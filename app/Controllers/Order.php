<?php

namespace App\Controllers;

class Order extends BaseController
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

        $Price = new \App\Models\Service\Price();
        $this->param = $Price->adjustParam($this->param);

        $ProductSet = new \App\Models\DB\ProductSet();

        if (!empty($this->param['id']))
            $this->param = array_merge($this->param,
                $ProductSet->getFromID($this->param['id']));

        $this->setCommonViews();
        return view('order/form', $this->param);
    }

    public function confirm()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');
        $session = session();

        $viewfile = 'order/form';

        $OrderHistory = new \App\Models\DB\OrderHistory();

        $this->param = $OrderHistory->adjustParam($this->param);
        $error = $OrderHistory->checkParam($this->param);

        if (!count($error)) {
            $viewfile = 'order/confirm';
            $this->param['price'] =
                (new \App\Models\Service\Price())->getPrice($this->param);

        } else
            $this->param['error'] = $error;

        $ProductSet = new \App\Models\DB\ProductSet();

        if (!empty($this->param['id']))
            $this->param = array_merge($this->param,
                $ProductSet->getFromID($this->param['id']));

        $this->setCommonViews();
        return view($viewfile, $this->param);
    }

    public function do() {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        $OrderHistory = new \App\Models\DB\OrderHistory();

        $this->param = $OrderHistory->adjustParam($this->param);
        $error = $OrderHistory->checkParam($this->param);

        if (!count($error)) {
            $this->param['user_id'] = $this->param['user']['id'];
            $this->param['product_set_id'] = $this->param['id'];
            $this->param['payment_limit'] =
                str_replace('/','-', $this->param['payment_limit']);
//            $this->param['payment_limit'] =
//            (new \app\Models\DB\LimitDateList())->getDateFromPrintUp($this->param);
            $this->param['status'] = 10;
            unset($this->param['id']);

            $this->param['order_id'] = $OrderHistory->modify($this->param);

            $Delivery = new \App\Models\DB\Delivery();
            $Delivery->modifyFromOrder($this->param);
        }

        return view('order/do', $this->param);
    }

    public function complete() {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        $OrderHistory = new \App\Models\DB\OrderHistory();

        $this->param['order_list'] =
            $OrderHistory->getLatest4user($this->param['user']['id']);

        $this->setCommonViews();
        return view('order/complete', $this->param);
    }

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
