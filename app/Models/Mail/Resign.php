<?php

namespace App\Models\Mail;

use App\Models\Service\SendMail;

class Resign extends SendMail
{
    private $subject_admin = '退会アンケート';

    private $subject_customer = '退会を承りました';

    private $admin_template_file = 'resign_admin.txt';

    private $customer_template_file = 'resign_customer.txt';

    protected $key2name = [
        'resign_reason'    => '退会理由',
        'comment'          => 'その他備考'
    ];

    private $necessary_key = [
        ''
    ];

    function send($param)
    {
//        $session = session();
        //$session->set('log', json_encode($data));

        $Config = new \App\Models\Service\Config();
        $CR = $this->CR;
        $error = [];

        if ($Config->getEnviron() == 'local') return true;

        $resign_reason_list = $Config->getResignReasonList();

        $resign_reason = '';
        foreach($resign_reason_list as $key=>$val)
            if(!empty($param[$key]))
                $resign_reason .= $val.$CR;

        if (empty($resign_reason)) $resign_reason = '（未回答）';


        // 管理宛メール
        $subject = '【'.$Config->getServiceName().'】'.$this->subject_admin;

        $body =
            file_get_contents(__DIR__.'/'.$this->admin_template_file);
        $body = str_replace('[resign_reason]', $resign_reason, $body);
        $body = str_replace('[comment]', $param['comment'], $body);

        $b = $this->sendMail([
            'from'     => $param['mail_address']
            ,'to'       => $this->admin_mail_address
            ,'bcc'      => $this->bcc_mail_address_2admin
            ,'subject'  => $subject
            ,'body'     => $body
        ]);

        // 顧客宛メール
        $subject_customer = '【'.$Config->getServiceName().'】'.$this->subject_customer;
        $body =
            file_get_contents(__DIR__.'/'.$this->customer_template_file);

        $b = $this->send([
            'from'      => $this->admin_mail_address
            ,'to'       => $param['mail_address']
            ,'bcc'      => $this->bcc_mail_address_2user
            ,'subject'  => $subject
            ,'body'     => $body
        ]);
        return true;
    }
}