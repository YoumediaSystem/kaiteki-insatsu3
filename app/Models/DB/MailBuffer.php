<?php

namespace App\Models\DB;

use CodeIgniter\Model;
//use CodeIgniter\Database\ConnectionInterface;

class MailBuffer extends Model
{
//    protected $db;

//    protected $DBGroup = 'local';

    protected $table      = 'mail_buffer';
//    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'sendlist_id',
        'request_date',
        'status',
        'protect'
    ];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;    

    protected $modifyFields = [
        'request_date',
        'status',
        'protect'
    ];

    protected $protectedFields = [
        'from',
        'to',
        'bcc',
        'subject',
        'body'
    ];

    protected $key2name = [
    ];

    protected $necessary = [
    ];

    protected $max_input_length = [
    ];

    protected $statusName = [
    ];

    private $salt = 'youclub1211';

    protected $rows_of_page = 20;

    // 値補正
    function adjustParam($param)
    {
        return $param;
    }

    // 値チェック
    function checkParam($param) {

    }

    function checkPass($param) {

    }

    // 送信履歴IDから取得

    function getFromID($id) {

        if (empty($id)) return false;

        $data = $this->find($id);

        if (!empty($data)) $data2 = $this->parseData($data);

        return $data2;
    }

    // 処理対象をリストアップ

    function getBuffer($number) {

        if (empty($number)) return [];

        $data = $this->where('status', 0)->findAll($number);

        $data2 = [];
        if (!empty($data)) {
            foreach($data as $row)
                $data2[] = $this->parseData($row);
        }

        return $data2;
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
        $Config = new \App\Models\Service\Config();

        $mail = $Config->getMailAddress();

        if (empty($param['to']) && !empty($param['mail_address']))
            $param['to'] = $mail['mail_address'];

        if (empty($param['from']))
            $param['from'] = $mail['admin_mail_address'];

        if (empty($param['bcc']))
            $param['bcc'] = $mail['bcc_mail_address_2user'];

        $a = [];
        foreach($this->protectedFields as $key)
            if (isset($param[$key]))
                $a[$key] = $param[$key];
        
        if (!empty($a))
            $param['protect'] =
                $Crypt->encryptWithIV(json_encode($a));

        if (!empty($param['ex']) && is_array($param['ex']))
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

        return $param;
    }

    function modify($param)
    {
        $data = [];

        if (!empty($param['id'])) {

            $data['id'] = $param['id'];

            $param = $this->makeData($param);

            $compare = $this->where($param['id'])->find();

            foreach($this->modifyFields as $key)
                if (isset($param[$key])
                &&  $param[$key] != $compare[$key])
                    $data[$key] = $param[$key];

            if (!empty($data))
                $result = $this->save($data);

        } else {

            foreach($this->allowedFields as $key)
                if (!empty($param[$key]))
                    $data[$key] = $param[$key];

            if (!empty($data)) {
                $this->insert($data);
                $result = (int)$this->insertID;
            }
        }

        return $result;
    }

    function garbageCollection() {

        $this->where('status',1)->delete();
    }
}