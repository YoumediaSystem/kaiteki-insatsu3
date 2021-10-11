<?php

namespace App\Models\DB;

use CodeIgniter\Model;
//use CodeIgniter\Database\ConnectionInterface;

class Point extends Model
{
    protected $statusName = [
        0 => '有効'
       ,-1 => '無効'
   ];

    protected $table      = 'point';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'point',
        'detail',
        'user_id',
        'payment_id',
        'note',
        'expire_date',
        'admin_id',
        'status',
        'ex'
    ];

    protected $dateFields = [
        'expire_date'
    ];

    protected $exFields = [
    ];
    
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;    

    private $salt;

    public function getStatusName() {
        return (array)$this->statusName;
    }

    public function getFromID($id) {

        if (empty($id)) return [];

        $data = $this->find($id);

        if (empty($data['id'])) return [];

//        $data['mode'] = 'detail';

        return $this->parseData($data);
    }

    public function getDetailFromID($id) {

        if (empty($id)) return [];

        $data = $this->find($id);

        if (empty($data['id'])) return [];

        $data['mode'] = 'detail';

        return $this->parseData($data);
    }

    public function getList4user($user_id) {

        if (empty($user_id)) return [];

        $data = $this
        ->where('user_id', $user_id)
        ->where('status >=', 0)
        ->orderBy('create_date', 'DESC')
        ->findAll();

        if (empty($data) || !count($data)) return [];

        $result = [];
        foreach($data as $order)
            $result[] = $this->parseData($order);

        return $result;
    }

    public function getList4admin($user_id) {

        if (empty($user_id)) return [];

        $data = $this
        ->where('user_id', $user_id)
        ->orderBy('create_date', 'DESC')
        ->findAll();

        if (empty($data) || !count($data)) return [];

        $result = [];
        foreach($data as $order)
            $result[] = $this->parseData($order);

        return $result;
    }

    public function setAddFromPayment($param) {

        if (empty($param['point_get'])) return false;

        $param['point']         = $param['point_get'];
        $param['payment_id']    = $param['id'];
        $param['detail']        = '決済ポイント獲得';
        $param['status']        = 0;
        unset($param['id']);

        $this->modify($param);
        $this->modUserExpire($param['user_id']);
        $this->modUserPoint($param['user_id']);
    }

    public function setUseFromPayment($param) {

        if (empty($param['point_use'])) return false;

        $param['point']         = $param['point_use'];
        if (0 < $param['point']) $param['point'] *= -1;

        $param['payment_id']    = $param['id'];
        $param['detail']        = '決済ポイント利用';
        $param['status']        = 0;
        unset($param['id']);

        $this->modify($param);
        $this->modUserExpire($param['user_id']);
        $this->modUserPoint($param['user_id']);
    }

    public function resetUseFromPayment($param) {

        if (!empty($param['payment_id'])) $payment_id = $param['payment_id'];
        elseif (!empty($param['id'])) $payment_id = $param['id'];
        else return false;

        $data = $this
        ->where('payment_id', $payment_id)
        ->where('point <', 0)
        ->findAll();
        if (empty($data[0]['id'])) return false;

        $data[0]['status'] = -1;
        $this->save($data[0]);
        $this->modUserPoint($data[0]['user_id']);

        return true;
    }

    function adjustParam($param) {

        $lib = new \App\Models\CommonLibrary();

        if (empty($param['payment_id']))
            unset($param['payment_id']); // null
        
        return $param;
    }

    function checkParam($param) {

        $error = [];

        if (empty($param['user_id']))
            $error[] = '顧客no.がありません';

        if (empty($param['point']))
            $error[] = 'ポイント増減数を設定してください';

        if (empty($param['detail']))
            $error[] = 'ポイント内容を記入してください';

        return $error;
    }

    function modify($param)
    {
        $data = [];
        $id = 0;

        $param = $this->makeData($param);

        if (!empty($param['id'])) {
            $data['id'] = $param['id'];
            
            $compare = $this->find($param['id']);

            foreach($this->allowedFields as $key)
                if (isset($param[$key])
                &&  $param[$key] != $compare[$key])
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

    function makeData($param) {

        $Crypt = new \App\Models\Crypt();

        foreach($this->dateFields as $key)
            if (!empty($param[$key]))
                $param[$key] = str_replace('/','-',$param[$key]);

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

        if (!empty($param['ex']) && $param['ex'] != '{}') {
            $a = json_decode($param['ex'], true);
            $param = array_merge($param, $a);
            unset($a, $param['ex']);
        }
/*
        $param['status_name'] = $this->statusName[$param['status']] ?? '';

        if (!empty($param['mode']) && $param['mode'] == 'detail') {

            $Delivery = new \App\Models\DB\Delivery();
            $param['delivery'] = $Delivery->getFromOrderID($param['id']);

            $ProductSet = new \App\Models\DB\ProductSet();
            $param['product_set'] = $ProductSet->getFromID($param['product_set_id']);
        }
*/
        return $param;
    }

    function modUserPoint($user_id) {

        $data = $this->selectSum('point','point_sum')
        ->where('user_id', $user_id)
        ->where('status >=', 0)
        ->findAll();

        (new \App\Models\DB\User())->modifyPoint([
            'id' => $user_id,
            'point' => $data[0]['point_sum']
        ]);

        $session = session();
        $session->set('user_point', $data[0]['point_sum']);
    }

    function modUserExpire($user_id) {

        $data = $this
        ->where('user_id', $user_id)
        ->where('status >=', 0)
        ->findAll();

        if (count($data)) {

            $DT = new \Datetime();
            $DT->modify('+365 days');
            $new_expire = $DT->format('Y-m-d');
            foreach($data as $history) {
                $history['expire_date'] = $new_expire;
                $this->save($history);
            }
        }
    }

}