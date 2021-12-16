<?php

namespace App\Controllers;

class Payment extends BaseController
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
        $a['b_mente'] = $Config->isMenteUser();

        $session = session();

        $a['user'] = [
            'id' => $session->get('user_id'),
            'name' => $session->get('user_name'),
            'point' => $session->get('user_point')
        ];

        $this->param = $a;
    }

    private function setCommonViews() {

        $this->param['view'] = [
            'header'    => view('user/_header', $this->param),
            'nav'       => view('user/_nav',    $this->param),
            'side'      => view('user/_side',   $this->param),
            'footer'    => view('user/_footer', $this->param)
        ];
    }

    public function index()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        return redirect()->to('/');
    }

    public function form()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        $OrderHistory = new \App\Models\DB\OrderHistory();

        $this->param['order_list'] =
            $OrderHistory->getYetPayment($this->param['user']['id']);

        $this->param['statusName'] = $OrderHistory->getStatusName();

        $this->setCommonViews();
        return view('payment/form', $this->param);
    }

    public function confirm()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        $Config = new \App\Models\Service\Config();
        $User = new \App\Models\DB\User();
        $OrderHistory = new \App\Models\DB\OrderHistory();

        $temp = [];
        if (isset($this->param['payment_order'])
        &&  count($this->param['payment_order'])
        )
            foreach($this->param['payment_order'] as $id)
                $temp[] = $OrderHistory->getDetailFromID($id);

        $this->param['order_list'] = $temp;
//            $OrderHistory->getPaymentOrder($this->param['payment_order']);

        $tempuser = $User->getFromID($this->param['user']['id']);
        $this->param['point'] = $tempuser['point'];

        $this->param['give_point_ratio'] = $Config->getPointRatio();
        $this->param['use_point_ratio'] = $Config->getUsePointRatio();
        $this->param['payment_fee'] = $Config->getPaymentFee();

        $this->setCommonViews();
        return view('payment/confirm', $this->param);
    }

    public function do() {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');
        $this->setCommonViews();

        $Payment = new \App\Models\DB\Payment();
        $this->param = $Payment->adjustParam($this->param);
        $error = $Payment->checkParam($this->param);

        if (!count($error)) {

            $this->param['user_id'] = (int)$this->param['user']['id'];

            $OrderHistory = new \App\Models\DB\OrderHistory();
//            $this->param = $OrderHistory->getPaymentLimit($this->param);
            $this->param = $Payment->getPaymentLimit($this->param);

            $this->param['id'] = $Payment->modify($this->param);

            if (!empty($this->param['id'])) {

                $Point = new \App\Models\DB\Point();
                $Point->setUseFromPayment($this->param);

                // 発注データに決済IDを書き込む
                $OrderHistory->modifyFromPayment($this->param);
                
                $User = new \App\Models\DB\User();
                $this->param['userdata'] = $User->getFromID($this->param['user_id']);
    
                // 決済代行システムに接続するための情報を積み込む
                $this->param = $Payment->adjustBeforePayment($this->param);

                // ペイジェントURL直結
/*                
                $Config = new \App\Models\Service\Config();
                $lib = new \App\Models\CommonLibrary();
                $result = $lib->httpGetContents(
                    $Config->getPaymentLinkURL(), $this->param, 'post');
                
                $this->param['result'] = $result;
*/
            } else {
                $error[] = '決済手続を中断しました（履歴作成不可）';
            }
        }
        $this->param['error'] = $error;

        return view('payment/do', $this->param);
    }

    public function do_standby() { // 未使用
        if (!$this->check_login()) return redirect()->to('/login');

        $this->setCommonViews();
        return view('payment/do_standby', $this->param);
    }

    public function error() { // 決済エラー処理　ログイン状態よりも処理を優先
//        if (!$this->check_login()) return redirect()->to('/login');

        if (!empty($this->param['user']['id'])
        &&  !empty($this->param['id'])
        ) {
            $Payment = new \App\Models\DB\Payment();

            $ex = [
                'error_code' => $this->param['error_code'] ?? 0,
                'error_detail' => $this->param['error_detail'] ?? ''
            ];
            
            $Payment->save([
                'id' => $this->param['id'],
                'status' => 12,
                'ex' => json_encode($ex)
            ]);

            $data = $Payment->find($this->param['id']);
            $data['payment_id'] = $data['id'];
            $data['b_not_mail'] = true;
            $Payment->modOrderPoint($data, 10, 12);
//            (new \App\Models\DB\OrderHistory())->cancelPayment($this->param);
//            (new \App\Models\DB\Point())->resetUseFromPayment($this->param);
        }

        $this->setCommonViews();
        return view('payment/error', $this->param);
    }

    public function return() {
//        if (!$this->check_login()) return redirect()->to('/login');
        $this->check_login();

        $Payment = new \App\Models\DB\Payment();
        $data = $Payment->getLastPaymentID($this->param['user']['id']);
/*
        $this->param['id'] =
        $this->param['payment_id'] = $this->param['trading_id']; // payment_id
        $this->param['user_id'] = $this->param['user']['id'];

        if ((int)$this->param['type'] == 2) { // card

            $Payment = new \App\Models\DB\Payment();
            $Payment->find($data['id']);
*/
            $this->param['payment_id'] = $data['id']; // payment_id
            $this->param['user_id'] = $this->param['user']['id'];

            if ($data['status'] != 40) { // ペイジェントAPIとの二重処理を防ぐ

                $Payment->save([
                    'id' => $data['id'],
                    'type' => 'card',
                    'status' => 40
                ]);
                $Payment->modOrderPoint($data, $data['status'], 40);
/*
                (new \App\Models\DB\OrderHistory())->completePayment($this->param);
                (new \App\Models\DB\Point())->setAddFromPayment($data);
*/
            }
//        }

        $this->setCommonViews();
        return view('payment/return', $this->param);
    }

    public function complete() {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        $this->setCommonViews();
        return view('payment/complete', $this->param);
    }

    public function stop() {
//        if (!$this->check_login()) return redirect()->to('/login');
        $this->check_login();

        if (!empty($this->param['payment_id'])) {

            $Payment = new \App\Models\DB\Payment();
//            $data = $Payment->getLastPaymentID($this->param['user']['id']);
            
            $Payment->save([
                'id' => $this->param['payment_id'],
                'status' => 99
            ]);

            $data = $Payment->find($this->param['payment_id']);
            $data['payment_id'] = $data['id'];
            $data['b_not_mail'] = true;
            $Payment->modOrderPoint($data, 10, 99);
/*
            if (isset($data['user_id'])) {
                $data['payment_id'] = $data['id'];
                (new \App\Models\DB\OrderHistory())->cancelPayment($data);
                (new \App\Models\DB\Point())->resetUseFromPayment($data);
            }
*/
        }

        $this->setCommonViews();
        return view('payment/stop', $this->param);
    }

    public function stopped() {
        if ($this->check_mente()) return redirect()->to('/mente');
//        if (!$this->check_login()) return redirect()->to('/login');

        $this->setCommonViews();
        return view('payment/stopped', $this->param);
    }


    public function reset_bank() {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        if (!empty($this->param['payment_id'])) {

            $Payment = new \App\Models\DB\Payment();
            $data = $Payment->find($this->param['payment_id']);

            if (isset($data['id'])
            &&  $data['user_id'] == $this->param['user']['id']
            &&  $data['type'] == 'bank'
            ) {
                $Payment->save([
                    'id' => $data['id'],
                    'status' => 99
                ]);

                $data['payment_id'] = $data['id'];
                $data['b_not_mail'] = true;
                $Payment->modOrderPoint($data, $data['status'], 99);
//                (new \App\Models\DB\OrderHistory())->cancelPayment($data);
//                (new \App\Models\DB\Point())->resetUseFromPayment($data);
            }
        }
        $this->setCommonViews();
        return view('payment/reset_bank', $this->param);
    }


    //

    public function list() {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        $OrderHistory = new \App\Models\DB\OrderHistory();

        $this->param['order_list'] =
            $OrderHistory->getList4user($this->param['user']['id']);

        $this->param['statusName'] = $OrderHistory->getStatusName();

        $this->setCommonViews();
        return view('order/history_list', $this->param);
    }

    public function detail() {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        $OrderHistory = new \App\Models\DB\OrderHistory();

        $this->param['mode'] = 'detail';
        $this->param['order'] =
            $OrderHistory->getDetailFromID($this->param['id']);

        $this->param['statusName'] = $OrderHistory->getStatusName();

        $this->param['youbi'] =
            (new \App\Models\CommonLibrary())->getYoubiArray();

        $this->setCommonViews();
        return view('order/history_detail', $this->param);
    }

    // 接続テスト

    public function test() {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        $Payment = new \App\Models\DB\Payment();

        if (!empty($this->param['mode'])) {
            $this->param = $Payment->getPaymentHash($this->param);

        } else {
            $this->param['id'] = 2; // ID as payment_id
            $this->param['amount'] = 10480;
            if (empty($this->param['payment_term_day'])
            &&  empty($this->param['payment_term_min']))
                $this->param['payment_term_day'] = 6;
        
            $this->param = $Payment->adjustBeforePayment($this->param);
            $this->param['customer_family_name']        = '手簀戸';
            $this->param['customer_name']               = '赤雲人';
            $this->param['customer_family_name_kana']   = 'ﾃｽﾄ';
            $this->param['customer_name_kana']          = 'ｱｶｳﾝﾄ';
            $this->param['customer_tel']                = '09012345678';
            $this->param['customer_id']                 = (int)$this->param['user']['id'];
        }

        return view('payment/test', $this->param);
    }

    // ログインチェック

    private function check_login() {
        $UserLogin = new \App\Models\UserLogin();
        if (!$UserLogin->is_login($this->param)) return false;
        $this->param['user'] = $UserLogin->getUserInfo();
        return true;
    }

    private function check_mente() {
        $Config = new \App\Models\Service\Config();
        return $Config->isMenteUser();
    }
}
