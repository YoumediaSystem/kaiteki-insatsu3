<?php

namespace App\Models\DB;

use CodeIgniter\Model;
//use CodeIgniter\Database\ConnectionInterface;

class Delivery extends Model
{
    protected $table      = 'delivery';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'type',
        'date',
        'number',
        'name_kana',
        'order_id',
        'note',
        'admin_id',
        'status',
        'protect',
        'ex'
    ];

    protected $protectedFields = [
        'zipcode',
        'real_address_1',
        'real_address_2',
        'real_name',
        'real_name_kana',
        'tel'
    ];

    protected $exFields = [
        'name',
        'place',
        'hall_name',
        'space_code',
        'circle_name',
        'circle_name_kana'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    protected $key2name;

    protected $necessary = [
        'print_up_date'
        ,'print_data_url'
        ,'print_title'
        ,'print_number_all'
        ,'print_page'
    ];

    protected $necessary_event = [
        '_date'
        ,'_name'
        ,'_place'
        ,'_circle_name'
        ,'_circle_name_kana'
    ];

    protected $necessary_other = [
        'other_zipcode'
       ,'other_real_address_1'
       ,'other_real_name'
       ,'other_real_name_kana'
       ,'other_tel'
    ];

    protected $deny_url_key = [
        'other_real_name'
        ,'other_real_name_kana'
        ,'other_real_address_1'
        ,'other_real_address_2'
        ,'print_title'
    ];

    protected $max_input_length = [

        'real_name'				=> 100

        ,'real_name'			=> 20
        ,'real_name_kana'		=> 20
        
        ,'mail_address'			=> 256
        
        ,'zipcode'				=> 8
        ,'real_address_1'		=> 200
        ,'real_address_2'		=> 200
        ,'tel'					=> 20
        ,'tel_range'			=> 100
        
        ,'print_up_date_y'	=> 4
        ,'print_up_date_m'	=> 2
        ,'print_up_date_d'	=> 2
        
        ,'birthday_y'		=> 4
        ,'birthday_m'		=> 2
        ,'birthday_d'		=> 2
        
        ,'print_data_url'	=> 2048
        ,'print_title'		=> 100
        ,'print_number_all'	=> 5
        ,'print_page'		=> 5
        ,'main_buffer_paper_detail'		=> 100
        
        ,'auth_key' => 256
    ];

    protected $encode_param = [
        'zipcode'
       ,'tel'
       ,'space_code'
       ,'circle_name_kana'
       ,'real_name_kana'
    ];
    
    protected $encode_param_kana = [
        'circle_name_kana'
       ,'real_name_kana'
    ];

    private $salt = 'youclub1211';

/*
    function __construct() {
        $OrderForm = new \App\Models\Service\OrderForm();
        $this->key2name = $OrderForm->getKey2Names();
    }
*/

    public function getFromID($id) {

        if (empty($id)) return [];

        $data = $this->find($id);

        if (empty($data['id'])) return [];

        return $this->parseData($data);
    }

    public function getFromOrderID($order_id) {

        if (empty($order_id)) return [];

        $data = $this
        ->where('order_id', $order_id)
        ->where('status >= 0')
        ->findAll();

        if (empty($data)) return [];

        $result = [];
        foreach ($data as $delivery)
            $result[] = $this->parseData($delivery);

        return $result;
    }

    function adjustParam($param) {

        $lib = new \App\Models\CommonLibrary();

        $param['zipcode']	= preg_replace('/-|－|ー|―/u', '', $param['zipcode']);
        
        $param['tel']		= preg_replace('/-|－|ー|―/u', '-', $param['tel']);

        foreach($this->encode_param as $key)
            if (!empty($param[$key]))
                $param[$key] = mb_convert_kana($param[$key], 'KVCa');
        
        foreach($this->encode_param_kana as $key)
            if (!empty($param[$key]))
                $param[$key] = preg_replace('/[^　ァ-ヶー]/u', '', $param[$key]);
        
        return $param;
    }

    function checkParam($param) {

        $lib = new \App\Models\CommonLibrary();

        $error = [];

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

    // 発注書出力時にユーザー情報を取得しロックする予定（顧客側アクションでは動かさない）

    function adjustOrderFromData($param) {

//        $param['payment_limit']    = $param['print_up_date'];

        $param['product_set_id']    = $param['id'];
         
        if (!empty($param['b_lock_userdata'])) {
            $param['user_id']           = $param['userdata']['id'];
            $param['user_name']         = $param['userdata']['name'];
            $param['user_name_kana']    = $param['userdata']['name_kana'];
            $param['birthday']          = $param['userdata']['birthday'];
            $param['sextype']           = $param['userdata']['sextype'];
            $param['zipcode']           = $param['userdata']['zipcode'];
            $param['address1']          = $param['userdata']['address1'];
            $param['address2']          = $param['userdata']['address2'];
            $param['tel']               = $param['userdata']['tel'];
            $param['tel_range']         = $param['userdata']['tel_range'];
        }

        unset($param['userdata'], $param['id']);

        return $param;
    }

    function modifyFromOrder($param) {

        if (!empty($param['number_event_1'])) {
            $p = [];
            $p['type']      = 'event';
            $p['date']      = $param['event_1_date'];
            $p['number']    = $param['number_event_1'];
            $p['name_kana'] = $param['event_1_circle_name_kana'];
            $p['order_id']  = $param['order_id'];
            $p['ex'] = [];

            foreach($this->exFields as $key) {
                $kkey = 'event_1_'.$key;
                if (!empty($param[$kkey]))
                    $p['ex'][$key] = $param[$kkey];
            }
            $this->modify($p);
        }

        if (!empty($param['number_event_2'])) {
            $p = [];
            $p['type']      = 'event';
            $p['date']      = $param['event_2_date'];
            $p['number']    = $param['number_event_2'];
            $p['name_kana'] = $param['event_2_circle_name_kana'];
            $p['order_id']  = $param['order_id'];
            $p['ex'] = [];

            foreach($this->exFields as $key) {
                $kkey = 'event_2_'.$key;
                if (!empty($param[$kkey]))
                    $p['ex'][$key] = $param[$kkey];
            }
            $this->modify($p);
        }

        if (!empty($param['number_other'])) {
            $p = [];
            $p['type']      = 'other';
            $p['number']    = $param['number_other'];
            $p['name_kana'] = $param['other_real_name_kana'];
            $p['order_id']  = $param['order_id'];

            foreach($this->protectedFields as $key) {
                $kkey = 'other_'.$key;
                if (!empty($param[$kkey]))
                    $p[$key] = $param['other_'.$key];
            }
            
            $this->modify($p);
        }
    }

    function checkForAdmin($param) {

        $error = [];
        if ($param['type'] == 'event') {

            foreach($this->exFields as $key) {
                if ($key != 'hall_name' && empty($param[$key]))
                    $error[] = $this->key2name[$key].'を入力してください。';
            }

        } elseif ($param['type'] == 'other') {

            foreach($this->protectedFields as $key) {
                if ($key != 'real_address_2' && empty($param[$key]))
                    $error[] = $this->key2name[$key].'を入力してください。';
            }
        }
        return $error;
    }

    function modifyFromAdmin($param) {

        $param = $this->makeData($param);

        if (!empty($param['id'])) {
            $this->save($param);
            $id = $param['id'];

        } else {
            $this->insert($data);
            $id = (int)$this->insertID;
        }
        return $id;
    }

    // 顧客側で使う　管理側は別のメソッドを使う
    function modify($param)
    {
        $data = [];
        $id = 0;

        $param = $this->makeData($param);

        if (!empty($param['id'])) {
            
            $data['user_id'] = $param['id'];

            $compare = $this->find($param['user_id']);

            foreach($this->allowedFields as $key)
                if (isset($param[$key])
                &&  $param[$key] != $compare[$key]
                &&  $key != 'pass')
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

        if (!empty($param['date'])) {
            $a = explode('-', $param['date']);
            $param['date_y'] = $a[0];
            $param['date_m'] = $a[1];
            $param['date_d'] = $a[2];
        }
/*
        $param['name'] = $param['sei'].' '.$param['mei'];
        $param['name_kana'] = $param['sei_kana'].' '.$param['mei_kana'];

//        if (!empty($this->statusName[$param['status']])
        $param['status_name'] = $this->statusName[$param['status']] ?? '';
*/
        return $param;
    }

}