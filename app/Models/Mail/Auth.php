<?php

namespace App\Models\Mail;

use App\Models\Service\SendMail;

class Auth extends SendMail
{
    protected $subject_admin = '';

    protected $subject_customer = '認証メール';

    protected $admin_template_file = '';

    protected $customer_template_file = 'auth_customer.txt';

    protected $key2name = [
        'url'          => 'URL'
    ];

    private $necessary_key = [
        'url'
    ];

    function sendAuth($param)
    {
        $Config = new \App\Models\Service\Config();

        if ($Config->getEnviron() == 'local') return true;

        $request = \Config\Services::request();
        $server = $request->getServer();

        $url = $Config->getProtocol();
        $url .= $server['SERVER_NAME'];
        $url .= $param['action'];
        $url .= '?hash='.$param['hash'];


        $body =
            file_get_contents(__DIR__.'/'.$this->customer_template_file);

        $subject = '【'.$Config->getServiceName().'】認証メール';

        $CR = $this->CR;

        $error = [];

        // 申請者宛メール（自動返信）
        $body = str_replace('[auth_url]', $url, $body);

        $b = $this->sendMail([
            'from'		=> $this->admin_mail_address
            ,'to'		=> $param['mail_address']
            ,'bcc'		=> $this->bcc_mail_address_2user
            
            ,'subject'	=> $subject
            ,'body'		=> $body
    //		,'file'		=> $param['make_filename']
    //		,'file_dir'	=> $file_dir
        ]);
        
        return $url;
    }
}