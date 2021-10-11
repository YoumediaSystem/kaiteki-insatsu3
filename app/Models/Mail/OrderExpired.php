<?php

namespace App\Models\Mail;

use App\Models\Service\SendMail;

class OrderExpired extends SendMail
{
    private $b_admin = false; // 管理者向け通知

    private $b_customer = true; // ユーザー向け通知

    private $subject_admin = '発注期限切れ';

    private $subject_customer = '発注期限切れのお知らせ（自動返信）';

    private $admin_template_file = '';

    private $customer_template_file = 'order_expired.txt';

    protected $key2name = [

        'contact_type'          => 'お問合せの種類'
       ,'id'                    => '発注ID'
       
       ,'mail_address'          => 'メールアドレス'
       
       ,'real_name'             => '氏名'
       ,'real_name_kana'        => '氏名カナ'
       
       ,'tel'                   => '電話番号'
       ,'tel_range'             => '連絡可能時間帯'
       
       ,'payment_date'          => '入金日'
       ,'payment_date_y'        => '入金日(年)'
       ,'payment_date_m'        => '入金日(月)'
       ,'payment_date_d'        => '入金日(日)'
       
       ,'payment_type'          => '入金方法'
       ,'payment_name'          => '入金名義人名'
       
       ,'detail'                => 'お問合せ内容'
       ];

    private $necessary_key = [
        'mail_address'
        ,'real_name'
        ,'real_name_kana'
    //	,'tel'
        ,'detail'
    ];

    private $deny_url_key = [
        'real_name'
        ,'real_name_kana'
        ,'payment_name'
        ,'tel_range'
    ];

    private $max_input_length = [
        'real_name'             => 100

        ,'real_name'            => 20
        ,'real_name_kana'       => 20
        
        ,'mail_address'         => 256
        
        ,'tel'                  => 20
        ,'tel_range'            => 100
        
        ,'payment_date_y'       => 4
        ,'payment_date_m'       => 2
        ,'payment_date_d'       => 2
        
        ,'auth_key' => 256
    ];

    private $encode_param_key = [
        'tel'
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

        $param['expired_reason'] = '';

        $a = explode('　',$param['note']);
        if (count($a))
            foreach ($a as $val)
                if (mb_strpos($val, '理由：', 0, 'UTF-8') !== false)
                    $param['expired_reason'] = '（'.$val.'）';

        return $param;
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

    // バッファー登録
    function buffer($param) {

        $param = $this->adjust($param);
        $param['mode'] = 'buffer';

        $data = $this->getMaildata($param);
        $this->addMailBuffer($data);
    }

    // メール送信
    function sendAutomail($param)
    {
        $Config = new \App\Models\Service\Config();
        $CR = $this->CR;
        $error = [];

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