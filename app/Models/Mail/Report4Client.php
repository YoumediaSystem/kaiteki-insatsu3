<?php

namespace App\Models\Mail;

use App\Models\Service\SendMail;

class Report4Client extends SendMail
{
    private $b_admin = true; // 管理者向け通知

    private $b_customer = false; // ユーザー向け通知

    private $subject_admin = '';

    private $subject_customer = '日次レポート';

    private $admin_template_file = 'report_4_client.txt';

//    private $customer_template_file = '';

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

        $param['site_url'] = $Config->getSiteURL();

        return $param;
    }

    // バッファー登録
    function buffer($param) {
        $data = $this->getMaildata($param);
        $this->addMailBuffer($data);
    }


    // 顧客メール内容取得
    function getMaildata($param) {

        $Config = new \App\Models\Service\Config();
        $CR = $this->CR;

        $mail = $Config->getMailAddress();

        $subject = '【'.$Config->getServiceName().'】'
            .$Config->getSaltSubject()
            .$this->subject_customer;

        $body = file_get_contents(__DIR__.'/'.$this->admin_template_file);

        $param = $this->adjust($param);

        foreach($param as $key=>$val)
            if(!in_array($key, $this->parse_ignore_keys) && !is_array($val))
                $body = str_replace('['.$key.']', $val, $body);

        $data = [
            'from'		=> $mail['admin_mail_address']
            ,'to'		=> $param['mail_address']
            ,'bcc'		=> $mail['bcc_mail_address_2admin']
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
        'メールを送信できませんでした。何度もこのエラーが出る場合はお問合せフォームか、'
        .$this->admin_mail_address.'までお問合せください。';

        $result = [
            'result' => !count($error) ? 'ng' : 'ok',
            'error' => $error
        ];

        return $result;
    }
}