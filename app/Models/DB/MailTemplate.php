<?php

namespace App\Models\DB;

use CodeIgniter\Model;

class MailTemplate extends Model
{
//    protected $db;

//    protected $DBGroup = 'local';

    protected $table      = 'mail_template';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'subject',
        'body',
        'admin_id',
        'status'
    ];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;    

    protected $modifyFields = [
        'subject',
        'body',
        'admin_id',
        'status'
    ];

    protected $protectedFields = [
    ];

    protected $key2name = [
        'subject'       => '件名',
        'body'          => '本文',
        'admin_id'      => '更新者ID',
        'status'        => 'ステータス'
    ];

    protected $necessary = [
        'subject',
        'body'
    ];

    protected $deny_url_key = [
        'subject'
    ];

    protected $max_input_length = [
        'subject' => 256,
        'body' => 1000
    ];

    protected $statusName = [
        0   => '利用可能'
        ,-1 => '削除'
    ];

    private $salt = 'youclub1211';

    protected $rows_of_page = 20;

    function getStatusNameArray() {
        return (array)$this->statusName;
    }

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

        $param['mode'] = $param['mode'] ?? '';

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

        return $error;
    }

    function checkPass($param) {

        $error = [];

        if (isset($param['pass'])
        &&  !preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,16}+\z/i', $param['pass']))
            $error[] = 'パスワードは半角英字と半角数字を含む8～16文字で入力してください。';

            return $error;
    }

    // IDから取得（ログイン後汎用）

    function getFromID($id) {

        if (empty($id)) return false;

        $data = $this->find($id);
        $data = $this->parseData($data);
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

    // リスト取得（管理サイト用）

    function getList($param = []) {

        $b_all = (
            empty($param['subject'])
        &&  empty($param['status'])
        );
        
        if (empty($param['page'])) $param['page'] = 0;

        if ($b_all) {
            $count = $this
            ->countAllResults(false);

        } else {
            $count = $this
//            ->orWhere('subject', $param['subject'])
            ->where('status', $param['status'])
            ->like('subject', $param['subject'])
            ->orderBy('id', 'DESC')
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
        
        return $param;
    }

    function parseData($param) {

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

        return $result;
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

}