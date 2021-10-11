<?php

namespace App\Models\DB;

use CodeIgniter\Model;
//use CodeIgniter\Database\ConnectionInterface;

class AuthCommon extends Model
{
//    protected $db;

//    protected $DBGroup = 'local';

    protected $table      = 'auth_common';
    protected $primaryKey = 'hash';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['type', 'hash', 'ex'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;    

    private $salt;
/*
	function __construct()
    {
        $this->DBGroup = 'local';
//        $this->DB =& $db;

//        $this->DB = \Config\Database::connect();
//        $this->builder = $this->DB->table('auth_common');

        $this->salt = (new \App\Models\Service\Config())->getSalt();
    }
*/
    // 認証ハッシュ照会
    function is_auth($param)
    {
        $this->refresh();
        if (empty($param['hash'])) $param['hash'] = 'NULL';

        $row = $this
            ->where('hash', $param['hash'])
            ->find();

        return (is_array($row) && count($row) == 1);
    }

    function getBufferData($param) {

        $row = $this
//            ->where('hash', $param['hash'])
            ->find($param['hash']);

        $a = json_decode($row['ex'], true);

        return is_array($a) ? $a : [];
    }

    function makeHash($param)
    {
        $lib = new \App\Models\CommonLibrary();

        $i = 0;

        do {
            $hash = $lib->getRandomStrings(32);
    
            // 同一キー有無チェック
            $row = $this->where('hash', $hash)->findAll();

            $i++;
            if (10 <= $i) return false;

        } while(0 < count($row));

        if (empty($param['ex'])) $ex = '{}';
        elseif (is_array($param['ex'])) $ex = json_encode($param['ex']);

        $this->insert([
            'type'  => $param['type'],
            'hash'  => $hash,
            'ex'    => $ex
        ]);

        return $hash;
    }

    function makeURL($param) {

        $Config = new \App\Models\Service\Config();

        $request = \Config\Services::request();
        $server = $request->getServer();

        $url = $Config->getProtocol();
        $url .= $server['SERVER_NAME'];
        $url .= $param['action'];
        $url .= '?hash='.$param['hash'];

        return $url;
    }

    // 過去の認証情報削除
    function refresh() {

        $DT1 = new \Datetime();
        $DT1->modify('-1 day');

        $this
        ->where('access_date <', $DT1->format('Y-m-d H:i:s'))
        ->delete();
    }

    // 処理済の認証情報削除
    function clearBuffer($param) {
        $this->delete($param['hash']);
    }

    //$builder->resetQuery()
}