<?php

namespace App\Models\DB;

use CodeIgniter\Model;
//use CodeIgniter\Database\ConnectionInterface;

class Client extends Model
{
    protected $statusName = [
         0 => '有効'
       ,-1 => '削除'
    ];

    protected $table      = 'client';
    protected $primaryKey = 'code';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'code',
        'name',
        'tel',
        'mail_address'
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

    protected $CR = "\n";


    public function getFromCode($code) {

        if (empty($code)) return [];

        $data = $this->find($code);

        return $data;

//        return $this->parseData($data);
    }

    public function getName($code) {

        $data = $this->getFromCode($code);

        return $data['name'] ?? '';
    }

    public function sendDailyReport() {

        $Product = new \App\Models\DB\ProductSet();
        $Order = new \App\Models\DB\OrderHistory();
        $DT = new \Datetime();

        $client = $this->findAll();

        foreach($client as $key=>$data) {

            $p = $data;
            $product = $Product
            ->where('client_code', $data['code'])
            ->where('open_date <=', $DT->format('Y-m-d H:i:s'))
            ->where('close_date >=', $DT->format('Y-m-d H:i:s'))
            ->findAll();

            $t = '';
            if (isset($product) && count($product)) {
                foreach($product as $kkey=>$ddata) {
                    $count = $Order
                    ->where('product_set_id', $ddata['id'])
                    ->where('status', 40)
                    ->countAllResults();

                    $t .= $ddata['name'].' … '.$count.'件'.$this->CR.$this->CR;
                }
            }

            if (strlen($t)) {
                $p['order_detail'] = $t;
                $b = (new \App\Models\Mail\Report4Client())->sendAutomail($p);
            }
        }
    }
/*
    public function getCodeList() {

        $data = $this
        ->where('code', $id)
        ->findAll();

        return $data;
    }
*/
}