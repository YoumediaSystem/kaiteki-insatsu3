<?php

namespace App\Controllers;

class Api extends BaseController
{

    protected $param = [];

    public function __construct()
    {
        $request = \Config\Services::request();
        $a1 = $request->getGet();
        $a2 = $request->getPost();
        $a = array_merge($a1, $a2); 
/*
        $Config = new \App\Models\Service\Config();
        $server = $request->getServer();

        $site_param = $Config->site_params();

        $a['site'] = $site_param;
        $a['environ'] = $Config->getEnviron();
*/
        $this->param = $a;
    }

    public function index()
    {
        return show_403();
    }

    public function send_payment_data() { // ペイジェント送信内容をcURLで送信　未使用

        $Config = new \App\Models\Service\Config();
        $lib = new \App\Models\CommonLibrary();

        $this->param['isbtob'] = 1;// text response mode

        $result = $lib->httpGetContents(
            $Config->getPaymentLinkURL(), $this->param, 'post');

        return view('ajax_result', ['result' => $result]);
    }

    public function payment_result() {
        if ($this->check_mente()) return show_503();

        $PaymentResult = new \App\Models\DB\PaymentResult();

        $data = $PaymentResult->find($this->param['payment_notice_id']);

        if (empty($data['id'])) {

            $result_org = [];
            foreach($this->param as $key => $val)
                if (strlen($val)) $result_org[$key] = $val;

            $PaymentResult->insert([
                'id'                => (int)$this->param['payment_notice_id'],
                'trading_id'        => (int)$this->param['trading_id'],
                'paygent_id'        => (int)$this->param['payment_id'],
                'status'            => (int)$this->param['payment_status'],
                'result_original'   => json_encode($result_org)
            ]);

            $PaymentResult->modPaymentRecord($this->param);
/*
            $Payment = new \App\Models\DB\Payment();

            if (!empty($this->param['payment_type']))
                $type = $Payment
                    ->getPaymentTypeName($this->param['payment_type']);

            if (!empty($this->param['trading_id'])
            &&  !empty($this->param['payment_status'])) {

                $Payment->modify([
                    'id'        => (int)$this->param['trading_id'],
                    'type'      => $type,
                    'status'    => (int)$this->param['payment_status']
                ]);
                
                $data = $this->getFromID($this->param['trading_id']);

                $data['payment_id'] = $data['id'];
                (new \App\Models\DB\OrderHistory())->completePayment($data);
                (new \App\Models\DB\Point())->setAddFromPayment($data);
            }
*/
        }
        
        return 'result=0';
    }

    // 入金連絡の再処理（処理ロジックもれ対策）

    public function reload_payment_notice() {
        if ($this->check_mente()) return show_503();

        if (empty($this->param['id'])) return 'notice ID is empty.';

        $PaymentResult = new \App\Models\DB\PaymentResult();

        $result = $PaymentResult->modPaymentRecord($this->param);

        $message = 'ID:'.$this->param['id'].' is ';

        return $result ? $message.'reloaded.' : $message.'failed.';
    }

    private function check_mente() {
        $Config = new \App\Models\Service\Config();
        return $Config->isMenteUser();
    }
}