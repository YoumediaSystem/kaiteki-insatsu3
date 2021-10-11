<?php

namespace App\Models\Mail;

use App\Models\Service\SendMail;

class ForgetMail extends SendMail
{
    private $subject_admin = 'メールアドレス変更希望';

    private $admin_template_file = 'forget_mail_admin.txt';

    protected $key2name = [

        'mail_address'  => 'メールアドレス'
       
       ,'sei_kana'      => '氏名'
       ,'mei_kana'      => '氏名カナ'
       ,'tel'           => '電話番号'
       ,'birthday'      => '生年月日'
    ];

    private $necessary_key = [
        ''
    ];

    function sendForgetMail($param)
    {
//        $session = session();
        //$session->set('log', json_encode($data));

        $Config = new \App\Models\Service\Config();
        $CR = $this->CR;
        $error = [];

        if ($Config->getEnviron() == 'local') return true;

        // 管理宛メール
        $subject = '【'.$Config->getServiceName().'】'.$this->subject_admin;

        $body =
            file_get_contents(__DIR__.'/'.$this->admin_template_file);

        foreach($this->key2name as $key=>$val)
            $body = str_replace("[$key]", $param[$key], $body);

        $b = $this->sendMail([
            'from'      => $param['mail_address']
            ,'to'       => $this->admin_mail_address
            ,'bcc'      => $this->bcc_mail_address_2admin
            ,'subject'  => $subject
            ,'body'     => $body
        ]);

        return true;
    }
}