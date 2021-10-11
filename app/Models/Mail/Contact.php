<?php

namespace App\Models\Mail;

use App\Models\Service\SendMail;

class Contact extends SendMail
{
    private $subject_admin = 'お問合せ';

    private $subject_customer = 'お問合せを受付しました（自動返信）';

    private $admin_template_file = 'contact_admin.txt';

    private $customer_template_file = 'contact_customer.txt';

    protected $key2name = [

        'contact_type'          => 'お問合せの種類'

       ,'user_id'               => '会員no.'
       ,'order_id'              => '発注no.'
       
       ,'mail_address'          => 'メールアドレス'
       
       ,'real_name'             => '氏名'
       ,'real_name_kana'        => '氏名カナ'
       
       ,'trans_point_type'      => 'ポイント移動内容'
       ,'points'                => '移動ポイント数'
       ,'url'                   => '原稿URL'

       ,'detail'                => 'お問合せ内容'
       ];

    private $necessary_key = [
        'contact_type'
        ,'mail_address'
        ,'real_name'
        ,'real_name_kana'
    ];

    private $deny_url_key = [
        'real_name'
        ,'real_name_kana'
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
        
        ,'url'                  => 2048

        ,'auth_key' => 256
    ];

    private $encode_param_key = [
        'tel'
    ];

    private $parse_ignore_keys = [
        'submit'
        ,'error'
    ];

    private $preview = [

        'contact_type'
       ,'user_id'
       ,'order_id'
       ,'mail_address'
       ,'real_name'
       ,'real_name_kana'
       ,'trans_point_type'
       ,'points'
       ,'url'
       ,'detail'
    ];
    
    private $not_preview = [
        'dummy'
    ];

    private $max_trans_points = 100000;

    function getKey2NameArray() {
        return (array)$this->key2name;
    }

    function getPreviewArray() {
        return (array)$this->preview;
    }

    function getNotPreviewArray() {
        return [];
    }

    // 入力値調整
    function adjustContact($param)
    {   
//        $lib = new \App\Models\CommonLibrary();

        foreach($this->encode_param_key as $key) {

            if (!empty($param[$key]))
                $param[$key] = mb_convert_kana($param[$key], 'KVa');
        }

        return $param;
    }

    function checkContact($param)
    {
        $lib = new \App\Models\CommonLibrary();

        $error = [];

        foreach($this->necessary_key as $key) { // 必須入力チェック
            if(empty($param[$key]))
                $error[] = $this->key2name[$key].'を入力してください。';
        }
        
        foreach($this->max_input_length as $key=>$val) { // 文字数上限チェック
            if(!empty($param[$key]) && $val < mb_strlen($param[$key],'UTF-8'))
                $error[] = $this->key2name[$key].'は'.$val.'文字以内で入力してください。';
        }
        
        foreach($this->deny_url_key as $key) { // URL混入チェック
            if(!empty($param[$key]) && $lib->is_url($param[$key]))
                $error[] = $this->key2name[$key].'に送信できない文字が含まれています。';
        }
    
        // メールアドレス
        $key = 'mail_address'; // 連絡先
        if (!empty($param[$key]) && !$lib->is_mail($param[$key]))
            $error[] = $this->key2name[$key].'を確認してください。';

        // URL
        $key = 'url';
        if (!empty($param[$key]) && !$lib->is_url($param[$key]))
            $error[] = $this->key2name[$key].'を確認してください。';

        // ポイント申請 or その他
        if (!empty($param['contact_type'])
        && $param['contact_type'] == 'ポイント移動申請') {

            if (empty($param['trans_point_type']))
                $error[] = 'ポイント移動内容を指定してください。';

            $p = (int)$param['points'] ?? 0;

            if ($p <= 0 || $this->max_trans_points < $p)
                $error[] = '移動ポイント数が異常です。確認してください。';

        } else {
            if (empty($param['detail']))
                $error[] = 'お問合せ内容を入力してください。';
        }

        return $error;
    }

    function adjustSendBefore($param) {

        $CR = $this->CR;

        if (!empty($param['trans_point_type'])
        &&  !empty($param['points'])
        )   $param['point_detail'] = 
                $param['trans_point_type'].' '
                .$param['points'].'ポイント移動希望';
        else
            $param['point_detail'] = '';

        if (!empty($param['url']))
            $param['url_detail'] = '■原稿URL'.$CR
            .$param['url'].$CR.$CR;
        else
            $param['url_detail'] = '';

        return $param;
    }

    function sendContact($param)
    {
        $Config = new \App\Models\Service\Config();
        $CR = $this->CR;
        $error = [];

        if ($Config->getEnviron() == 'local') return true;

        $param = $this->adjustSendBefore($param);

        // 管理者宛メール
        $subject = '【'.$Config->getServiceName().'】'.$param['contact_type'];

        $body = file_get_contents(__DIR__.'/'.$this->admin_template_file);

        foreach($param as $key=>$val)
            if(!in_array($key, $this->parse_ignore_keys) && !is_array($val))
                $body = str_replace('['.$key.']', $val, $body);

        $body .= $this->getReport4Admin();

        $mail = $Config->getMailAddress();

        // 入稿IDがある場合は、クライアントアドレスを追加する
        if (!empty($param['order_id'])) {
            $order = (new \App\Models\DB\OrderHistory())
                ->getDetailFromID($param['order_id']);

            if (!empty($order['product_set']['client_mail']))
            $mail['bcc_mail_address_2admin'].','
            .$order['product_set']['client_mail'];
        }

        $b = $this->sendMail([
            'from'		=> $param['mail_address']
            ,'to'		=> $mail['admin_mail_address']
            ,'bcc'		=> $mail['bcc_mail_address_2admin']
            ,'subject'	=> $subject
            ,'body'		=> $body
    //		,'file'		=> $param['make_filename']
    //		,'file_dir'	=> $file_dir
        ]);
        
        if (!$b) $error[] =
        'フォーム送信できませんでした。何度もこのエラーが出る場合はお問合せフォームか、'
        .$this->admin_mail_address.'までお問合せください。';


        // 申請者宛メール（自動返信）

        $subject = '【'.$Config->getServiceName().'】お問合せを受付しました（自動返信）';

        $body = file_get_contents(__DIR__.'/'.$this->customer_template_file);

        foreach($param as $key=>$val)
            if(!in_array($key, $this->parse_ignore_keys) && !is_array($val))
                $body = str_replace('['.$key.']', $val, $body);

        $b = $this->sendMail([
            'from'		=> $mail['admin_mail_address']
            ,'to'		=> $param['mail_address']
            ,'bcc'		=> $mail['bcc_mail_address_2user']
            ,'subject'	=> $subject
            ,'body'		=> $body
    //		,'file'		=> $param['make_filename']
    //		,'file_dir'	=> $file_dir
        ]);
        
        if (!$b) $error[] =
        '自動返信メールを送信できませんでした。何度もこのエラーが出る場合はお問合せフォームか、'
        .$mail['admin_mail_address'].'までお問合せください。';

        $result_param = [
            'result' => !count($error) ? 'ng' : 'ok',
            'error' => $error
        ];

        return $result_param;
    }
}