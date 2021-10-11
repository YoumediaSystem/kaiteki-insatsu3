<?php

namespace App\Models\DB;

use CodeIgniter\Model;
//use CodeIgniter\Database\ConnectionInterface;

class User extends Model
{
//    protected $db;

//    protected $DBGroup = 'local';

    protected $table      = 'user';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'status',
        'point',
        'sei_kana',
        'mei_kana',
        'mail_address',
        'note',
        'admin_id',
        't_hash',
        'pass',
        'protect',
        'ex'
    ];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;    

    protected $modifyFields = [
        'sei_kana',
        'mei_kana',
        'note',
        'admin_id',
        't_hash',
        'protect',
        'ex'
    ];

    protected $protectedFields = [
        'sei',
        'mei',
        'birthday',
        'sextype',
        'zipcode',
        'address1',
        'address2',
        'tel',
        'tel_range',
    ];

    protected $key2name = [
        'mail_address'  => 'メールアドレス',
        'pass'          => 'パスワード',
        'sei'           => '姓',
        'mei'           => '名',
        'sei_kana'      => '姓カナ',
        'mei_kana'      => '名カナ',
        'birthday'      => '生年月日',
        'sextype'       => '性別',
        'zipcode'       => '郵便番号',
        'address1'      => '住所1',
        'address2'      => '住所2',
        'tel'           => '電話番号',
        'tel_range'     => '連絡可能時間帯'
    ];

    protected $necessary = [
        'pass',
        'sei',
        'mei',
        'sei_kana',
        'mei_kana',
        'birthday',
        'sextype',
        'zipcode',
        'address1',
        'tel'
    ];

    protected $necessary_forget_mail = [
        'mail_address',
        'sei_kana',
        'mei_kana',
        'birthday',
        'tel'
    ];

    protected $deny_url_key = [
        'address1',
        'address2',
        'tel_range'
    ];

    protected $name_kanji_key = [
        'sei',
        'mei'
    ];

    protected $max_input_length = [
        'mail_address' => 256,
        'sei' => 6,
        'mei' => 6,
        'birthday' => 10,
        'sextype' => 3,
        'zipcode' => 7,
        'address1' => 128,
        'address2' => 128,
        'tel' => 20,
        'tel_range' => 32
    ];

    protected $max_input_kana_length = [
        'sei_kana' => 12,
        'mei_kana' => 12
    ];

    protected $statusName = [
        0   => '利用中'
        ,-1 => '退会'
        ,-2 => '強制停止'
    ];

    private $salt = 'youclub1211';

    protected $rows_of_page = 20;

    function getStatusNameArray() {
        return (array)$this->statusName;
    }

    // 値補正
    function adjustParam($param)
    {
        foreach(['tel','zipcode'] as $key) {

            if (isset($param[$key])) {
                $param[$key] = preg_replace('/-|－|ー|―/u', '', $param[$key]);
                $param[$key] = mb_convert_kana($param[$key], 'KVa');
            }
        }

        $key = 'birthday';
        if (isset($param[$key.'_y'])
        &&  isset($param[$key.'_m'])
        &&  isset($param[$key.'_d'])
        ) { $param[$key]  = $param[$key.'_y'].'-';
            $param[$key] .= $param[$key.'_m'].'-';
            $param[$key] .= $param[$key.'_d'];
        }

        return $param;
    }

    // 値チェック
    function checkParam($param)
    {
        $lib = new \App\Models\CommonLibrary();
        $error = [];

        $param['mode'] = $param['mode'] ?? '';

        $necessary = (array)$this->necessary;
        if ($param['mode'] == 'modify') {

            $key = array_search('pass', $necessary);
            if ($key !== false) unset($necessary[$key]);
        }

        if ($param['mode'] == 'forget_mail') {
            $necessary = (array)$this->necessary_forget_mail;
        }

        foreach($necessary as $key) { // 必須入力チェック
            if(empty($param[$key]))
            $error[] = $this->key2name[$key].'を入力してください。';
        }
        
        foreach($this->max_input_length as $key=>$val) { // 文字数上限チェック
            if(!empty($param[$key]) && $val < mb_strlen($param[$key],'UTF-8'))
            $error[] = $this->key2name[$key].'は'.$val.'文字以内で入力してください。';
        }
        
        foreach($this->max_input_kana_length as $key=>$val) { // 氏名カナチェック

            if(!empty($param[$key])) {

                $text = mb_convert_kana($param[$key],'k','UTF-8');

                if(!empty($text) && $val < mb_strlen($text,'UTF-8'))
                    $error[] = $this->key2name[$key].'は'.$val.'文字以内で入力してください（濁点を1文字として）';
            }
        }

        foreach($this->deny_url_key as $key) { // URL混入チェック
            if(!empty($param[$key]) && $lib->is_url($param[$key]))
            $error[] = $this->key2name[$key].'に送信できない文字が含まれています。';
        }

        foreach($this->name_kanji_key as $key) { // 氏名漢字チェック

            if(!empty($param[$key])) {

                $bad_character = $lib->findOutOfJIS1or2($param[$key]);

                if(!empty($bad_character))
                $error[] = $this->key2name[$key].'に登録できない文字（'
                    .$bad_character.'）が含まれています。常用漢字またはカナ文字で登録してください。';
            }
        }

        if (isset($param['pass'])
        &&  !preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,16}+\z/i', $param['pass']))
            $error[] = 'パスワードは半角英字と半角数字を含む8～16文字で入力してください。';

        return $error;
    }

    function checkPass($param) {

        $error = [];

        if (isset($param['pass'])
        &&  !preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,16}+\z/i', $param['pass']))
            $error[] = 'パスワードは半角英字と半角数字を含む8～16文字で入力してください。';

            return $error;
    }

    // 会員no.から取得（ログイン後汎用）

    function getFromID($id) {

        if (empty($id)) return false;

        $data = $this->find($id);
        $data = $this->parseData($data);
        return $data;
    }

    function getListFromIDs($id) {

        if (empty($id)) return false;

        $data = $this->whereIn('id',$id)->findAll();

        if (!empty($data) && count($data)) {
            foreach($data as $key=>$row)
                $data[$key] = $this->parseData($row);
        }
        return $data;
    }

    // メールアドレスからステータスのみ取得（新規登録チェック用）

    function getStatusFromMail($mail_address) {

        if (empty($mail_address)) return false;

        $data = $this->where('mail_address', $mail_address)->find();

        $result = $data[0]['status'] ?? false;

        return $result;
    }

    function getError_signup($status) {

        switch($status) {
            case 0:
                return '新規登録できません。他のメールアドレスをご入力ください。';
            
            case -1:
                return '新規登録できません。利用再開ご希望の場合は、お問合せください。';
            
            case -2:
                return '新規登録できません。';
        }
        return '[status:'.$status.']このメッセージを添えてお問合せください。';
    }

    // メールアドレスからIDのみ取得（メアド変更手続用）

    function getIDfromMail($mail_address) {

        if (empty($mail_address)) return false;

        $data = $this->where('mail_address', $mail_address)->find();

        return $data[0]['id'];
    }

    // ID・パスワードから取得（ログイン認証用）

    function getFromAuthInfo($param) {

        //$session = session();
        
        if (empty($param['mail_address'])
        ||  empty($param['pass'])
        ) return 'param empty';//false;

        $data = $this->where('mail_address', $param['mail_address'])->find();

        if (!is_array($data)
        ) return 'record not exists';//false;

//        $session->set('log', json_encode($data[0]));

        if (!password_verify($param['pass'], $data[0]['pass'])
        ) return 'verify out';//false;

        $data = $this->parseData($data[0]);
        return $data;
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

    // 会員リスト取得（管理サイト用）

    function getList($param) {

        $b_all = (empty($param['mail_address'])
        &&  empty($param['tel'])
        &&  empty($param['sei_kana'])
        &&  empty($param['mei_kana'])
        );
        
        if (empty($param['page'])) $param['page'] = 0;

        if ($b_all) {
            $count = $this
            ->countAllResults(false);

        } else {
            $count = $this
            ->orWhere('mail_address', $param['mail_address'])
            ->orWhere('sei_kana', $param['sei_kana'])
            ->orWhere('mei_kana', $param['mei_kana'])
            ->orWhere('t_hash', hash('sha256', $param['mail_address'].$this->salt))
            ->countAllResults(false);
        }

        $data = $this
        ->findAll($this->rows_of_page,
            $this->rows_of_page * $param['page']);

        $result = [];

        foreach($data as $key=>$val)
            $result[] = $this->parseData($val);

        $result['count_all'] = $count;

        return $result;
    }

    // メルマガ送信候補リストを取得

    function getMagazineList($param) {

        $b_all = (empty($param['client_code'])
        &&  empty($param['template_id'])
        );
        
        if (empty($param['page'])) $param['page'] = 0;
        
        $status = [-1,0];
        if (!empty($param['b_repeat'])) $status = [-1,0,1];

        if ($b_all) {
            $count = $this
            ->countAllResults(false);

        } else {
            $count = $this
            ->select('user.*, product_set.client_code, mail_sendlist.status as send_status')
            ->join('mail_sendlist', 'user.id = mail_sendlist.user_id', 'left')
            ->join('order_history', 'user.id = order_history.user_id', 'left')
            ->join('product_set', 'order_history.product_set_id = product_set.id', 'left')
            ->where('product_set.client_code', $param['client_code'])
            ->where('mail_sendlist.template_id', $param['template_id'])
            ->whereIn('mail_sendlist.status', $status)
//            ->where('order_history.payment_limit >', $DT->format('Y-m-d H:i:s'))
//            ->orderBy('product_set.client_code')
            ->countAllResults(false);
        }

        $data = $this
        ->findAll($this->rows_of_page,
            $this->rows_of_page * $param['page']);

        $result = [];

        foreach($data as $key=>$val)
            $result[] = $this->parseData($val);

        $result['count_all'] = $count;

        return $result;
    }

    function makeData($param) {

        $Crypt = new \App\Models\Crypt();

        $a = [];
        foreach($this->protectedFields as $key)
            if (isset($param[$key]))
                $a[$key] = $param[$key];
        
        if (!empty($a))
            $param['protect'] =
                $Crypt->encryptWithIV(json_encode($a));

        if (!empty($param['honya_id']))
            $param['ex']['honya_id'] = $param['honya_id'];

        if (!empty($param['youclub_id']))
            $param['ex']['youclub_id'] = $param['youclub_id'];

        if (!empty($param['ex']) && is_array($param['ex']))
            $param['ex'] = json_encode($param['ex']);
        
        if (!empty($param['tel']))
            $param['t_hash'] = hash('SHA256', $param['tel'].$this->salt);

        if (!empty($param['pass']))
            $param['pass'] = password_hash($param['pass'], PASSWORD_DEFAULT);

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

        $key = 'birthday';
        $a = explode('-', $param[$key]);
        $param[$key.'_y'] = $a[0];
        $param[$key.'_m'] = $a[1];
        $param[$key.'_d'] = $a[2];

        $param['name'] = $param['sei'].' '.$param['mei'];
        $param['name_kana'] = $param['sei_kana'].' '.$param['mei_kana'];

//        if (!empty($this->statusName[$param['status']])
        $param['status_name'] = $this->statusName[$param['status']] ?? '';
        
        return $param;
    }

    function modify($param)
    {
        $data = [];

        if (!empty($param['id'])) {

            $data['id'] = $param['id'];
            $data['admin_id'] = $param['admin_id'] ?? 0;

            $param = $this->makeData($param);

            $compare = $this->find($param['id']);

            foreach($this->modifyFields as $key)
                if (isset($param[$key])
                &&  $param[$key] != $compare[$key])
                    $data[$key] = $param[$key];

        } else {
            foreach($this->allowedFields as $key)
                if (!empty($param[$key]))
                    $data[$key] = $param[$key];
        }

        $result = 0;
        if (!empty($data)) {
            $result = $this->save($data);

            if (empty($param['id']))
                $result = (int)$this->insertID;
        }

        if ($result && !empty($param['sei']) && !empty($param['mei'])) {
            $session = session();
            $session->set('user_name', $param['sei'].' '.$param['mei']);
        }

        return $result;
    }

    function modifyMail($param) {
        
        if (empty($param['id'])
        ||  empty($param['mail_address'])
        ) return false;

        $data = [
            'id' => $param['id'],
            'mail_address' => $param['mail_address'],
            'admin_id' => 0
        ];

        $this->save($data);
        return true;
    }

    function modifyPass($param) {
        
        if (empty($param['id'])
        ||  empty($param['pass'])
        ) return false;

        if (strlen($param['pass']) < 60)
            $param = $this->makeData($param);

        $data = [
            'id' => $param['id'],
            'pass' => $param['pass'],
            'admin_id' => 0
        ];

        $this->save($data);
        return true;
    }

    function modifyStatus($param) {
        
        if (empty($param['id'])
        ||  !isset($param['status'])
        ) return false;

        $data = [
            'id' => $param['id'],
            'status' => $param['status'],
            'admin_id' => 0
        ];

        $this->save($data);
        return true;
    }

    function modifyPoint($param) {
        
        if (empty($param['id'])
        ||  !isset($param['point'])
        ) return false;

        $data = [
            'id' => $param['id'],
            'point' => $param['point'],
            'admin_id' => 0
        ];

        $this->save($data);
        return true;
    }

    function is_auth($param) {

        if (empty($param['mail_address'])
        ||  empty($param['pass'])
        ) return false;

        $data = $this
        ->where('mail_address', $param['mail_address'])
        ->find();

        if (empty($data['pass'])) return false;

        return password_verify($param['pass'], $data['pass']);
    }

    // メールアドレス以外の3要素で本人確認

    function is_userFromProfile($param) {

        $b = false;

        $data = $this
        ->where('sei_kana', $param['sei_kana'])
        ->where('mei_kana', $param['mei_kana'])
        ->where('t_hash', hash('sha256', $param['tel'].$this->salt))
        ->findAll();

        $birthday  = $param['birthday_y'].'-';
        $birthday .= $param['birthday_m'].'-';
        $birthday .= $param['birthday_d'];

        if (count($data)) {

            foreach($data as $row) {

                $data2 = $this->parseData($row);

                if (isset($data2['birthday'])) {
                    $DT1 = new \Datetime($birthday);
                    $DT2 = new \Datetime($data2['birthday']);
                    $b |= ($DT1 == $DT2);
                }
            }
        }

        return $b;
    }

    // 18歳以上か？

    function isOver18($id) {

        $data = $this->getFromID($id);

        if (empty($data['birthday'])) return null;

        $DT = new \Datetime();
        $now_ymd = (int)$DT->format('Ymd');
        
        $DT_birth = new \Datetime($data['birthday']);
        $birth_ymd = (int)$DT_birth->format('Ymd');

//        return $now_ymd - $birth_ymd;
//        return $now_ymd.'_'.$birth_ymd;
        return (180000 <= $now_ymd - $birth_ymd);
    }

}