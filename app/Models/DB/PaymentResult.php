<?php

namespace App\Models\DB;

use CodeIgniter\Model;
//use CodeIgniter\Database\ConnectionInterface;

require_once("/home/xsvx2010092/paygent/module/vendor/autoload.php");

use PaygentModule\System\PaygentB2BModule;



class PaymentResult extends Model
{
//    protected $db;

//    protected $DBGroup = 'local';

    protected $table      = 'payment_result';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id',
        'trading_id',
        'paygent_id',
        'status',
        'result_original'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;    


    public function getFromID($id) {

        if (empty($id)) return [];

        $data = $this->find($id);

        if (empty($data['id'])) return [];

        return $this->parseData($data);
    }

    public function getFromPaymentID($id) {

        if (empty($id)) return [];

        $data = $this
        ->where('trading_id', $id)
        ->orderBy('create_date', 'DESC')
        ->findAll();

        if (empty($data[0]['id'])) return [];

        return $this->parseData($data[0]);
    }

    function parseData($param) {

        if (!empty($param['result_original']) && $param['result_original'] != '{}') {
            $a = json_decode($param['result_original'], true);
            $param = array_merge($param, $a);
            unset($a, $param['result_original']);
        }

        return $param;
    }

    function modPaymentRecord($param = []) {

        if (empty($param['id'])
        &&  empty($param['payment_notice_id'])
        ) return false;

        if (!empty($param['id'])
        &&  empty($param['payment_notice_id'])
        ) {
            $temp = $this->find($param['id']);

            if (empty($temp['id'])) return false;

            $param = json_decode($temp['result_original'], true);
            unset($temp);
        }

        $Payment = new \App\Models\DB\Payment();

        if (!empty($param['payment_type']))
            $type = $Payment
                ->getPaymentTypeText($param['payment_type']);

        if (!empty($param['trading_id'])
        &&  !empty($param['payment_status'])) {

            $data_before = $Payment->find($param['trading_id']);

            $Payment->save([
                'id'        => (int)$param['trading_id'],
                'type'      => $type,
                'status'    => (int)$param['payment_status']
            ]);
            
            $data = $Payment->getFromID($param['trading_id']); // user_id取得
            $data['payment_id'] = $data['id'];

            // ステータスによって処理を変える
            $status = $param['payment_status'];

            $b_result = $Payment->modOrderPoint($data, $data_before['status'], $status);

            if (!$b_result) {

                $a = [];
                $a[] = '処理対象の発注/受注がありません。';
                $a[] = '快適印刷決済ID：'.$param['trading_id'] ?? '';
                $a[] = 'ペイジェントID：'.$param['id'] ?? '';
                $a[] = '決済ステータス：'.$status ?? '不明';

                $p = [
                    'query' => 'id='.$param['trading_id'],
                    'alert_detail' => implode("\n",$a)
                ];
                (new \App\Models\Mail\PaymentAlert())->sendAutomail($p);
            }
        }

        return true;
    }
}