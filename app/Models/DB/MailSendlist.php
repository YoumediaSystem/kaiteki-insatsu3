<?php

namespace App\Models\DB;

use CodeIgniter\Model;
//use CodeIgniter\Database\ConnectionInterface;

class MailSendlist extends Model
{
//    protected $db;

//    protected $DBGroup = 'local';

    protected $table      = 'mail_sendlist';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'status',
        'user_id',
        'template_id',
        'request_date',
        'admin_id'
    ];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;    

    protected $modifyFields = [
        'status',
        'user_id',
        'template_id',
        'request_date',
        'admin_id'
    ];

    protected $protectedFields = [
    ];

    protected $key2name = [
        'status'        => 'ステータス',
        'user_id'       => '会員ID',
        'template_id'   => 'メール雛形ID',
        'request_date'  => '配信予約日時',
        'admin_id'      => '管理者ID'
    ];

    protected $necessary = [
        'user_id',
        'template_id',
        'request_date'
    ];

    protected $max_input_length = [
    ];

    protected $statusName = [
         0  => '未送信'
        ,1  => '送信済'
        ,-1 => '停止'
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

        if (isset($param['birthday_y'])
        &&  isset($param['birthday_m'])
        &&  isset($param['birthday_d'])
        ) {
            $param['birthday']  = $param['birthday_y'].'-';
            $param['birthday'] .= $param['birthday_m'].'-';
            $param['birthday'] .= $param['birthday_d'];
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
        
        foreach($this->deny_url_key as $key) { // URL混入チェック
            if(!empty($param[$key]) && $lib->is_url($param[$key]))
            $error[] = $this->key2name[$key].'に送信できない文字が含まれています。';
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

    // 送信履歴IDから取得

    function getFromID($id) {

        if (empty($id)) return false;

        $data = $this->find($id);
//        $data = $this->parseData($data);
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

    // 送信履歴リスト取得（管理サイト用）

    function getList($param) {

        $b_all = (empty($param['template_id'])
        &&  empty($param['user_id'])
        );
        
        if (empty($param['page'])) $param['page'] = 0;

        if ($b_all) {
            $count = $this
            ->countAllResults(false);

        } else {
            $count = $this
            ->orWhere('template_id', $param['template_id'])
            ->orWhere('user_id', $param['user_id'])
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

        $a = explode('-', $param['birthday']);
        $param['birthday_y'] = $a[0];
        $param['birthday_m'] = $a[1];
        $param['birthday_d'] = $a[2];

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