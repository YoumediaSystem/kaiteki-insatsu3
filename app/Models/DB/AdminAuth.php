<?php

namespace App\Models\DB;

use CodeIgniter\Model;
//use CodeIgniter\Database\ConnectionInterface;

class AdminAuth extends Model
{
    protected $table      = 'admin_auth';
    protected $primaryKey = 'token';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'admin_id',
        'token',
    ];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;    

    protected $protectedFields = [
    ];

    protected $key2name = [
    ];

    protected $necessary = [
    ];

    protected $deny_url_key = [
    ];

    protected $max_input_length = [
    ];

    private $salt = 'youclub1211';
/*
    function modify($param)
    {
        if (empty($param['admin_id'])
        ||  empty($param['token'])
        ) return false;
        
        $this->save($param);
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
*/
    // 過去の認証情報削除
    function refresh() {

        $DT1 = new \Datetime();
        $DT1->modify('-30 days');

        $this
        ->where('access_date <', $DT1->format('Y-m-d H:i:s'))
        ->delete();
    }

}