<?php

namespace App\Models\DB;

use CodeIgniter\Model;
//use CodeIgniter\Database\ConnectionInterface;

class Admin extends Model
{
//    protected $db;

//    protected $DBGroup = 'local';

    protected $table      = 'admin';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'role',
        'client_code',
        'status',
        'm_hash',
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
        'role',
        'client_code',
        'status',
        'm_hash',
        'pass',
        'protect',
        'ex'
    ];

    protected $protectedFields = [
        'name',
        'mail_address'
    ];

    protected $key2name = [
        'mail_address'  => '管理ID',
        'pass'          => 'パスワード',
        'name'          => '名前'
    ];

    protected $necessary_login = [
        'mail_address',
        'name',
        'pass'
    ];

    protected $necessary_admin = [
        'mail_address',
        'name'
    ];

    protected $deny_url_key = [
        'name'
    ];

    protected $max_input_length = [
        'mail_address' => 256,
        'name' => 32,
        'role' => 16
    ];

    private $salt = 'youclub1211';

    // 値補正
    function adjustParam($param)
    {
        return $param;
    }

    // 値チェック
    function checkParam($param)
    {
        $lib = new \App\Models\CommonLibrary();
        $error = [];

        if ($param['mode'] == 'create')
            $necessary = (array)$this->necessary_login;

        else
            $necessary = (array)$this->necessary_admin;

        foreach($necessary as $key) { // 必須入力チェック
            if(!isset($param[$key]) || !strlen($param[$key]))
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

        if (!empty($param['pass'])
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

    // 全件取得（システム管理者ページ用）

    function getList() {

        $data = $this->findAll();

        foreach($data as $key=>$row)
            $data[$key] = $this->parseData($row);
        
        return $data;
    }


    // 会員no.から取得（ログイン後汎用）

    function getFromID($id) {

        if (empty($id)) return false;

        $data = $this->find($id);
        $data = $this->parseData($data);
        return $data;
    }

    // 管理者no.から名前だけ取得

    function getNameFromID($id) {

        if (empty($id)) return false;

        $data = $this->find($id);
        $data = $this->parseData($data);
        return $data['name'];
    }

    // ID・パスワードから取得（ログイン認証用）

    function getFromAuthInfo($param) {

        $session = session();
        
        if (empty($param['mail_address'])
        ||  empty($param['pass'])
        ) return 'param empty';//false;

        $data = $this
        ->where('m_hash',
            hash('sha256', $param['mail_address'].$this->salt))
        ->find();

        if (empty($data[0]['pass'])
        ) return 'record not exists';//false;

        $session->set('log', json_encode($data[0]));

        if (!password_verify($param['pass'], $data[0]['pass'])
        ) return 'verify out';//false;

        $data = $this->parseData($data[0]);
        return $data;
    }

    // 登録用データに加工する

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
        
        if (!empty($param['mail_address']))
            $param['m_hash'] = hash('SHA256', $param['mail_address'].$this->salt);

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
        return $param;
    }

    function modify($param)
    {
        $data = [];

        if (!empty($param['id'])) {

            $data['id'] = $param['id'];

            $param = $this->makeData($param);

            $compare = $this->find($param['id']);

            foreach($this->modifyFields as $key)
                if (isset($param[$key])
                &&  ($key != 'pass' || !empty($param[$key])) // pass未入力はスルー
                &&  $param[$key] != $compare[$key]
                )
                    $data[$key] = $param[$key];

            $result = $this->save($data);

        } else {
            $data = $this->makeData($param);
            
            $result = $this->insert($data);

            if ($result)
                $result = (int)$this->insertID;
        }

        return $result;
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
}