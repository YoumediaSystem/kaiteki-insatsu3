<?php

namespace App\Models\Mail;

use App\Models\Service\SendMail;

class OrderOK extends SendMail
{
    private $b_admin = false; // 管理者向け通知

    private $b_customer = true; // ユーザー向け通知

    private $subject_admin = '';

    private $subject_customer = '';

    private $admin_template_file = '';

    private $customer_template_file = 'order_ok.txt';

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

        $this->subject_customer = '【'.$Config->getServiceName().'】'
            .$Config->getSaltSubject();

        return $param;
    }

    // バッファー登録
    function buffer($param) {

        $data = $this->getMaildata($param);
        $this->addMailBuffer($data);
    }

    function getTextByStatus($status = 150, $adjust_detail_text = '') {

        if (!in_array($status, [10,50,60,62,70,150,160,170])) return [
            'subject' => '',
            'order_step' => '',
            'order_step_next' => '',
            'order_step_notice' => ''
        ];

        $a = [];
        $CR = (string)$this->CR;

        if (in_array($status, [10])) {
            $a['subject'] = $this->subject_customer.'入稿内容調整のお知らせ';
            $a['order_step'] = '入稿内容調整';
            $a['order_step_next'] = '調整内容は以下となります。'.$CR;
            $a['order_step_next'] .= $adjust_detail_text;

            $a['order_step_notice']  = '※快適印刷さんサイトにログイン後'.$CR;
            $a['order_step_notice'] .= '　入稿詳細ページ内容をご確認の上'.$CR;
            $a['order_step_notice'] .= '　入金期限までにお支払いください。';
        }

        if (in_array($status, [50,150])) {
            $a['subject'] = $this->subject_customer.'受付開始のお知らせ';
            $a['order_step'] = 'ダウンロード確認';
            $a['order_step_next'] = 'これより、順次データチェックを行います。';

            $a['order_step_notice']  = '※原稿データに不具合が発見された場合、'.$CR;
            $a['order_step_notice'] .= '　連絡を差し上げる可能性がございます。'.$CR;
            $a['order_step_notice'] .= '　何卒ご了承ください。';
        }

        if (in_array($status, [60,160])) {
            $a['subject'] = $this->subject_customer.'表紙印刷開始のお知らせ';
            $a['order_step'] = '表紙データ精査';
            $a['order_step_next'] = 'これより、表紙の印刷を開始いたします。';

            $a['order_step_notice']  = '※これ以後、表紙は変更できません。'.$CR;
            $a['order_step_notice'] .= '　あらかじめご理解ご了承ください。';
        }

        if (in_array($status, [62,162])) {
            $a['subject'] = $this->subject_customer.'本文印刷開始のお知らせ';
            $a['order_step'] = '本文データ精査';
            $a['order_step_next'] = 'これより、本文の印刷を開始いたします。';

            $a['order_step_notice']  = '※これ以後、本文は変更できません。'.$CR;
            $a['order_step_notice'] .= '　あらかじめご理解ご了承ください。';
        }

        if (in_array($status, [70,170])) {
            $a['subject'] = $this->subject_customer.'印刷開始のお知らせ';
            $a['order_step'] = '全てのデータ精査';
            $a['order_step_next'] = 'これより、本文の印刷を開始いたします。';

            $a['order_step_notice']  = '※以後納品まで内容変更できません。'.$CR;
            $a['order_step_notice'] .= '　あらかじめご理解ご了承ください。';
        }

        return $a;
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

        $param += $this->getTextByStatus(
            $param['status']
            ,($param['adjust_detail_text'] ?? '')
        );

        foreach($param as $key=>$val)
            if(!in_array($key, $this->parse_ignore_keys) && !is_array($val))
                $body = str_replace('['.$key.']', $val, $body);

        $data = [
            'from'		=> $mail['admin_mail_address']
            ,'to'		=> $param['mail_address']
            ,'bcc'		=> $mail['bcc_mail_address_2user']
            ,'subject'	=> $param['subject']
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