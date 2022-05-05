<?php

namespace App\Controllers;

class Admin extends BaseController
{
    const ONEDAY = 86400;

    protected $param = [];

    public function __construct()
    {
        $request = \Config\Services::request();
        $a1 = $request->getGet();
        $a2 = $request->getPost();
        $a = array_merge($a1, $a2); 

        $Config = new \App\Models\Service\Config();
        $server = $request->getServer();

        $site_param = $Config->site_params();

        $a['site'] = $site_param;
        $a['environ'] = $Config->getEnviron();
        $a['b_mente'] = $Config->isMenteAdmin();

        $session = session();

        $a['admin'] = [
            'id' => $session->get('admin_id'),
            'name' => $session->get('admin_name'),
            'role' => $session->get('admin_role'),
            'client_code' => $session->get('admin_client_code')
        ];
//        $a['error'] = [];

        $this->param = $a;
    }

    private function setCommonViews() {

        $this->param['view'] = [
            'header'    => view('admin/_header', $this->param),
            'nav'       => view('admin/_nav',    $this->param),
            'side'      => view('admin/_side',   $this->param),
            'footer'    => view('admin/_footer', $this->param)
        ];
    }

    public function login()
    {
        $this->setCommonViews();
        return view('admin/login', $this->param);
    }

    public function login_do()
    {
        $AdminLogin = new \App\Models\AdminLogin();

        $error = $AdminLogin->checkParam($this->param);

        if (count($error) == 0) {
            $b = $AdminLogin->is_login($this->param);
            if (!$b) $error[] = 'ID・パスワードを確認してください。';
        }

        if (count($error)) {
            $this->param['error'] = $error;

            $this->setCommonViews();
            return view('admin/login', $this->param);
        }

        return view('admin/login_do', $this->param);
    }

    public function index()
    {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');
        
        $this->setCommonViews();
        return view('admin/index', $this->param);
    }

    public function user()
    {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');
		$this->noExpire();

        if (!empty($this->param['mode']) && $this->param['mode'] == 'search') {
            
            $User = new \App\Models\DB\User();
            $this->param['user_list'] = $User->getList($this->param);
            $this->param['statusName'] = $User->getStatusNameArray();

            $this->param['count_all'] = $this->param['user_list']['count_all'] ?? 0;
            unset($this->param['user_list']['count_all']);

            $this->param['pager'] = $User->getPagerInfo($this->param);
        }

        $this->setCommonViews();

        return view('admin/user', $this->param);
    }

    public function user_detail()
    {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $view_file = '/admin/user';
        if (empty($this->param['id']))
            $this->param['error'][] = '会員IDがありません。';

        else {
            (new \App\Models\DB\Point())
                ->modUserPoint($this->param['id']);

            $User = new \App\Models\DB\User();
            $data = $User->getFromID($this->param['id']);

            if (empty($data['id']))
                $this->param['error'][] = '会員情報がありません。';
            
            else {
                $this->param['r18'] = $User->isOver18($this->param['id']);
                $this->param['admin_username'] =
                    (new \App\Models\DB\Admin())->getNameFromID($data['admin_id']);

                $this->param = array_merge($this->param, $data);
                $view_file = '/admin/user/detail';
            }

            $MailTemplate = new \App\Models\DB\MailTemplate();
            $this->param['template_list'] = $MailTemplate->getList();
        }

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    public function user_form()
    {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $view_file = '/admin/user';
        if (empty($this->param['id']))
            $this->param['error'][] = '会員IDがありません。';

        else {
            $User = new \App\Models\DB\User();
            $data = $User->getFromID($this->param['id']);

            if (empty($data['id']))
                $this->param['error'][] = '会員情報がありません。';
            
            else {
                if (!isset($this->param['from']) || $this->param['from'] != 'confirm')
                    $this->param = array_merge($this->param, $data);
                
                $this->param['statusName'] = $User->getStatusNameArray();
                $view_file = '/admin/user/form';
            }
        }

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    public function user_confirm()
    {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $view_file = '/admin/user_form';
        $User = new \App\Models\DB\User();

        $this->param = $User->adjustParam($this->param);
        $this->param['error'] = $User->checkParam($this->param);

        if (!count($this->param['error']))
            $view_file = '/admin/user/confirm';

        $this->param['statusName'] = $User->getStatusNameArray();

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    public function user_do()
    {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $view_file = '/admin/user/do';

        $User = new \App\Models\DB\User();
        $this->param['error'] = $User->checkParam($this->param);

        if (!count($this->param['error'])) {
            $this->param['admin_id'] = (int)$this->param['admin']['id'];
            $b = $User->modify($this->param);
            if (empty($b))
            $this->param['error'][] = '会員情報変更できませんでした。';
        }

        $this->setCommonViews();
        return view($view_file, $this->param);
    }


    public function user_mail_edit()
    {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $view_file = '/admin/user';

        if (empty($this->param['user_id']))
            $this->param['error'][] = '会員IDがありません。';

        elseif (empty($this->param['template_id']))
            $this->param['error'][] = 'メール雛形IDがありません。';

        else {
            $view_file = '/admin/user/mail_edit';
            $User = new \App\Models\DB\User();
            $MailTemplate = new \App\Models\DB\MailTemplate();

            $this->param['user'] =
                $User->getFromID($this->param['user_id']);

            $this->param['template'] =
                $User->getFromID($this->param['template_id']);
        }

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    // ポイント履歴

    public function point_detail()
    {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $view_file = '/admin/user';
        if (empty($this->param['id']))
            $this->param['error'][] = '会員IDがありません。';

        else {
            $User = new \App\Models\DB\User();
            $data = $User->getFromID($this->param['id']);

            if (empty($data['id']))
                $this->param['error'][] = '会員情報がありません。';
            
            else {
                $this->param = array_merge($this->param, $data);

                $Point = new \App\Models\DB\Point();
                $this->param['history'] = $Point->getList4admin($this->param['id']);
                
                $view_file = '/admin/point/detail';
            }
        }

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    public function point_edit() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Point = new \App\Models\DB\Point();
        $User = new \App\Models\DB\User();

        if (!empty($this->param['id']))
            $this->param +=
                $Point->getFromID($this->param['id']);

        $this->param['statusName'] = $Point->getStatusName();

        if (!empty($this->param['user_id']))
            $this->param['user'] = $User->getFromID($this->param['user_id']);

        $this->setCommonViews();
        return view('admin/point/form', $this->param);
    }

    public function point_confirm() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $view_file = '/admin/point/form';

        $Point = new \App\Models\DB\Point();
        $User = new \App\Models\DB\User();
        $this->param['statusName'] = $Point->getStatusName();

        if (!empty($this->param['user_id']))
            $this->param['user'] = $User->getFromID($this->param['user_id']);
        
        $this->param['error'] = $Point->checkParam($this->param);

        if (!count($this->param['error'])) {
            $view_file = '/admin/point/confirm';
        }

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    public function point_do() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Point = new \App\Models\DB\Point();
        $this->param['error'] = $Point->checkParam($this->param);

        if (!count($this->param['error'])) {
            $this->param = $Point->adjustParam($this->param);
            $b = $Point->modify($this->param);

            if (empty($b))
            $this->param['error'][] = 'ポイント情報変更できませんでした。';

            else {
                $Point->modUserPoint($this->param['user_id']);

                if (empty($this->param['id']))
                    $Point->modUserExpire($this->param['user_id']);
            }
        }

        $this->setCommonViews();
        return view('admin/point/do', $this->param);
    }



    // 入稿管理

    public function order() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Order = new \App\Models\DB\OrderHistory();
        $this->param['statusName'] = $Order->getStatusNameArray();

        $this->param['product_set'] = (new \App\Models\DB\ProductSet())
                ->getList($this->param);

        if (!empty($this->param['mode']) && $this->param['mode'] == 'search') {
            
            if (empty($this->param['page'])) $this->param['page'] = 0;

            $this->param['user_list'] = $Order->getList($this->param);

            $this->param['count_all'] = $this->param['user_list']['count_all'] ?? 0;
            unset($this->param['user_list']['count_all']);

            $this->param['pager'] = $Order->getPagerInfo($this->param);
        }

        $this->setCommonViews();
        return view('admin/order', $this->param);
    }

    public function order_detail() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Order = new \App\Models\DB\OrderHistory();
        $ModOrder = new \App\Models\DB\ModOrderHistory();
        $Config = new \App\Models\Service\Config();

        $this->param['mode'] = 'detail';
        $this->param['order'] =
            $Order->getDetailFromID($this->param['id']);

        $this->param['mod_order'] =
            $ModOrder->getFromOrderID($this->param['id']);

        $client_code = $this->param['order']['product_set']['client_code'];
        $order_sheet = 'order_'.$this->param['id'].'.pdf';

        $this->param['statusName'] = $Order->getStatusName();

        $this->param['youbi'] =
            (new \App\Models\CommonLibrary())->getYoubiArray();

        $this->param['client_dir'] = $Config->getClientDirectory($client_code);
        $this->param['order_sheet_file'] = $order_sheet;
        
        $this->param['b_order_sheet'] = 
            file_exists($Config->getClientOrderSheetPlace($client_code).$order_sheet);

        $this->setCommonViews();
        return view('admin/order/detail', $this->param);
    }

    public function order_detail_judge() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Order = new \App\Models\DB\OrderHistory();
        $Product = new \App\Models\DB\ProductSet();
        $Config = new \App\Models\Service\Config();

        $data = $Order->find($this->param['id']);
        $status_before = $data['status'];

        if (!empty($this->param['ng_reason_other']))
            $this->param['ng_reason'] .= '（'
                .$this->param['ng_reason_other'].'）';

        $a = explode('　',$data['note']);
        foreach($a as $key=>$val)
            if ($val == ''
            ||  mb_strpos($val,'理由：',0,'UTF-8') !== false
            ||  mb_strpos($val,'理由1：',0,'UTF-8') !== false
            ||  mb_strpos($val,'理由2：',0,'UTF-8') !== false
            ||  mb_strpos($val,'理由3：',0,'UTF-8') !== false
            ) unset($a[$key]);

        if (!empty($this->param['ng_reason'])) {
            $ng_reason = (string)$this->param['ng_reason'];
            $a[] = $ng_reason;

            $status = 41; // 一次不備

            if (mb_strpos($ng_reason,'理由1',0,'UTF-8') !== false)
                $status = 41; // 一次不備

            if (mb_strpos($ng_reason,'理由2',0,'UTF-8') !== false)
                $status = 51; // 二次不備

            if (mb_strpos($ng_reason,'理由3',0,'UTF-8') !== false)
                $status = 61; // 三次不備

        } else {

            if (in_array($status_before, [40,41]))
                $status = 50; // 仮受付

            elseif (in_array($status_before, [50,51])) {

                $status = 60; // 表紙印刷開始

                if (!empty($this->param['to_status'])
                &&  in_array($this->param['to_status'], [62,70])
                )
                    $status = (int)$this->param['to_status'];
            }

            elseif (in_array($status_before, [60,61,62]))
                $status = 70; // 本文
        }
        
        $note = implode('　', $a);
        
        $Order->save([
            'id' => $this->param['id'],
            'status' => $status,
            'note' => $note
        ]);

        $data = $Order->find($this->param['id']);
        $data['mode'] = 'detail';
        $data = $Order->parseData($data);

        if (in_array($status, [41,51,61])) {
            $Model = new \App\Models\Mail\OrderNG();
            $data = $Model->adjust($data);
            $Model->sendAutomail($data);

        } elseif (in_array($status, [50,60,70])) {
            $Model = new \App\Models\Mail\OrderOK();
            $data = $Model->adjust($data);
            $Model->sendAutomail($data);
        }

        // 残り販売数を更新
        // $Product->updateOrderedCount($data['product_set_id']);

        $this->setCommonViews();
        return view('admin/order/detail_judge', $this->param);
    }

    public function order_edit() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Order = new \App\Models\DB\OrderHistory();
        $ModOrder = new \App\Models\DB\ModOrderHistory();
        $Config = new \App\Models\Service\Config();

        $this->param['mode'] = 'detail';
        $this->param +=
            $Order->getDetailFromID($this->param['id']);

        $this->param['mod_order'] =
            $ModOrder->getFromOrderID($this->param['id']);

        $this->param['statusName'] = $Order->getStatusName();

        $this->param['client_dir'] =
            $Config->getClientDirectory($this->param['product_set']['client_code']);

        $this->param['youbi'] =
            (new \App\Models\CommonLibrary())->getYoubiArray();

        $this->setCommonViews();
        return view('admin/order/mod_form', $this->param);
    }

    public function order_confirm() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Order = new \App\Models\DB\OrderHistory();
        $ModOrder = new \App\Models\DB\ModOrderHistory();
        $Config = new \App\Models\Service\Config();

        $this->param['org'] =
            $Order->getDetailFromID($this->param['id']);

        $this->param['price'] =
            (new \App\Models\Service\PriceInterface())
                ->getObject($this->param['client_code'])
                ->getPrice($this->param);
            
        $this->param['statusName'] = $Order->getStatusName();

        $this->param['client_dir'] =
            $Config->getClientDirectory($this->param['client_code']);

        $this->setCommonViews();
        return view('admin/order/mod_confirm', $this->param);
    }

    public function order_do() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Order = new \App\Models\DB\OrderHistory();
        $ModOrder = new \App\Models\DB\ModOrderHistory();
        $Delivery = new \App\Models\DB\Delivery();

        $org = $Order->find($this->param['id']);
        $org = $Order->parseData($org);
        $dest = $Order->makeData($this->param); // protect

        // 入稿内容調整待ち → 未入金
        if ($this->param['status'] == 12) {

            $org['mode'] = 'detail';
            $temp = $Order->parseData($org);
            $temp['adjust_detail_text'] =
                '【合計金額　'.$this->param['price'].'円　'.
                '入金期限　'.str_replace('-','/', $this->param['payment_limit']).
                '】'.($this->param['adjust_note_front'] ?? '');
            $temp['status'] = 10;

            $OrderOK = new \App\Models\Mail\OrderOK();
            $temp = $OrderOK->adjust($temp);
            $OrderOK->sendAutoMail($temp);
            unset($OrderOK, $temp);

            $this->param['status'] = 10;
        }

        $Order->save([ // 受発注データは以下項目のみ更新、他はmodレコードを更新
            'id'            => $this->param['id'],
            'print_title'   => $this->param['print_title'],
            'payment_limit' => $this->param['payment_limit'],
            'status'        => $this->param['status'],
            'note'          => $this->param['note'],
            'protect'       => $dest['protect']
        ]);

        $mod_data = (array)$this->param;
        $mod_data['org_id'] = $mod_data['id'];

        if (!empty($mod_data['mod_id']))
            $mod_data['id'] = $mod_data['mod_id'];
        else unset($mod_data['id']);

        $ModOrder->modify($mod_data);

        if (isset($this->param['number_delivery'])
        &&  count($this->param['number_delivery']))
            foreach($this->param['number_delivery'] as $id=>$num) {
                $Delivery->save([
                    'id' => $id,
                    'number' => $num
                ]);
            }

        $this->setCommonViews();
        return view('admin/order/mod_do', $this->param);
    }

    // 入稿作業書出力（直接表示　デバッグ用）
    public function order_sheet() {

        $Config = new \App\Models\Service\Config();
        $Order = new \App\Models\DB\OrderHistory();
        $Price = new \App\Models\Service\Price();

        $data   = $Order->getDetailFromID($this->param['id']);

        ini_set('memory_limit', '2G');
        ini_set("max_execution_time",6000);

        $ExportPDF = new \App\Models\ExportPDF();
//        header('Content-type: application/pdf');
//        header('Content-Disposition: attachment; filename="order_'.$data['id'].'.pdf"');

        $data['save_file_place'] =
            $Config->getClientOrderSheetPlace($data['product_set']['client_code']);
        
        $ExportPDF->order_sheet($data);
        echo 'ok.';
//        echo $data['save_file_place'];
    }

    // 入稿作業書出力（バックグラウンド方式）
    public function export_sheet() {

        $Config = new \App\Models\Service\Config();

        if (empty($this->param['id']) || !is_numeric($this->param['id']))
            return 0;

        $index_php = $Config->getSiteIndexPlace();

        $exec_command = '/usr/bin/php7.4 '.$index_php.' Batch make_order_sheet '.
            $this->param['id'].' > /dev/null &';

        exec($exec_command, $output, $return);
//        echo $exec_command.'<br>';

        echo 1;
    }

    // CSV出力
    public function export_order_csv() {

        if (empty($this->param['id']) || !is_numeric($this->param['id']))
            return NULL;

        $order = (new \App\Models\DB\OrderHistory())
            ->getDetailFromID($this->param['id']);
        
        $data = (new \App\Models\ExportCSV())->getOrderData($order);

        $this->response->setHeader("Content-Type", "application/octet-stream");
        $this->response->setHeader('Content-Disposition'
            ,'attachment; filename=order_'.$this->param['id'].'.csv'
        );
        echo $data;
/*
        $this->response->download($data, null)
            ->setFileName('order_'.$this->param['id'].'.csv');
*/
    }

    // 発送先編集

    public function delivery_edit() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Delivery = new \App\Models\DB\Delivery();
        $Order = new \App\Models\DB\OrderHistory();

        $data = $Delivery->getFromID($this->param['id']);
        $this->param['delivery'] = $data;

        $data2 = $Order->find($data['order_id']);
        $this->param['order'] = $Order->parseData($data2);

        $this->setCommonViews();
        return view('admin/order/delivery', $this->param);
    }

    public function delivery_do() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Delivery = new \App\Models\DB\Delivery();

        $this->param['error'] = $Delivery->checkForAdmin($this->param);

        if (!count($this->param['error']))
            $Delivery->modifyFromAdmin($this->param);

        $this->setCommonViews();
        return view('admin/order/delivery_do', $this->param);
    }

    // 分納部数編集

    public function divide_edit() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Delivery = new \App\Models\DB\Delivery();
        $Order = new \App\Models\DB\OrderHistory();

        $this->param['order'] = $Order->getDetailFromID($this->param['id']);

        $this->setCommonViews();
        return view('admin/order/divide', $this->param);
    }

    public function divide_do() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Delivery = new \App\Models\DB\Delivery();
        $Order = new \App\Models\DB\OrderHistory();
        $ModOrder = new \App\Models\DB\ModOrderHistory();

        $data = $Order->find($this->param['id']);
        $ex = json_decode($data['ex'], true);
        $ex['number_kaiteki']   = $this->param['number_kaiteki'] ?? 0;
        $ex['number_home']      = $this->param['number_home'] ?? 0;
        $ex['delivery_divide']  = $this->param['delivery_divide'] ?? 0;
        $Order->save([
            'id' => $this->param['id'],
            'note' => $this->param['note'] ?? '',
            'ex' => json_encode($ex)
        ]);
/*
        将来mod_order_historyを更新する場合は、ここを有効にする
        $data2 = $ModOrder->getFromOrderID($this->param['id']);

        if (!empty($data2['id'])) {
            $data2['number_kaiteki']    = $ex['number_kaiteki'];
            $data2['number_home']       = $ex['number_home'];
            $data2['delivery_divide']   = $ex['delivery_divide'];
            $ModOrder->modify($data2);
        }
*/
        if (isset($this->param['number_delivery'])
        &&  count($this->param['number_delivery']))
            foreach($this->param['number_delivery'] as $id=>$num) {
                $Delivery->save([
                    'id' => $id,
                    'number' => $num
                ]);
            }

        $this->setCommonViews();
        return view('admin/order/divide_do', $this->param);
    }

    // 決済管理

    public function payment() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        if (!empty($this->param['mode']) && $this->param['mode'] == 'search') {
            
            $Payment = new \App\Models\DB\Payment();
            $this->param['payment_list'] = $Payment->getList($this->param);
            $this->param['statusName'] = $Payment->getStatusNameArray();

            $this->param['count_all'] = $this->param['payment_list']['count_all'] ?? 0;
            unset($this->param['payment_list']['count_all']);

            $Order = new \App\Models\DB\OrderHistory();
            $this->param['pager'] = $Order->getPagerInfo($this->param);
        }

        $this->setCommonViews();
        return view('admin/payment', $this->param);
    }

    public function payment_detail() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Payment = new \App\Models\DB\Payment();
        $Config = new \App\Models\Service\Config();

        $this->param['mode'] = 'detail';
        $this->param['payment'] =
            $Payment->getDetailFromID($this->param['id']);

        $this->param['statusName'] = $Payment->getStatusNameArray();

        $this->param['youbi'] =
            (new \App\Models\CommonLibrary())->getYoubiArray();

        $this->setCommonViews();
        return view('admin/payment/detail', $this->param);
    }

    public function payment_form() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $view_file = '/admin/payment';
        if (empty($this->param['id']))
            $this->param['error'][] = '決済IDがありません。';

        else {
            $view_file = '/admin/payment/detail';
            $Payment = new \App\Models\DB\Payment();
            $data = $Payment->getFromID($this->param['id']);

            if (empty($data['id']))
                $this->param['error'][] = '決済情報がありません。';
            
            else {
                if (!isset($this->param['from']) || $this->param['from'] != 'confirm')
                    $this->param += $data;
                
                $this->param['statusName'] = $Payment->getStatusNameArray();
                $view_file = '/admin/payment/form';
            }
        }

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    public function payment_confirm() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Payment = new \App\Models\DB\Payment();

        $view_file = '/admin/payment/form';
        $this->param = $Payment->adjustParamAdmin($this->param); 
        $this->param['error'] = $Payment->checkParamAdmin($this->param);

        if (!count($this->param['error']))
            $view_file = '/admin/payment/confirm';

        $this->param['statusName'] = $Payment->getStatusNameArray();

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    public function payment_do() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $view_file = '/admin/payment/do';

        $Payment = new \App\Models\DB\Payment();
        $this->param['error'] = $Payment->checkParamAdmin($this->param);

        if (!count($this->param['error'])) {
            $this->param['admin_id'] = (int)$this->param['admin']['id'];
            $b = $Payment->modify($this->param);
            if (empty($b))
            $this->param['error'][] = '決済情報変更できませんでした。';
        }

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    // 決済照会

    public function payment_check_result() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        // Payment->checkResult 実行する前に以下設定を行う
        // ペイジェントモジュールがPHP8非推奨記述を含む＆契約者判断で修正できない為
        ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED & ~E_NOTICE);

        $Payment = new \App\Models\DB\Payment();
        $res = $Payment->checkResult($this->param);

        if (!empty($res['error'])) $this->param['error'] = $res['error'];

        $this->setCommonViews();
        return view('admin/payment/check_result', $this->param);
    }

    // 決済キャンセル

    public function payment_cancel() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Payment = new \App\Models\DB\Payment();

        if (!empty($this->param['id'])) {

            $note = 
            (   isset($this->param['order_ids'])
            &&  count($this->param['order_ids'])
            )
            ? '発注ID：'.implode(',', $this->param['order_ids'])
            : '';

            $res = $Payment->save([
                'id' => $this->param['id'],
                'status' => 99,
                'note' => $note
            ]);
            $data = $Payment->find($this->param['id']);
            $data = $Payment->parseData($data);
            $data['payment_id'] = $data['id'];
            $data['b_not_mail'] = true;
            (new \App\Models\DB\OrderHistory())->cancelPayment($data);
            (new \App\Models\DB\Point())->resetUseFromPayment($data);
        }

        if (!empty($res['error'])) $this->param['error'] = $res['error'];

        $this->setCommonViews();
        return view('admin/payment/cancel', $this->param);
    }

    // 商品管理

    public function product() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        if (!empty($this->param['mode'])
        &&  $this->param['mode'] == 'search') {
            $Product = new \App\Models\DB\ProductSet();
            $this->param['product_list'] = $Product->getList($this->param);
        }

        $Client = new \App\Models\DB\Client();
        $this->param['client_list'] = $Client->findColumn('code');

        $this->setCommonViews();
        return view('admin/product', $this->param);
    }

    public function product_detail() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Product = new \App\Models\DB\ProductSet();
        $Config = new \App\Models\Service\Config();

        $this->param['mode'] = 'detail';
        $this->param['product'] =
            $Product->getFromID($this->param['id']);

        $this->param['youbi'] =
            (new \App\Models\CommonLibrary())->getYoubiArray();

        $this->setCommonViews();
        return view('admin/product/detail', $this->param);
    }

    public function product_update_ordered() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Product = new \App\Models\DB\ProductSet();
        $this->param['result'] = $Product->updateOrderedCount(
            $this->param['id']);

        $this->setCommonViews();
        return view('admin/product/update_ordered', $this->param);
    }

    public function product_form() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $view_file = '/admin/product';
        if (empty($this->param['id']))
            $this->param['error'][] = '商品IDがありません。';

        else {
            $Product = new \App\Models\DB\ProductSet();
            $data = $Product->getFromID($this->param['id']);

            if (empty($data['id']))
                $this->param['error'][] = '商品情報がありません。';
            
            else {
                if (!isset($this->param['from']) || $this->param['from'] != 'confirm')
                    $this->param += $data;
                
                $this->param['statusName'] = $Product->getStatusNameArray();
                $view_file = '/admin/product/form';
            }
        }

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    public function product_confirm() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Product = new \App\Models\DB\ProductSet();

        $view_file = '/admin/product/form';
        $this->param = $Product->adjustParam($this->param);
        $this->param['error'] = $Product->checkParam($this->param);

        if (!count($this->param['error']))
            $view_file = '/admin/product/confirm';

        $this->param['statusName'] = $Product->getStatusNameArray();

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    public function product_do() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $view_file = '/admin/product/do';

        $Product = new \App\Models\DB\ProductSet();
        $this->param['error'] = $Product->checkParam($this->param);

        if (!count($this->param['error'])) {
            $this->param['admin_id'] = (int)$this->param['admin']['id'];
            $b = $Product->modify($this->param);
            if (empty($b))
            $this->param['error'][] = '商品情報変更できませんでした。';
        }

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    // 締切管理

    public function limit() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Limit = new \App\Models\DB\LimitDateList();

        if ($this->param['admin']['role'] == 'master') {
            $this->param['limit_date_list'] = $Limit->getList('');
            $this->param['client_list'] = 
                (new \App\Models\DB\Client())->findColumn('code');

        } else {
            $this->param['limit_date_list'] = $Limit->getList(
                $this->param['admin']['client_code']);

            $this->param['client_list'] = [
                $this->param['admin']['client_code']];
        }

        $this->setCommonViews();
        return view('admin/limit', $this->param);
    }

    public function limit_do() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $view_file = '/admin/limit_do';

        $Limit = new \App\Models\DB\LimitDateList();
        $this->param['error'] = $Limit->checkParam($this->param);

        if (!count($this->param['error'])) {
            $this->param['admin_id'] = (int)$this->param['admin']['id'];
            $b = $Limit->modify($this->param);
            if (empty($b))
            $this->param['error'][] = '締切情報変更できませんでした。';
        }

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    // メルマガ管理

    public function mail_template() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        if (!empty($this->param['mode']) && $this->param['mode'] == 'search') {
            
            $MailTemplate = new \App\Models\DB\MailTemplate();
            $this->param['template_list'] = $MailTemplate->getList($this->param);
            $this->param['statusName'] = $MailTemplate->getStatusNameArray();

            $this->param['count_all'] = $this->param['template_list']['count_all'] ?? 0;
            unset($this->param['template_list']['count_all']);

            $this->param['pager'] = $MailTemplate->getPagerInfo($this->param);
        }

        $this->setCommonViews();
        return view('admin/mail_template', $this->param);
    }

    public function mail_template_detail() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        if (empty($this->param['mode'])
        &&  !empty($this->param['id'])
        &&  0 < intval($this->param['id']))
        {
            $MailTemplate = new \App\Models\DB\MailTemplate();
            $data = $MailTemplate->getFromID($this->param['id']);

            if (!empty($data['id'])) {
                $this->param['subject'] = $data['subject'];
                $this->param['body'] = $data['body'];
                $this->param['mode'] = 'edit';
            }

            $this->param['statusName'] = $MailTemplate->getStatusNameArray();
        }

        $this->setCommonViews();
        return view('admin/mail/template_detail', $this->param);
    }

    public function mail_template_confirm() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $view_file = '/admin/mail/template_detail';
        $MailTemplate = new \App\Models\DB\MailTemplate();

        $this->param = $MailTemplate->adjustParam($this->param);
        $this->param['error'] = $MailTemplate->checkParam($this->param);

        if (!count($this->param['error']))
            $view_file = '/admin/mail/template_confirm';

        $this->param['statusName'] = $MailTemplate->getStatusNameArray();

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    public function mail_template_do() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $view_file = '/admin/mail/template_do';

        $MailTemplate = new \App\Models\DB\MailTemplate();
        $this->param['error'] = $MailTemplate->checkParam($this->param);

        if (!count($this->param['error'])) {
            $this->param['admin_id'] = (int)$this->param['admin']['id'];
            $b = $MailTemplate->modify($this->param);
            if (empty($b))
            $this->param['error'][] = 'メールテンプレート登録/更新できませんでした。';
        }

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    public function mail_sendlist() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        if (!empty($this->param['mode']) && $this->param['mode'] == 'search') {
            
            $MailSendlist = new \App\Models\DB\MailSendlist();
            $this->param['send_list'] = $MailSendlist->getList($this->param);
            $this->param['statusName'] = $MailSendlist->getStatusNameArray();

            $this->param['count_all'] = $this->param['send_list']['count_all'] ?? 0;
            unset($this->param['send_list']['count_all']);

            $Order = new \App\Models\DB\OrderHistory();
            $this->param['pager'] = $Order->getPagerInfo($this->param);
        }

        $this->setCommonViews();
        return view('admin/mail_sendlist', $this->param);
    }

    public function mail_magazine() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        if (!empty($this->param['mode']) && $this->param['mode'] == 'search') {
            
            $User = new \App\Models\DB\User();
            $MailSendlist = new \App\Models\DB\MailSendlist();
            $this->param['user_list'] = $User->getMagazineList($this->param);

            $this->param['statusName'] = $User->getStatusNameArray();
            $this->param['send_statusName'] = $MailSendlist->getStatusNameArray();

            $this->param['count_all'] = $this->param['user_list']['count_all'] ?? 0;
            unset($this->param['user_list']['count_all']);

            $this->param['pager'] = $User->getPagerInfo($this->param);
        }

        $MailTemplate = new \App\Models\DB\MailTemplate();
        $this->param['template_list'] = $MailTemplate->getList(['status' => 0]);

        $this->setCommonViews();
        return view('admin/mail/magazine', $this->param);
    }

    public function mail_magazine_confirm() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $view_file = '/admin/mail/magazine';

        $User = new \App\Models\DB\User();
        $MailTemplate = new \App\Models\DB\MailTemplate();
        $MailSendlist = new \App\Models\DB\MailSendlist();
        $Magazine = new \App\Models\Mail\Magazine();

        $this->param = $Magazine->adjustParam($this->param);
        $this->param['error'] = $Magazine->checkParam($this->param);

        if (!count($this->param['error'])) {
            $view_file = '/admin/mail/magazine_confirm';

            $this->param['user_list'] = $User->getListFromIDs($this->param['user_id']);
            $this->param['statusName'] = $User->getStatusNameArray();
            $this->param['send_statusName'] = $MailSendlist->getStatusNameArray();
    
            $this->param['template'] = $MailTemplate->getFromID($this->param['template_id']);

            $this->param['username'] = $this->param['user_list'][0]['name'];
            $this->param['replace_keys'] = ['username'];
            $this->param['replace_text'] = $this->param['template']['subject'];
            $this->param['subject_sample'] = $Magazine->replaceText($this->param);

            $this->param['replace_text'] = $this->param['template']['body'];
            $this->param['body_sample'] = $Magazine->replaceText($this->param);
        }

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    public function mail_magazine_do() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $view_file = '/admin/mail/magazine_do';

        $User = new \App\Models\DB\User();
        $MailTemplate = new \App\Models\DB\MailTemplate();
        $MailSendlist = new \App\Models\DB\MailSendlist();
        $MailBuffer = new \App\Models\DB\MailBuffer();
        $Magazine = new \App\Models\Mail\Magazine();

        $template = $MailTemplate->getFromID($this->param['template_id']);

        foreach($this->param['user_id'] as $id) {

            // 送信履歴に登録
            $MailSendlist->insert([
                'user_id' => $id,
                'template_id' => $this->param['template_id'],
                'request_date' => $this->param['request_date']
            ]);
            $sendlist_id = (int)$MailSendlist->insertID;

            // メルマガ送信内容登録    
            $userdata = $User->getFromID($id);
            $this->param['user'] = $userdata;

            $this->param['username'] = $userdata['name'];
            $this->param['replace_keys'] = ['username'];
            $this->param['replace_text'] = $template['subject'];
            $this->param['subject'] = $Magazine->replaceText($this->param);

            $this->param['replace_text'] = $template['body'];
            $this->param['body'] = $Magazine->replaceText($this->param);
            $this->param['to'] = $userdata['mail_address'];

            $p = $MailBuffer->makeData($this->param);
            $MailBuffer->insert([
                'sendlist_id' => $sendlist_id,
                'request_date' => $this->param['request_date'],
                'protect' => $p['protect']
            ]);
        }

        $this->setCommonViews();
        return view($view_file, $this->param);
    }

    // 管理アカウントの管理

    public function admin() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Admin = new \App\Models\DB\Admin();
        $data = $Admin->getFromID($this->param['admin']['id']);

        $this->param['name'] = $data['name'] ?? '';
        $this->param['mail_address'] = $data['mail_address'] ?? '';

        $Client = new \App\Models\DB\Client();
        $this->param['client_list'] = $Client->findColumn('code');

        if ($this->param['admin']['role'] == 'master')
            $this->param['admin_list'] = $Admin->getList();

        $this->setCommonViews();
        return view('admin/admin', $this->param);
    }

    public function admin_do() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Admin = new \App\Models\DB\Admin();
        $this->param['error'] = $Admin->checkParam($this->param);

        if (!count($this->param['error'])) {

            $b = $Admin->modify($this->param);
            
            if (empty($b))
                $this->param['error'][] = '管理ユーザー情報変更できませんでした。';

            elseif (!empty($this->param['id'])
            &&  $this->param['mode'] == 'modify'
            &&  $this->param['id'] == $this->param['admin']['id']) {

                $session = session();
                $session->set('admin_name', $this->param['name']);
            }
        }

        $this->setCommonViews();
        return view('admin/admin_do', $this->param);
    }

    // システム管理者限定

    public function system() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $this->setCommonViews();
        return view('admin/system', $this->param);
    }

    // 日次レポート送信

    public function report_4_client() {
        if (!$this->check_login()
        ||  $this->check_mente()) return redirect()->to('/admin/login');

        $Client = new \App\Models\DB\Client();
        $this->param['error'] = $Client->sendDailyReport();

        $this->setCommonViews();
        return view('admin/report_4_client', $this->param);
    }

    // ログアウト

    public function logout() {
        $AdminLogin = new \App\Models\AdminLogin();
        $AdminLogin->logout();
        $this->param['admin'] = $AdminLogin->getAdminInfo();

        $this->setCommonViews();
        return view('admin/logout', $this->param);
    }

    // キャッシュ期限無期限

    private function noExpire() {

        $DT = new \DateTime();
        $DT->modify('+1 day');
		
		header('Expires: '.$DT->format('D, d M Y H:i:s T'));
		header('Cache-Control: max-age=86400');
//		header('Pragma:');

//        $this->response->setHeader('Pragma', '');
//        $this->response->setHeader('Expires', $DT->format('D, d M Y H:i:s T'));
//        $this->response->setHeader('Cache-Control', 'max-age=86400');
//        $this->response->setHeader('Cache-Control', 'max-age=-1');

	}

    // ログインチェック

    private function check_login() {
        $AdminLogin = new \App\Models\AdminLogin();
        if (!$AdminLogin->is_login($this->param)) return false;
        $this->param['admin'] = $AdminLogin->getAdminInfo();
        return true;
    }

    private function check_mente() {
        $Config = new \App\Models\Service\Config();
        return $Config->isMenteAdmin();
    }
}
