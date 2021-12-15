<?php

namespace App\Models\Mail;

use App\Models\Service\SendMail;

class OrderNG extends SendMail
{
    private $b_admin = false; // 管理者向け通知

    private $b_customer = true; // ユーザー向け通知

    private $subject_admin = '';

    private $subject_customer = '発注不備のお知らせ';

    private $admin_template_file = '';

    private $customer_template_file = 'order_ng.txt';

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
        $param['limit_date'] = $param['payment_limit'] ?? '';

        $param['product_set_name'] = $param['product_set']['name'] ?? '';
        $param['client_name'] = $param['product_set']['client_name'] ?? '';
        $param['client_tel'] = $param['product_set']['client_tel'] ?? '';

        $param['ng_reason'] = '';

        $a = explode('　',$param['note']);
        if (count($a))
            foreach ($a as $val)
                if (mb_strpos($val, '理由：', 0, 'UTF-8') !== false
                ||  mb_strpos($val, '理由1：', 0, 'UTF-8') !== false
                ||  mb_strpos($val, '理由2：', 0, 'UTF-8') !== false
                )
                    $param['ng_reason'] = '（'.$val.'）';

        return $param;
    }

    // バッファー登録
    function buffer($param) {
        $data = $this->getMaildata($param);
        $this->addMailBuffer($data);
    }


    // 顧客メール内容取得
    function getMaildata($param) {

        if (!$this->b_customer) return [];

        $Config = new \App\Models\Service\Config();
        $CR = $this->CR;

        $mail = $Config->getMailAddress();

        $subject = '【'.$Config->getServiceName().'】'
            .$Config->getSaltSubject()
            .$this->subject_customer;

        $body = file_get_contents(__DIR__.'/'.$this->customer_template_file);

        foreach($param as $key=>$val)
            if(!in_array($key, $this->parse_ignore_keys) && !is_array($val))
                $body = str_replace('['.$key.']', $val, $body);

        $data = [
            'from'		=> $mail['admin_mail_address']
            ,'to'		=> $param['mail_address']
            ,'bcc'		=> $mail['bcc_mail_address_2user']
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