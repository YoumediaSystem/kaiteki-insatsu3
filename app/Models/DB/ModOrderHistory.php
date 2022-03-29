<?php

namespace App\Models\DB;

use CodeIgniter\Model;
//use CodeIgniter\Database\ConnectionInterface;

class ModOrderHistory extends Model
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

    protected $table      = 'mod_order_history';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'org_id',
        'price',
        'ex'
    ];

    protected $protectedFields = [
        'print_data_password',
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
        'delivery_divide',
        'price_text',
        'number_home',
        'number_kaiteki',
        'b_overprint_kaiteki',
        'nonble_from',
        'b_extra_order',
        'extra_order_note',
        'basic_price',
        'adjust_price',
        'adjust_note_front',
        'adjust_note_admin'
    ];

    protected $dateFields = [
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    protected $key2name;

    protected $necessary = [
    ];

    protected $necessary_event = [
    ];

    protected $necessary_other = [
    ];

    protected $deny_url_key = [
    ];

    protected $max_input_length = [
    ];

    protected $encode_param = [
    ];
    
    protected $encode_param_kana = [
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

    public function getFromOrderID($order_id) {

        if (empty($order_id)) return [];

        $data = $this
        ->where('org_id', $order_id)
        ->orderBy('id','DESC')
        ->first();

        if (empty($data['id'])) return [];

        return $this->parseData($data);
    }

    public function getLatestPrice($order_id) {

        if (empty($order_id)) return [];

        $data = $this
        ->where('org_id', $order_id)
        ->orderBy('id','DESC')
        ->first();

        if (empty($data['price'])) return 0;

        return $data['price'];
    }

    function adjustParam($param) {

        $lib = new \App\Models\CommonLibrary();

        if (!empty($param['price_text']))
            $param['price_text'] = $lib->unicode_unescape($param['price_text']);

        return $param;
    }

    function modify($param)
    {
        $data = [];
        $id = 0;

        $param = $this->makeData($param);

        if (!empty($param['id'])) {
            
            $data['id'] = $param['id'];
            $compare = $this->find($param['id']);

            $b = false;
            foreach($this->allowedFields as $key)
                if ($param[$key] != $compare[$key]) {
                    $data[$key] = $param[$key];
                    $b = true;
                }

            if ($b) $this->save($data);
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

        return $param;
    }

}