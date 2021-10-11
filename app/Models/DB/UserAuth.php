<?php

namespace App\Models\DB;

use CodeIgniter\Model;
//use CodeIgniter\Database\ConnectionInterface;

class UserAuth extends Model
{
    protected $table      = 'user_auth';
    protected $primaryKey = 'token';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'user_id',
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
        $data = [];

//        $param = $this->makeData($param);

        if (!empty($param['id'])) {
            
            $data['user_id'] = $param['id'];

            $compare = $this->find($param['user_id']);

            foreach($this->allowedFields as $key)
                if ($param[$key] != $compare[$key]
                &&  $key != 'pass')
                    $data[$key] = $param[$key];

        } else {

            foreach($this->allowedFields as $key)
                if (!empty($param[$key]))
                    $data[$key] = $param[$key];
        }

        $id = 0;
        if (!empty($data)) {
            $this->save($data);
            $id = (int)$this->insertID;
        }

        return $id;
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