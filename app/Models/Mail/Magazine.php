<?php

namespace App\Models\Mail;

use App\Models\Service\SendMail;

class Magazine extends SendMail
{
    protected $subject_admin = '';

    protected $subject_customer = '';

    protected $admin_template_file = '';

    protected $customer_template_file = 'auth_customer.txt';

    protected $key2name = [
        'url'          => 'URL'
    ];

    private $necessary_key = [
        'url'
    ];

    function adjustParam($param) {

        $d  = $param['request_date_y'].'-';
        $d .= $param['request_date_m'].'-';
        $d .= $param['request_date_d'].' ';
        $d .= $param['request_date_h'].':00:00';
        $param['request_date'] = $d;

        return $param;
    }

    function checkParam($param) {

        $error = [];

        if (empty($param['user_id']))
            $error[] = '会員IDがありません。';

        if (empty($param['template_id']))
            $error[] = 'メール雛形IDがありません。';

        $DT = new \Datetime();
        $DT_req = new \Datetime($param['request_date']);

        if ($DT_req < $DT)
            $error[] = '配信開始日時を確認してください。';

        return $error;
    }

    function replaceText($param) {

        if (empty($param['replace_text'])
        ||  empty($param['replace_keys'])
        ) return $param['replace_text'] ?? '';

        $text = $param['replace_text'];
        foreach($param['replace_keys'] as $key)
            $text = str_replace("[$key]", $param[$key] ?? '', $text);

        return $text;
    }

    function addBuffer($param) {

    }

    function sendBuffer($param)
    {
        $Config = new \App\Models\Service\Config();

        if ($Config->getEnviron() == 'local') return true;

        $request = \Config\Services::request();
        $server = $request->getServer();

        $subject = '【'.$Config->getServiceName().'】'.$param['subject'];

        return $this->sendMail([
            'from'		=> $param['from']
            ,'to'		=> $param['to']
            ,'bcc'		=> $param['bcc']
            
            ,'subject'	=> $subject
            ,'body'		=> $param['body']
        ]);
    }
}