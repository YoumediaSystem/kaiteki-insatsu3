<?php

namespace App\Models\DB;

use CodeIgniter\Model;
//use CodeIgniter\Database\ConnectionInterface;

class OrderHistory extends Model
{
    protected $statusName = [
         10 => '未入金'
        ,11 => '入金待ち'
        ,40 => '入金済'
        ,50 => '不備あり'
        ,60 => '印刷開始済'
        ,90 => '期限切れ'

        ,110 => '未入金（通知未発送）'
        ,140 => '入金済（通知未発送）'
        ,150 => '不備あり（通知未発送）'
        ,160 => '印刷開始済（通知未発送）'
        ,190 => '期限切れ（通知未発送）'

        ,-1 => '削除'
    ];

    protected $table      = 'order_history';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'print_up_date',
        'print_title',
        'price',
        'user_id',
        'product_set_id',
        'payment_id',
        'payment_limit',
        'point_ids',
        'note',
        'admin_id',
        'status',
        'protect',
        'ex'
    ];

    protected $protectedFields = [
        'user_name',
        'user_name_kana',
        'birthday',
        'sextype',
        'zipcode',
        'address1',
        'address2',
        'tel',
        'tel_range',
    ];

    protected $exFields = [
        'print_data_url',
        'print_number_all',
        'print_size',
        'print_page',
        'print_direction',
        'cover_paper',
        'cover_color',
        'cover_process',
        'main_paper',
        'main_color',
        'main_process',
        'main_buffer_paper',
        'main_buffer_paper_detail',
        'binding',
        'r18',
        'number_home',
        'number_kaiteki',
        'delivery_divide',
        'price_text',
        'b_overprint_kaiteki'
    ];

    protected $dateFields = [
        'print_up_date'
        ,'event_1_date'
        ,'event_2_date'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    protected $key2name;

    protected $necessary = [
        'print_up_date'
        ,'print_data_url'
        ,'print_title'
        ,'print_number_all'
        ,'print_page'
    ];

    protected $necessary_event = [
        '_date'
        ,'_name'
        ,'_place'
        ,'_circle_name'
        ,'_circle_name_kana'
    ];

    protected $necessary_other = [
        'other_zipcode'
       ,'other_real_address_1'
       ,'other_real_name'
       ,'other_real_name_kana'
       ,'other_tel'
    ];

    protected $deny_url_key = [
        'other_real_name'
        ,'other_real_name_kana'
        ,'other_real_address_1'
        ,'other_real_address_2'
        ,'print_title'
    ];

    protected $max_input_length = [

        'real_name'				=> 100

        ,'real_name'			=> 20
        ,'real_name_kana'		=> 20
        
        ,'mail_address'			=> 256
        
        ,'zipcode'				=> 8
        ,'real_address_1'		=> 200
        ,'real_address_2'		=> 200
        ,'tel'					=> 20
        ,'tel_range'			=> 100
        
        ,'print_up_date_y'	=> 4
        ,'print_up_date_m'	=> 2
        ,'print_up_date_d'	=> 2
        
        ,'birthday_y'		=> 4
        ,'birthday_m'		=> 2
        ,'birthday_d'		=> 2
        
        ,'print_data_url'	=> 2048
        ,'print_title'		=> 100
        ,'print_number_all'	=> 5
        ,'print_page'		=> 5
        ,'main_buffer_paper_detail'		=> 100
        
        ,'auth_key' => 256
    ];

    protected $encode_param = [
        'zipcode'
       ,'tel'
       ,'other_zipcode'
       ,'other_tel'
       ,'real_name_kana'
       ,'other_real_name_kana'
       ,'event_1_circle_name_kana'
       ,'event_2_circle_name_kana'
    ];
    
    protected $encode_param_kana = [
        'real_name_kana'
        ,'other_real_name_kana'
        ,'event_1_circle_name_kana'
        ,'event_2_circle_name_kana'
    ];
       

    private $salt = 'youclub1211';

    protected $rows_of_page = 20;

    function getStatusNameArray() {
        return (array)$this->statusName;
    }

/*
    function __construct() {

        $OrderForm = new \App\Models\Service\OrderForm();

        $this->key2name = (new \App\Models\Service\OrderForm())->getKey2Names();
    }
*/
    public function getKey2Name() {
        return (array)$this->key2name;
    }

    public function getStatusName() {
        return (array)$this->statusName;
    }
/*
    public function getFromID($id) {

        if (empty($id)) return [];

        $data = $this->find($id);

        if (empty($data['id'])) return [];

        $data['mode'] = 'detail';

        return $this->parseData($data);
    }
*/
    public function getFromID($id, $mode = 'normal') {

        if (empty($id)) return [];

        $org = $this->find($id);
        if (empty($org['id'])) return [];

        if ($mode == 'detail') $org['mode'] = 'detail';

        $org = $this->parseData($org);

        $ModOrder = new \App\Models\DB\ModOrderHistory();

        $mod = $ModOrder->getFromOrderID($id);

        if(!empty($mod['id'])) {

            foreach($this->exFields as $key) {
                $b1 = isset($org[$key]);
                $b2 = isset($mod[$key]);
                if (
                    (!$b1 && $b2)
                ||  ($b1 && $b2 && $org[$key] != $mod[$key])
                )   $org[$key] = $mod[$key];
            }

            $key = 'price';
            if($org[$key] != $mod[$key]) {
                $org['org_price'] = $org['price'];
                $org[$key] = $mod[$key];
            }

            $org['mod_id'] = $mod['id'];
        }

        return $org;
    }

    public function getDetailFromID($id) {

        return $this->getFromID($id, 'detail');
    }

    public function getFromPaymentID($id) {

        if (empty($id)) return [];

        $record = $this->where('payment_id', $id)->findAll();

        if (empty($record[0]['id'])) return [];

        $result = [];
        foreach($record as $data) {

            $temp = $data;
            $temp['mode'] = 'detail';
            $result[] = $this->parseData($temp);
        }
        return $result;
    }

    public function getList4user($user_id) {

        if (empty($user_id)) return [];

        $data = $this
        ->where('user_id', $user_id)
        ->where('status >=', 10)
        ->orderBy('id','DESC')
        ->findAll();

        if (empty($data) || !count($data)) return [];

        $result = [];
        foreach($data as $order)
            $result[] = $this->parseData($order);

        return $result;
    }

    public function getLatest4user($user_id) {

        if (empty($user_id)) return [];

        $data = $this
        ->where('user_id', $user_id)
        ->where('status >=', 10)
        ->orderBy('id', 'DESC')
        ->findAll();

        if (empty($data) || !count($data)) return [];

        $result = [];
            $result[] = $this->parseData($data[0]);

        return $result;
    }

    public function isDoing($user_id) {

        if (empty($user_id)) return false;

        $DT = new \Datetime();

        $data = $this
        ->where('user_id', $user_id)
        ->whereIn('order_history.status', [10,11,40,50,110,140,150])
        ->findAll();

        return (!empty($data) && count($data));
    }

    public function isYetPayment($user_id) {

        if (empty($user_id)) return false;

        $DT = new \Datetime();

        $data = $this
        ->where('user_id', $user_id)
        ->where('order_history.status', 10)
        ->where('order_history.payment_limit >', $DT->format('Y-m-d H:i:s'))
        ->findAll();

        return (!empty($data) && count($data));
    }

    public function getYetPayment($user_id) {

        if (empty($user_id)) return [];

        $DT = new \Datetime();

        $data = $this
        ->select('order_history.*, product_set.client_code, client.name as client_name')
        ->join('product_set',
            'order_history.product_set_id = product_set.id', 'left')
        ->join('client',
            'product_set.client_code = client.code', 'left')
        ->where('user_id', $user_id)
        ->where('order_history.status', 10)
        ->where('order_history.payment_limit >', $DT->format('Y-m-d H:i:s'))
        ->orderBy('product_set.client_code')
        ->findAll();

        if (empty($data) || !count($data)) return [];

        $result = [];
        foreach($data as $order)
            $result[] = $this->parseData($order);

        return $result;
    }

    public function getPaymentOrder($order_id_array) {

        if (empty($order_id_array) || !count($order_id_array)) return [];

        $data = $this
        ->select('order_history.*, product_set.client_code, client.name as client_name')
        ->join('product_set',
            'order_history.product_set_id = product_set.id', 'left')
        ->join('client',
            'product_set.client_code = client.code', 'left')
        ->whereIn('order_history.id', $order_id_array)
        ->findAll();

        if (empty($data) || !count($data)) return [];

        $result = [];
        foreach($data as $order)
            $result[] = $this->parseData($order);

        return $result;
    }

    // 会員管理ページ用情報取得（管理サイト用）

    function getPagerInfo($param) {

        $pager = [
            'page'      => $param['page'],
            'count_all' => $param['count_all'],
            'max_page'  => intval($param['count_all'] / $this->rows_of_page),
            'offset'    => $this->rows_of_page * $param['page']
        ];

        return $pager;
    }

    // 受注リスト取得（管理サイト用）

    function getList($param) {

        $b_all = (empty($param['user_id'])
//        &&  empty($param['tel'])
//        &&  empty($param['sei_kana'])
//        &&  empty($param['mei_kana'])
        &&  empty($param['product_set_id'])
        &&  empty($param['status'])
        );
        
        if (empty($param['page'])) $param['page'] = 0;

        if ($b_all) {
            $count = $this
            ->countAllResults(false);

        } else {
            $count = $this
            ->like('user_id', $param['user_id'] ?? '*')
            ->like('product_set_id', $param['product_set_id'] ?? '*')
            ->like('status', $param['status'] ?? '*')
            ->countAllResults(false);
        }

        $data = $this
        ->orderBy('id','DESC')
        ->findAll($this->rows_of_page,
            $this->rows_of_page * $param['page']);

        $result = [];

        foreach($data as $key=>$val) {
            $temp = $val;
            $temp['mode'] = $param['mode'];
            $result[] = $this->parseData($temp);
        }

        $result['count_all'] = $count;

        return $result;
    }

    public function getSelector($matrix = null) { // 仮設定　どこに情報格納し編集するかは今後策定

        if (empty($matrix)) {
            $matrix = [
                'index_h' => ['10冊','20冊','30冊'],
                'index_v' => ['8p','12p','16p'],
            ];
        } // debug

        $select['print_number_all'] = [];

        foreach ($matrix['index_h'] as $key=>$val)
            $select['print_number_all'][] = $val;
        /*
        = [
            30,50,80,100,150];
            
        for ($i=200; $i<=$max_print_number; $i+=100) $select['print_number_all'][] = $i;
        */
        
        
        $select['print_page'] = [];
        
        foreach ($matrix['index_v'] as $key=>$val)
            $select['print_page'][] = $val;
        /*
        for ($i=$min_print_page;
            $i<=$max_print_page; $i+=4) $select['print_page'][] = $i;
        */
        
        $select['print_size'] = [
            'A6','B6','A5','B5'];
        
        $select['sex_type'] = [
            '男','女','未回答'];
        
        $select['print_direction'] = [
            '右綴じ','左綴じ'];
        
        $select['cover_paper'] = [
            'アートポスト180kg'];
        
        $select['cover_color'] = [
            'オフセット印刷フルカラー（表2・3印刷無し）'];
        
        $select['cover_process'] = [
            'クリアPP','マットPP'];
        
        $select['main_paper'] = [
             '上質70kg'
            ,'上質90kg'
            ,'スーパーバルギー'
            ,'書籍バルギー'
            //,'書籍バルギー'
            //,'上質110kg','スーパーバルギー'
        ];
        
        $select['main_color'] = [
            'オフセット印刷スミ1色'];
        
        $select['main_print_type'] = [
            'AM120線','FM'];
        
        $select['main_buffer_paper'] = [
            'なし'];
        //	'なし','前のみ','前後'];
        
        $select['main_buffer_paper_detail'] = [
        /*	
             '色上質・やまぶき'
            ,'色上質・レモン'
            ,'色上質・濃クリーム'
            ,'色上質・白茶'
            ,'色上質・オレンジ'
        
            ,'色上質・サーモン'
            ,'色上質・空'
            ,'色上質・ブルー'
            ,'色上質・水'
            ,'色上質・あじさい'
        
            ,'色上質・りんどう'
            ,'色上質・銀ねず'
            ,'色上質・コスモス'
            ,'色上質・さくら'
            ,'色上質・桃'
        
            ,'色上質・若草'
            ,'色上質・もえぎ'
            ,'色上質・若竹'
            ,'色上質・赤'
            ,'色上質・黒'
        
            ,'クラシコトレーシング・ピンク'
            ,'クラシコトレーシング・イエロー'
            ,'クラシコトレーシング・ブルー'
            ,'クラシコトレーシング・グレー'
            ,'クラシコトレーシング・オレンジ'
        
            ,'クラシコトレーシング・グリーン'
            ,'クラシコトレーシング・バイオレット'
        */
        ];
        
        $select['binding'] = [
            '無線綴じ'];
        
        $select['r18'] = [
            '全年齢向け','成人向け表現あり'];
        
        $select['delivery_buffer'] = [
            '自宅','直接搬入1','直接搬入2','快適本屋さん','その他'];
        
        $select['_type'] = [
            '自宅','直接搬入','快適本屋さんOnline','その他'];

        return $select;
    }

    function adjustParam($param) {

        $lib = new \App\Models\CommonLibrary();

        $param['print_up_date']	= $lib->join_date($param, 'print_up_date');
        $param['event_1_date']	= $lib->join_date($param, 'event_1_date');
        $param['event_2_date']	= $lib->join_date($param, 'event_2_date');
        
        $param['other_zipcode']	= preg_replace('/-|－|ー|―/u', '', $param['other_zipcode']);
        
        $param['other_tel']		= preg_replace('/-|－|ー|―/u', '-', $param['other_tel']);

        foreach($this->encode_param as $key)
            if (!empty($param[$key]))
                $param[$key] = mb_convert_kana($param[$key], 'KVCa');
        
        foreach($this->encode_param_kana as $key)
            if (!empty($param[$key]))
                $param[$key] = preg_replace('/[^　ァ-ヶー]/u', '', $param[$key]);

        $count = 0;
        $count += $param['number_home'] ? 1 : 0;
        $count += $param['number_event_1'] ? 1 : 0;
        $count += $param['number_event_2'] ? 1 : 0;
        $count += $param['number_kaiteki'] ? 1 : 0;
        $count += $param['number_other'] ? 1 : 0;
        $param['delivery_divide'] = $count;
        
        if (!empty($param['price_text']))
            $param['price_text'] = $lib->unicode_unescape($param['price_text']);

        return $param;
    }

    function isEarlyLimit() {

    }

    function checkParam($param) {

        $this->key2name = (new \App\Models\Service\OrderForm())->getKey2Names();

        $lib = new \App\Models\CommonLibrary();
        $Limit = new \App\Models\Service\LimitDate();


        $error = [];

        foreach($this->necessary as $key) { // 必須入力チェック
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

        $DT_now = new \Datetime();
        
//        $DT_border = new \Datetime('next wednesday');
        $DT_border = $Limit->getPaymentLimitDate('object');
        
//        $DT_up_border = new \Datetime('next wednesday');
//        $DT_up_border->modify('+3days'); // saturday
        $DT_up_border = $Limit->getBorderEventDate('object');
    
//        $DT_border_later = new \Datetime('first day of');
//        $DT_border_later->modify('+3 month');
//        $DT_border_later->modify('last day of');
        $DT_border_later = $Limit->getBorderLaterDate('object');
    
        $DT_up = new \Datetime($param['print_up_date']);

        // 早期締切リスト　DB参照
        $early_limit_event = [];
        $temp = (new \App\Models\DB\LimitDateList())
        ->getList4OrderForm([
            'client_code'   => $param['client_code'],
            'date_from'     => $param['border_event_date'],
            'date_to'       => $param['border_later_date']
        ]);
        if (count($temp))
            foreach ($temp as $key=>$row)
                $early_limit_event[$row['print_up_date']] = $row;

        // 早期締切対象
        $print_up_date_ymd = $DT_up->format('Y-m-d');
        if (array_key_exists($print_up_date_ymd, $early_limit_event)) {

            $DT_limit = new \Datetime(
                $early_limit_event[$print_up_date_ymd]['limit_date']);

            if ($DT_limit < $DT_up_border)
                $DT_up_border->modify($DT_limit->format('Y-m-d H:i:s'));

            if ($DT_limit < $DT_now)
                $error[] = '指定した納品日は入稿期限を過ぎているため受付できません。';
        }

        $b_border = true;
/*
        // 入稿期限オーバー
        if ($DT_border < $DT_now)
            $error[] = '指定した納品日は入稿期限を過ぎているため受付できません。';
*/
        // 納品希望日オーバー
        if ($DT_border_later < $DT_up) {
            $t = '納品日は '.$DT_border_later->format('Y/n/j').' 以前の日付を指定してください。';
            $error[] = $t;
            $b_border = false;
        }
    
        // 冊数
        if ($param['print_number_all'] == '0冊')
            $error[] = '冊数を選択してください。';
    
        // ページ数
        if ($param['print_page'] == '0p')
            $error[] = 'ページ数を選択してください。';

        // 誕生日チェック
        if (mb_strpos($param['r18'],'成人向',0,'UTF-8') !== false) {
    
            if (
                !(new \App\Models\DB\User())->isOver18($param['user']['id'])
            )
                $error[] = '18歳未満は成人向け作品発注できません。';
        }
    
        // メールアドレス
        $key = 'mail_address'; // 連絡先
        if (!empty($param[$key]) && !$lib->is_mail($param[$key]))
            $error[] = $this->key2name[$key].'を確認してください。';
    
        // 表紙の紙と加工の組み合わせ
        if (mb_strpos($param['name_en'], 'ondemand', 0, 'UTF-8') !== false) {
    
            $b_process = (mb_strpos($param['cover_paper'], 'ポスト', 0,'UTF-8') !== false);
    
            if ($b_process && $param['cover_process'] == 'なし')
                $error[] = 'ご選択された表紙では、表紙・基本加工はクリアPP・マットPPいずれかをご選択ください。';
            
            if (!$b_process && $param['cover_process'] != 'なし')
                $error[] = 'ご選択された表紙ではクリアPP・マットPPは選択できません。表紙・基本加工「なし」をご選択ください。';
            }
    
        // 発行部数と納品部数
        $number_ratio = $param['number_ratio'] ?? 1;

        $n = 0;
        $n += $param['number_home'];
        $n += $param['number_event_1'];
        $n += $param['number_event_2'];
        $n += $param['number_kaiteki'];
        $n += $param['number_other'];
        if ((int)$param['print_number_all'] * $number_ratio != $n)
            $error[] = '発行部数と納品部数の合計が異なります。確認してください。';
    
        // 分納先の数
        $n = 0;
        $n += $param['number_home'] ? 1 : 0;
        $n += $param['number_event_1'] ? 1 : 0;
        $n += $param['number_event_2'] ? 1 : 0;
        $n += $param['number_kaiteki'] ? 1 : 0;
        $n += $param['number_other'] ? 1 : 0;
/*        
        if (4 < $n)
            $error[] = '分納は最大4箇所までとなります。納品先部数を確認してください。';
*/
        // 余部納品先チェック
        $param['delivery_buffer'] = $param['delivery_buffer'] ?? '';
        if (!$param['number_home'] && $param['delivery_buffer'] == '自宅')
            $error[] = '余部納品先（'.$param['delivery_buffer'].'）の納品部数を確認してください。';
    
        if (!$param['number_event_1'] && $param['delivery_buffer'] == '直接搬入1')
            $error[] = '余部納品先（'.$param['delivery_buffer'].'）の納品部数を確認してください。';
    
        if (!$param['number_event_2'] && $param['delivery_buffer'] == '直接搬入2')
            $error[] = '余部納品先（'.$param['delivery_buffer'].'）の納品部数を確認してください。';
    
        if (!$param['number_kaiteki'] && $param['delivery_buffer'] == '快適本屋さん')
            $error[] = '余部納品先（'.$param['delivery_buffer'].'）の納品部数を確認してください。';
    
        if (!$param['number_other'] && $param['delivery_buffer'] == 'その他')
            $error[] = '余部納品先（'.$param['delivery_buffer'].'）の納品部数を確認してください。';
    
        // 直接搬入1 必須項目チェック
        if (!empty($param['number_event_1'])) {
    
            foreach($this->necessary_event as $key) { // 必須入力チェック
                if(empty($param['event_1'.$key]))
                    $error[] = $this->key2name['event_1'.$key].'を入力してください。';
            }
            // 納品日以降チェック
            $DT_event = new \Datetime($param['event_1_date']);
    
            if ($DT_border_later < $DT_event) {
                $t = '直接搬入1のイベント開催日は '.$DT_border_later->format('Y/n/j').' 以前に限ります。';
                $error[] = $t;
            }
    
            if ($DT_event < $DT_up_border) {
                $t = '直接搬入1のイベント開催日は '.$DT_up_border->format('Y/n/j').' 以降に限ります。';
                $error[] = $t;
            }
/*
            // 早期締切対象
            if (in_array($param['event_1_date'], $early_limit_event)) {
    
                $DT_limit = new \Datetime($early_limit_date[$param['event_1_date']]);
    
                if ($DT_limit < $DT_up_border)
                    $DT_up_border->modify($DT_limit->format('Y/m/d H:i:s'));
            }
*/
        }
    
        // 直接搬入2 必須項目チェック
        if (!empty($param['number_event_2'])) {
    
            foreach($this->necessary_event as $key) { // 必須入力チェック
                if(empty($param['event_2'.$key]))
                    $error[] = $this->key2name['event_2'.$key].'を入力してください。';
            }
            // 納品日以降チェック
            $DT_event = new \Datetime($param['event_2_date']);
    
            if ($DT_border_later < $DT_event) {
                $t = '直接搬入2のイベント開催日は '.$DT_border_later->format('Y/n/j').' 以前に限ります。';
                $error[] = $t;
            }
    
            if ($DT_event < $DT_up_border) {
                $t = '直接搬入2のイベント開催日は '.$DT_up_border->format('Y/n/j').' 以降に限ります。';
                $error[] = $t;
            }
/*
            // 早期締切対象
            if (in_array($param['event_2_date'], $early_limit_event)) {
    
                $DT_limit = new \Datetime($early_limit_date[$param['event_2_date']]);
    
                if ($DT_limit < $DT_up_border)
                    $DT_up_border->modify($DT_limit->format('Y/m/d H:i:s'));
            }
*/
        }
    
        // その他発送先 必須項目チェック
        if (!empty($param['number_other'])) {
    
            foreach($this->necessary_other as $key) { // 必須入力チェック
                if(empty($param[$key]))
                    $error[] = $this->key2name[$key].'を入力してください。';
            }
        }
    
        // 納品締切
        if ($DT_up < $DT_up_border) {
            $t = '納品日は '.$DT_up_border->format('Y/n/j').' 以降の日付を指定してください。';
            $error[] = $t;
            $b_border = false;
        }
    
        return $error;
    }

    function makeContactID($param) {

        if (empty($param['mail_address'])) return false;

        $salt = '_kaitekiPrint';
        $DT = new \Datetime();
        $DT->setTimezone(new \DatetimeZone('Asia/Tokyo'));
        
        $id = $DT->format('Ymd_His_');
        
        $hash = hash('SHA256',$param['mail_address'].$salt);
        $hash = str_replace('a','r',$hash);
        $hash = str_replace('b','t',$hash);
        $hash = str_replace('c','u',$hash);
        $hash = str_replace('d','m',$hash);
        $hash = str_replace('e','o',$hash);
        $hash = str_replace('f','w',$hash);
        $id .= substr($hash, 0, 8);

        return $id;
    }

    // 発注書出力時にユーザー情報を取得しロックする予定（顧客側アクションでは動かさない）

    function adjustOrderFromData($param) {

//        $param['payment_limit']    = $param['print_up_date'];

        $param['product_set_id']    = $param['id'];
         
        if (!empty($param['b_lock_userdata'])) {
            $param['user_id']           = $param['userdata']['id'];
            $param['user_name']         = $param['userdata']['name'];
            $param['user_name_kana']    = $param['userdata']['name_kana'];
            $param['birthday']          = $param['userdata']['birthday'];
            $param['sextype']           = $param['userdata']['sextype'];
            $param['zipcode']           = $param['userdata']['zipcode'];
            $param['address1']          = $param['userdata']['address1'];
            $param['address2']          = $param['userdata']['address2'];
            $param['tel']               = $param['userdata']['tel'];
            $param['tel_range']         = $param['userdata']['tel_range'];
        }

        unset($param['userdata'], $param['id']);

        return $param;
    }

    // ここにあるとメンテナンス困難な為、Paymentモデルに移植
/*    
    function getPaymentLimit($param) {
        return (new \App\Models\DB\Payment())->getPaymentLimit($param);
    }
/*
    function getPaymentLimit($param) {

        $limit_date = '';

        foreach($param['payment_order'] as $order_id) {

            $data = $this->find($order_id);

            if (empty($limit_date) || $data['payment_limit'] < $limit_date)
                $limit_date = $data['payment_limit'];
        }

        $DT = new \Datetime();
        $DT_limit = new \Datetime($limit_date);
        $DT_limit_0 = new \Datetime($DT_limit->format('Y-m-d').' 00:00:00');
        $diff = ($DT_limit->getTimestamp() - $DT->getTimestamp());

        $diff_min = floor($diff / 60);
        $border_minutes = 60 * (24 + 10); // ※10時締切の場合

        // 決済代行会社システムの仕様上、指定しない方のパラメータはゼロではなく空文字にする
        if ($border_minutes < $diff_min) {
            $diff2 = ($DT_limit_0->getTimestamp() - $DT->getTimestamp());
            $param['payment_term_day'] = floor($diff2 / 86400);
            $param['payment_term_min'] = '';
            $param['limit_date'] = $limit_date;

        } else {
            $param['payment_term_min'] = $diff_min;
            $param['payment_term_day'] = '';
            $param['limit_date'] = $DT_limit_0->format('Y-m-d 00:00:00');
        }
        return $param;
    }
*/
    function modify($param)
    {
        $data = [];
        $id = 0;

        $param = $this->makeData($param);

        if (!empty($param['id'])) {
            
            $compare = $this->find($param['id']);

            foreach($this->allowedFields as $key)
                if ($param[$key] != $compare[$key])
                    $data[$key] = $param[$key];

            $this->save($data);
            $id = $param['id'];

        } else {

            foreach($this->allowedFields as $key)
                if (!empty($param[$key]))
                    $data[$key] = $param[$key];
            
            $this->insert($data);
            $id = (int)$this->insertID;
        }

        return $id;
    }

    // 決済IDを書き込む　全額ポイント決済はステータスも遷移させる

    function modifyFromPayment($param) {

        if (empty($param['id'])
        ||  empty($param['payment_order'])
        ||  !count($param['payment_order'])
        ) return false;

        $b_point_pay = ($param['amount'] == 0 && $param['point_use'] > 0);

        foreach($param['payment_order'] as $order_id) {

            $data = $this->find($order_id);
            $data['payment_id'] = $param['id'];

            $data['status'] = $b_point_pay ? 140 : 11;

            $this->save($data);
        }
    }

    function completePayment($param) {

        if (!empty($param['payment_id'])) $payment_id = $param['payment_id'];
        elseif (!empty($param['id'])) $payment_id = $param['id'];
        else return false;

        $data = $this
        ->where('payment_id', $payment_id)
        ->findAll();

        if (empty($data) || !count($data)) return false;

        $a = [];
        foreach($data as $order) {
            $order['status'] = !empty($param['b_not_mail']) ? 40 : 140;
            $this->save($order);

            if (!empty($order['product_set_id'])
            &&  !in_array($order['product_set_id'], $a)
            )   $a[] = $order['product_set_id'];
        }

        // 残り販売数を更新
        if (count($a)) {
            $Product = new \App\Models\DB\ProductSet();

            foreach($a as $product_id)
                $Product->updateOrderedCount($product_id);
        }
        
        return true;
    }

    function cancelPayment($param) {

        if (!empty($param['payment_id'])) $payment_id = $param['payment_id'];
        elseif (!empty($param['id'])) $payment_id = $param['id'];
        else return false;

        $data = $this
        ->where('payment_id', $payment_id)
        ->findAll();

        if (empty($data) || !count($data)) return false;

        foreach($data as $order) {
            $order['payment_id'] = null;
            $order['status'] = !empty($param['b_not_mail']) ? 10 : 110;
            $this->save($order);
        }
        return true;
    }

    function makeData($param) {

        $Crypt = new \App\Models\Crypt();

        foreach($this->dateFields as $key)
            if (!empty($param[$key]))
                $param[$key] = str_replace('/','-',$param[$key]);

        $a = [];
        foreach($this->protectedFields as $key)
            if (isset($param[$key]))
                $a[$key] = $param[$key];
        
        if (!empty($a))
            $param['protect'] =
                $Crypt->encryptWithIV(json_encode($a));

        $param['ex'] = $param['ex'] ?? [];
        foreach($this->exFields as $key)
            if (isset($param[$key]))
                $param['ex'][$key] = $param[$key];
        
        if (!empty($param['ex']) && count($param['ex']))
            $param['ex'] = json_encode($param['ex']);
        
        return $param;
    }

    function parseData($param) {

        $Crypt = new \App\Models\Crypt();

        if (!empty($param['protect'])) {
            $j = $Crypt->decryptWithIV($param['protect']);
            $a = json_decode($j, true);
            $param = array_merge($param, $a);
            unset($a, $param['protect']);
        }

        if (!empty($param['ex']) && $param['ex'] != '{}') {
            $a = json_decode($param['ex'], true);
            $param = array_merge($param, $a);
            unset($a, $param['ex']);
        }

        $param['status_name'] = $this->statusName[$param['status']] ?? '';

        if (!empty($param['mode']) && in_array($param['mode'], ['detail','search'])) {

            $User = new \App\Models\DB\User();
            $param['userdata'] = $User->getFromID($param['user_id']);
            unset($User);

            $Delivery = new \App\Models\DB\Delivery();
            $param['delivery'] = $Delivery->getFromOrderID($param['id']);
            unset($Delivery);

            $ProductSet = new \App\Models\DB\ProductSet();
            $param['product_set'] = $ProductSet->getFromID($param['product_set_id']);
            unset($ProductSet);

            $Client = new \App\Models\DB\Client();
            $temp = $Client->getFromCode($param['product_set']['client_code']);
            $param['product_set']['client_name'] = $temp['name'];
            $param['product_set']['client_tel'] = $temp['tel'];
            $param['product_set']['client_mail'] = $temp['mail_address'];
            unset($Client);

            if (!empty($param['payment_id'])) {
                $Payment = new \App\Models\DB\Payment();
                $param['payment'] = $Payment->find($param['payment_id']);
                $param['payment']['type_name'] = 
                    $Payment->getPaymentTypeName($param['payment']['type']);
                unset($Payment);
                $param['payment']['ex'] =
                    json_decode($param['payment']['ex'], true);
            }

        }
        
        return $param;
    }

}