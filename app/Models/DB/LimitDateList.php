<?php

namespace App\Models\DB;

use CodeIgniter\Model;
//use CodeIgniter\Database\ConnectionInterface;

class LimitDateList extends Model
{
    protected $limit_hour = 12;

    protected $statusName = [
         0 => '有効'
       ,-1 => '削除'
    ];

    protected $table      = 'limit_date_list';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'client_code',
        'print_up_date',
        'limit_date',
        'status'
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

    public function getLimitHour() {
        return (int)$this->limit_hour;
    }

    public function getFromID($id) {

        if (empty($id)) return [];

        $data = $this->find($id);

        return $data;
    }

    public function getList($client_code) {

        if (empty($client_code)) {
            $data = $this->findAll();

        } else {
            $DT = new \DateTime();

            $data = $this
            ->where('client_code', $client_code)
            ->orderBy('print_up_date','DESC')
            ->findAll();
        }

        if (isset($data) && count($data))
            foreach($data as $key=>$row)
                $data[$key] = $this->parseData($row);

        return $data;
    }

    public function getList4OrderForm($param) {

        if (empty($param['client_code'])
        ||  empty($param['date_from'])
        ||  empty($param['date_to'])
        ) return [];

        $data = $this
        ->where('client_code',      $param['client_code'])
        ->where('print_up_date >=', $param['date_from'])
        ->where('print_up_date <=', $param['date_to'])
        ->where('status = 0')
        ->orderBy('print_up_date','ASC')
        ->findAll();

        return $data;
    }

    public function getDateFromPrintUp($param) {

        $limit_date = (new \App\Models\Service\LimitDate())
        ->getPaymentLimitDate();

        if (!empty($param['print_up_date'])
        &&  !empty($param['client_code'])
        ) {
            $DT = new \Datetime($param['print_up_date']);
            $print_up_date = $DT->format('Y-m-d');

            $data = $this
            ->where('client_code', $param['client_code'])
            ->where('print_up_date', $print_up_date)
            ->first();

            if (!empty($data['id']))
                $limit_date = $data['limit_date'];
        }

        $DT = new \Datetime($limit_date);
        return $DT->format('Y-m-d H:i:s');
    }

    function checkParam($param) {
        $error = [];

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

    public function makeData($param) {

        if (!empty($param['print_up_date_y'])
        ||  !empty($param['print_up_date_m'])
        ||  !empty($param['print_up_date_d'])
        )   $param['print_up_date'] = 
            $param['print_up_date_y'].'-'.
            $param['print_up_date_m'].'-'.
            $param['print_up_date_d'];

        if (!empty($param['limit_date_y'])
        ||  !empty($param['limit_date_m'])
        ||  !empty($param['limit_date_d'])
        )   $param['limit_date'] = 
            $param['limit_date_y'].'-'.
            $param['limit_date_m'].'-'.
            $param['limit_date_d'].' '.$this->limit_hour.':00:00';

        return $param;
    }

    public function parseData($param) {

        if (!empty($param['print_up_date'])) {
            $DT = new \Datetime($param['print_up_date']);
            $param['print_up_date_y'] = $DT->format('Y');
            $param['print_up_date_m'] = $DT->format('n');
            $param['print_up_date_d'] = $DT->format('j');
        }

        if (!empty($param['limit_date'])) {
            $DT = new \Datetime($param['limit_date']);
            $param['limit_date_y'] = $DT->format('Y');
            $param['limit_date_m'] = $DT->format('n');
            $param['limit_date_d'] = $DT->format('j');
        }

        return $param;
    }
}