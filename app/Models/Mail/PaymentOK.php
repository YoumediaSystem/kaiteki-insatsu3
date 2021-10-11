<?php

namespace App\Models\Mail;

use App\Models\Service\SendMail;

class PaymentOK extends SendMail
{
    private $b_admin = true; // 管理者向け通知する

    private $b_customer = true; // ユーザー向け通知する

    private $subject_admin = '入金のお知らせ';

    private $subject_customer = '入金確認のお知らせ';

    private $admin_template_file = 'payment_ok_admin.txt';

    private $customer_template_file = 'payment_ok_customer.txt';

    private $parse_ignore_keys = [
        'submit'
        ,'error'
    ];

    // 入力値調整
    function adjust($param)
    {   
        $lib = new \App\Models\CommonLibrary();
        $Config = new \App\Models\Service\Config();

        $param['mail_address'] = $param['userdata']['mail_address'] ?? '';
        $param['real_name'] = $param['userdata']['name'] ?? '';

        $param['order_id'] = $param['id'];
        $param['product_set_name'] = $param['product_set']['name'] ?? '';
        $param['client_name'] = $param['product_set']['client_name'] ?? '';
        $param['admin_url'] = $Config->getSiteURL()
            .'/admin/order_detail?id='.$param['order_id'];

//        $param['print_title'] = $param['print_title'];
//        $param['print_number_all'] = $param['print_number_all'];
//        $param['price'] = $param['price'];

        $data = (new \App\Models\DB\Payment())->getFromID($param['payment_id']);

        $param['payment_date'] = $data['update_date'] ?? '';
        $param['price_total'] = $data['amount'] ?? 0;

        return $param;
    }

    // バッファー登録
    function buffer($param) {

        $param = $this->adjust($param);
        $param['mode'] = 'buffer';

        if ($this->b_admin) {
            $data = $this->getAdminMaildata($param);
            $this->addMailBuffer($data);
        }
        if ($this->b_customer) {
            $data = $this->getMaildata($param);
            $this->addMailBuffer($data);
        }
    }

    // 管理メール内容取得
    function getAdminMaildata($param) {

        if (!$this->b_admin) return [];

        $Config = new \App\Models\Service\Config();
        $CR = $this->CR;

        $subject = '【'.$Config->getServiceName().'】'
            .$Config->getSaltSubject()
            .$this->subject_admin;

        $body = file_get_contents(__DIR__.'/'.$this->admin_template_file);

        foreach($param as $key=>$val)
            if(!in_array($key, $this->parse_ignore_keys) && !is_array($val))
                $body = str_replace('['.$key.']', $val, $body);

        if (!$param['b_background'])
            $body .= $this->getReport4Admin();

        $data = [
            'from'		=> $param['mail_address']
            ,'to'		=> $this->admin_mail_address
            ,'bcc'		=> $this->bcc_mail_address_2admin
            ,'subject'	=> $subject
            ,'body'		=> $body
        ];
        return $data;
    }

    // 顧客メール内容取得
    function getMaildata($param) {

        if (!$this->b_customer) return [];

        $Config = new \App\Models\Service\Config();
        $CR = $this->CR;

        $subject = '【'.$Config->getServiceName().'】'
            .$Config->getSaltSubject()
            .$this->subject_customer;

        $body = file_get_contents(__DIR__.'/'.$this->customer_template_file);

        foreach($param as $key=>$val)
            if(!in_array($key, $this->parse_ignore_keys) && !is_array($val))
                $body = str_replace('['.$key.']', $val, $body);

        $data = [
            'from'		=> $this->admin_mail_address
            ,'to'		=> $param['mail_address']
            ,'bcc'		=> $this->bcc_mail_address_2user
            ,'subject'	=> $subject
            ,'body'		=> $body
        ];
        return $data;
    }

    // メール送信
    function sendAutomail($param)
    {
        $Config = new \App\Models\Service\Config();
        $CR = $this->CR;
        $error = [];

        if ($Config->getEnviron() == 'local') return true;

        // 管理者宛メール
        $data = $this->getAdminMaildata($param);
        $b = $this->sendMail($data);
        
        if (!$b) $error[] =
        'メール送信できませんでした。何度もこのエラーが出る場合はお問合せフォームか、'
        .$this->admin_mail_address.'までお問合せください。';


        // 申請者宛メール（自動返信）
        $data = $this->getMaildata($param);
        $b = $this->sendMail($data);

        if (!$b) $error[] =
        '自動返信メールを送信できませんでした。何度もこのエラーが出る場合はお問合せフォームか、'
        .$this->admin_mail_address.'までお問合せください。';

        $result = [
            'result' => !count($error) ? 'ng' : 'ok',
            'error' => $error
        ];

        return $result;
    }
}