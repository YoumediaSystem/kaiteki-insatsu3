<?php

namespace App\Models\Mail;

use App\Models\Service\SendMail;

class OrderExtra extends SendMail
{
    private $b_admin = true; // 管理者向け通知

    private $b_customer = false; // ユーザー向け通知

    private $subject_admin = '';

    private $subject_customer = '';

    private $admin_template_file = 'order_extra_admin.txt';

    private $customer_template_file = '';

    protected $key2name = [
       ];

    private $necessary_key = [
    ];

    private $deny_url_key = [
    ];

    private $max_input_length = [
    ];

    private $encode_param_key = [
    ];

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

        $this->subject_customer = '【'.$Config->getServiceName().'】'
            .$Config->getSaltSubject();

        return $param;
    }

    // バッファー登録
    function buffer($param) {

        $data = $this->getMaildata($param);
        $this->addMailBuffer($data);
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
/*
        if (!$param['b_background'])
            $body .= $this->getReport4Admin();
*/
        $data = [
            'from'		=> $param['mail_address']
            ,'to'		=> $this->admin_mail_address
            ,'bcc'		=> $this->bcc_mail_address_2admin
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

        $result = [
            'result' => !count($error) ? 'ng' : 'ok',
            'error' => $error
        ];

        return $result;
    }
}