<?php

namespace App\Models\Service;

class Config
{
    protected $service_name = '快適印刷さん';

    protected $b_real_open = true;

    protected $b_mente_admin = false;

    protected $b_mente_user = false;


    protected $tax_per = 10;

//    protected $give_point_ratio = 0.3; // DB化？
    protected $give_point_ratio = 0.01;

    protected $not_kaiteki_point_ratio = 30;

    protected $use_point_ratio = 1;

    protected $payment_fee = 0;

    // 新規登録ポイント
    protected $signup_point = 0;
    
    protected $point_expire_days = 365;

    protected $max_userable_points = 30000;

    protected $max_give_points = 30000;



    protected $send_magazine_default_hour = 18;
    
    protected $send_magazine_per_minute = 100; // XSERVER
    
    protected $b_payment_service = true; // テスト環境の決済代行システム接続

    protected $invite_code_salt = '_comiclive';

    function __construct() {

        if (!$this->b_payment_service) {

            $this->payment_link['test']     = (string)$this->payment_link['local'];
            $this->merchant_id['test']      = (int)$this->merchant_id['local'];
        }

        if ($this->getEnviron() != 'real')
            $this->service_name = '※'.$this->service_name;
    }

    function getPointRatio() {
        return (float)$this->give_point_ratio;
    }

    function getUsePointRatio() {
        return (int)$this->use_point_ratio;
    }

    function getNotKaitekiPointRatio() {
        return (int)$this->not_kaiteki_point_ratio;
    }

    function getTaxRatio() {
        return (float)$this->tax_per * 0.01;
    }

    function getPaymentFee() {
        return (int)$this->payment_fee;
    }

    function getServiceName() {
        return (string)$this->service_name;
    }

    function getSignupPoint() {
        return (int)$this->signup_point;
    }

    function getSendMagazineHour() {
        return (int)$this->send_magazine_default_hour;
    }

    function getSendMagazinePerMinute() {
        return (int)$this->send_magazine_per_minute;
    }

    function getPointExpireDatetext() {

        $DT = new \Datetime();
        $DT->modify('+'. $this->getPointExpireDays() .' days');

        return (string)$DT->format('Y-m-d');
    }

    function getPointExpireDays() {
        return (int)$this->point_expire_days;
    }

    public function getMaxUserablePoints() {
        return (int)$this->max_userable_points;
    }

    public function getMaxGivePoints() {
        return (int)$this->max_give_points;
    }

    public function site_params()
    {
        $param = [];
        $testmark = ($this->getEnviron() != 'real') ? '※' : '';

        $param['name']          = $testmark.'快適印刷さん Online入稿';
        $param['tel']           = '03-5816-1062';
        $param['contact_time']  = '平日 11:00 ～ 18:00';
        $param['since']         = '2021';

        $param['site_name']         = $testmark.'快適印刷さん Online入稿';
        $param['site_tel']          = '03-5816-1062';
        $param['site_contact_time'] = '平日 11:00 ～ 18:00';
        $param['site_since']        = '2021';
        return $param;
    }

    private $payment_link = [
        'local' => 'https://www.youyou.co.jp/test/payment/',
        'test'  => 'https://sandbox.paygent.co.jp/v/u/request',
        'real'  => 'https://link.paygent.co.jp/v/u/request'
    ];

    private $return_url = [
        'local' => 'http://print.l/user/mypage_?payment_result=1',
        'test'  => 'https://xsvx2010092.xsrv.jp/user/mypage_?payment_result=1',
        'real'  => 'https://kaitekiinsatsu.com/user/mypage_?payment_result=1'
    ];

    private $stop_return_url = [
        'local' => 'http://print.l/payment/stop',
        'test'  => 'https://xsvx2010092.xsrv.jp/payment/stop',
        'real'  => 'https://kaitekiinsatsu.com/payment/stop'
    ];

    private $hash_key_4_payment = [
        'local' => '',
        'test'  => 'otXhcy9GMa8B',
        'real'  => '4L0gRBvuYQc0'
    ];

    private $merchant_id = [
        'local' => 99999,
        'test'  => 48741,
        'real'  => 57291
    ];

    private $fix_params_strings_4_payment = 
    'customer_family_name,customer_name,customer_family_name_kana,customer_name_kana,customer_tel';

    private $site_index_place = [
        'local' => 'C:\xampp\htdocs\print_local\public\index.php',
        'test'  => '/home/xsvx2010092/xsvx2010092.xsrv.jp/public/index.php',
        'real'  => '/home/kaitekihonya/kaitekiinsatsu.com/ci4/public/index.php'
    ];

    private $upload_place = [
        'local' => 'C:\xampp\htdocs\print_local\writable\uploads',
        'test'  => '/home/xsvx2010092/xsvx2010092.xsrv.jp/writable/uploads',
        'real'  => '/home/kaitekihonya/kaitekiinsatsu.com/ci4/writable/uploads'
    ];

    private $order_sheet_place = [
        'local' => 'C:\xampp\htdocs\print_local\public\pdf\order_sheet',
        'test'  => '/home/xsvx2010092/xsvx2010092.xsrv.jp/public/pdf/order_sheet',
        'real'  => '/home/kaitekihonya/kaitekiinsatsu.com/ci4/public/pdf/order_sheet'
    ];

    private $site_url = [
        'local' => 'http://print.l',
        'test'  => 'https://xsvx2010092.xsrv.jp',
        'real'  => 'https://kaitekiinsatsu.com'
    ];

    private $salt_subject = [ // テスト環境識別テキスト
        'local' => '(local)',
        'test'  => '(test)',
        'real'  => ''
    ];

    public function getSalt()
    {
        return 'youclub2021';
    }

    public function isRealOpen() {

        $b = ($this->getEnviron() != 'real');
        $b |= $this->b_real_open;

        return $b;
    }

    public function isMenteUser() {

        return (bool)$this->b_mente_user;
    }

    public function isMenteAdmin() {

        return (bool)$this->b_mente_admin;
    }

    public function getEnviron($url = '')
    {
        if (empty($url)) {
            $request = \Config\Services::request();
            $server = $request->getServer();

            if (!empty($server['SERVER_NAME']))
                $url = (string)$server['SERVER_NAME'];

            else
                $url = __DIR__; // XSERVER
        }

        if(strpos($url, 'print.l') !== false) return 'local';
        if(strpos($url, 'xsvx2010092.xsrv.jp') !== false) return 'test';
        if(strpos($url, 'kaitekiinsatsu.com') !== false) return 'real';

        return 'unknown';
    }

    public function getSiteURL() {
        return $this->site_url[$this->getEnviron()];
    }

    public function getPaymentLinkURL() {
        return $this->payment_link[$this->getEnviron()];
    }

    public function getReturnURL() {
        return $this->return_url[$this->getEnviron()];
    }

    public function getStopReturnURL() {
        return $this->stop_return_url[$this->getEnviron()];
    }

    public function getMerchantID() {
        return $this->merchant_id[$this->getEnviron()];
    }

    public function getHashKey4Payment() {
        return $this->hash_key_4_payment[$this->getEnviron()];
    }

    public function getFixParams4Payment() {
        return (string)$this->fix_params_strings_4_payment;
    }

    public function getSaltSubject()
    {
        return (string)$this->salt_subject[$this->getEnviron()];
    }

    public function getProtocol($url = '')
    {
        $env = $this->getEnviron($url);

        return (in_array($env, ['test','real'])) ? 'https://' : 'http://';
    }

    public function getMailAddress()
    {
        $array = [];
        $array['admin_mail_address'] = 'order@kaitekiinsatsu.com';

//        $array['bcc_mail_address'] = 'yamato@youyou.co.jp';
//        $array['bcc_mail_address_2admin'] = 'yamato@youyou.co.jp';
//        $array['bcc_mail_address_2user'] = 'yamato@youyou.co.jp';
        
        $array['bcc_mail_address'] = 'support@youmedia.net';
        $array['bcc_mail_address_2admin'] = 'support@youmedia.net';
        $array['bcc_mail_address_2user'] = 'support@youmedia.net';
        
        return $array;
/*        
        //$bcc_mail_address_2admin = 'yamato@youyou.co.jp,s_yamasaki@youmedia.net';
        
        $traffic_sampling	= 600;
        $traffic_border		= 5;
*/
    }

    public function getResignReasonList()
    {
        $array = [
            'reason_set'       => '使いたい入稿セットがない',
            'reason_payment'   => '使いたいお支払い方法がない',
            'reason_cost'      => 'セット料金が高い',
            'reason_fast'      => '入稿期限が早い',
            'reason_data'      => '原稿データが対応していない',
            'reason_website'   => 'WEBサイトが使いづらい',
            'reason_support'   => 'サポート内容に不満',
            'reason_other'     => '上記以外の理由'
        ];
        
        return $array;
    }

    public function getSiteIndexPlace() {

        return $this->site_index_place[$this->getEnviron()];
    }

    public function getClientDirectory($client_code) {

        if (empty($client_code)) return '';

        return substr(
            hash('sha256', $client_code.$this->getSalt())
            , 0, 8);
    }

    public function getClientFilePlace($client_code) {

        if (empty($client_code)) return '';

        $env = $this->getEnviron();
        $place = $this->upload_place[$env];
        $place .= '/order_sheet/';
        $place .= $this->getClientDirectory($client_code);

        if (!file_exists($place)) mkdir($place, "0705");

        return $place.'/';
    }

    public function getClientOrderSheetPlace($client_code) {

        if (empty($client_code)) return '';

        $env = $this->getEnviron();
        $place = $this->order_sheet_place[$env].'/';
        $place .= $this->getClientDirectory($client_code);

        if (!file_exists($place)) {
            mkdir($place, 0705);
            chmod($place, 0705);
        }

        return $place.'/';
    }

    public function getInviteCode($order_id, $text) {

        $hash = substr(
            hash('SHA256', $text.$this->invite_code_salt),0,8
        );

        return 'KIS'.$order_id.'-'.$hash;
    }
}
