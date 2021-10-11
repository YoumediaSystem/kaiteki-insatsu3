<?php

namespace App\Models\DB;

use CodeIgniter\Model;
//use CodeIgniter\Database\ConnectionInterface;

class Payment extends Model
{
    protected $statusName = [
        10 => '入金確認待ち'
       ,12 => '期限切れ'
       ,15 => '期限切れ'
       ,40 => '入金済'
       ,43 => '速報検知済'
       ,98 => '決済未成立'
       ,99 => '決済キャンセル'
       ,-1 => '削除'
   ];

//    protected $db;

//    protected $DBGroup = 'local';

    protected $table      = 'payment';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'user_id',
        'type',
        'amount',
        'fee',
        'point_get',
        'point_use',
        'note',
        'limit_date',
        'status',
        'ex'
    ];

    protected $dateFields = [
        'limit_date'
    ];

    protected $exFields = [
        'paygent_id'
        ,'url'
        ,'error_code'
        ,'error_detail'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    function getStatusNameArray() {
        return (array)$this->statusName;
    }

    public function getPaymentTypeText($code) {

        $typelist = [
            '01' => 'atm',
            '02' => 'card',
            '03' => 'cvs',
            '05' => 'bank'
        ];

        return $typelist[$code] ?? 'none';
    }

    public function getPaymentTypeName($type) {

        if (preg_match("/^[0-9]+$/", $type))
            $type = $this->getPaymentTypeText($type);

        $typelist = [
            'point' => '全額ポイント決済',
            'atm'   => 'ATM決済',
            'card'  => 'カード決済',
            'cvs'   => 'コンビニ決済',
            'bank'  => '銀行ネット決済'
        ];

        return $typelist[$type] ?? '不明';
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

    public function getLastPaymentID($user_id) {

        $data = $this
        ->where('user_id', $user_id)
        ->where('status', 10)
        ->orderBy('create_date', 'DESC')
        ->findAll();

        $data2 = [];
        if (!empty($data[0]['id']))
            $data2 = $this->parseData($data[0]);

        return $data2;
    }

    public function isAbortedPayment($user_id) {

        $count = $this
        ->where('user_id', $user_id)
        ->where('status', 10)
        ->like('ex', 'url', 'both')
        ->notLike('ex', 'paygent_id', 'both')
        ->countAllResults();

        return !empty($count);
    }

    public function isWaitingBankPayment($user_id) {

        $DT = new \DateTime();
        $DT->modify('+1 day');

        $count = $this
        ->where('user_id', $user_id)
        ->where('status', 10)
        ->where('type', 'bank')
        ->where('limit_date <', $DT->format('Y-m-d H:i:s'))
        ->countAllResults();

        return !empty($count);
    }

    // 15分以上前かつペイジェントID未明の決済を取得（バッチ処理用）

    public function getUnknownTypeIDs() {

        $DT = new \Datetime();
        $DT->modify('-20 minutes'); // DB登録日時+20分後を対象（逆算）

        $data = $this
        ->where('status', 10)
        ->where('type', 'none')
        ->where('create_date <', $DT->format('Y-m-d H:i:s'))
        ->findAll();

        $result = [];
        if (!empty($data[0]['id']))
            foreach($data as $row)
                $result[] = $row['id'];

        return $result;
    }

    // 決済期限過ぎた決済IDを取得（バッチ処理用）

    public function getWaitingPaymentIDs() {

        $DT = new \Datetime();
        $DT->modify('-1 hour'); // 決済期限+1時間後を対象（逆算）

        $data = $this
        ->where('status', 10)
        ->where('limit_date <', $DT->format('Y-m-d H:i:s'))
        ->findAll();

        $result = [];
        if (!empty($data[0]['id']))
            foreach($data as $row)
                $result[] = $row['id'];

        return $result;
    }

    public function getList4user($user_id) {

        if (empty($user_id)) return [];

        $data = $this
        ->where('user_id', $user_id)
        ->where('status >=', 0)
        ->findAll();

        if (empty($data) || !count($data)) return [];

        $result = [];
        foreach($data as $order)
            $result[] = $this->parseData($order);

        return $result;
    }

    function getPaymentLimit($param) {

        $OrderHistory = new \App\Models\DB\OrderHistory();
        $limit_date = '';

        foreach($param['payment_order'] as $order_id) {

            $data = $OrderHistory->getFromID($order_id);

            if (empty($limit_date) || $data['payment_limit'] < $limit_date)
                $limit_date = $data['payment_limit'];
        }

        $DT = new \Datetime();
        $DT_limit = new \Datetime($limit_date);
        $DT_limit_0 = new \Datetime($DT_limit->format('Y-m-d').' 00:00:00');
        $diff = ($DT_limit->getTimestamp() - $DT->getTimestamp());

        $diff_min = round($diff / 60, 0, PHP_ROUND_HALF_DOWN);
        $border_minutes = 60 * (24 + 10); // ※10時締切の場合

        // 決済代行会社システムの仕様上、指定しない方のパラメータはゼロではなく空文字にする
        if ($border_minutes < $diff_min) {
            $diff2 = ($DT_limit_0->getTimestamp() - $DT->getTimestamp());
            $param['payment_term_day'] = round($diff2 / 86400, 0, PHP_ROUND_HALF_DOWN);
            $param['payment_term_min'] = '';

            $DT_limit_0->modify('-1 second');
            $param['limit_date'] = (string)$DT_limit_0->format('Y-m-d H:i:s');

        } else {
            $param['payment_term_min'] = (int)$diff_min;
            $param['payment_term_day'] = '';
            $param['limit_date'] = (string)$limit_date;
        }
        return $param;
    }

    function adjustBeforePayment($param) { // 全額ポイント以外、決済代行会社用のパラメータに変換する

        if (empty($param['type']) || $param['type'] != 'point') {

//            $p = [];
            $p = $param;

            $p['trading_id']            = $param['id'];// payment_id
            $p['id']                    = $param['amount'];
            $p['payment_type']          = $this->getUsablePaymentType($param);

            $Config = new \App\Models\Service\Config();
            $p['payment_link_url']      = $Config->getPaymentLinkURL();
            $p['seq_merchant_id']       = $Config->getMerchantID();
            $p['return_url']            = $Config->getReturnURL();
            $p['stop_return_url']       = $Config->getStopReturnURL();
            $p['fix_params']            = $Config->getFixParams4Payment();
            $p['finish_disable']        = 1; // ペイジェント側の決済完了画面を表示しない
            $p['payment_class']         = 0; // 1回払いのみ
            $p['use_card_conf_number']  = 1; // カード確認番号使用あり
            $p['stock_card_mode']       = 1; // カード預かり機能を有効にする（任意で）
            $p['sales_flg']             = 1; // オーソリ直後に自動で売上確定処理する
            $p['payment_detail']        = 'オンライン印刷発注';
            $p['payment_detail_kana']   = 'ｵﾝﾗｲﾝｲﾝｻﾂﾊｯﾁｭｳ';
            $p['merchant_name']         = '快適印刷さん';
            $p['merchant_name_kana']    = 'ｶｲﾃｷｲﾝｻﾂｻﾝ';

            $p['customer_family_name']        =
                mb_convert_kana($param['userdata']['sei'] ?? '', 'A', 'UTF-8');
            $p['customer_name']               =
                mb_convert_kana($param['userdata']['mei'] ?? '', 'A', 'UTF-8');
            $p['customer_family_name_kana']   =
                mb_convert_kana($param['userdata']['sei_kana'] ?? '', 'k', 'UTF-8');
            $p['customer_name_kana']          =
                mb_convert_kana($param['userdata']['mei_kana'] ?? '', 'k', 'UTF-8');
            $p['customer_tel']                = $param['userdata']['tel'] ?? '';
            $p['customer_id']                 = $param['userdata']['id'] ?? '';
            $p = $this->getPaymentHash($p);

        } else {
            $p['payment_link_url']  = '/payment/complete';
        }
        return $p;
    }

    function getPaymentHash($param) { // 注意：adjustBeforePaymentの後に呼び出す

        $param['hash_key'] = (new \App\Models\Service\Config())->getHashKey4Payment();
/*
        if (!empty($param['payment_type']))
            $param['payment_type'] = $this->getUsablePaymentType($param);
*/
        $org_str =
            ($param['trading_id'] ?? ''). //(payment)id
            ($param['payment_type'] ?? '').
            ($param['fix_params'] ?? '').
            ($param['id'] ?? '').//amount
            ($param['seq_merchant_id'] ?? '').
            ($param['payment_term_day'] ?? '').
            ($param['payment_term_min'] ?? '').
            ($param['payment_class'] ?? '').
            ($param['use_card_conf_number'] ?? '').
            ($param['customer_id'] ?? '').//user_id
            ($param['threedsecure_ryaku'] ?? '').
            ($param['hash_key'] ?? '');

        $hash_str = hash("sha256", $org_str);

        // create random string
        $rand_str = "";
        $rand_char = ['a','b','c','d','e','f','A','B','C','D','E','F','0','1','2','3','4','5','6','7','8','9'];
        for($i=0; ($i<20 && rand(1,10) != 10); $i++){
            $rand_str .= $rand_char[rand(0, count($rand_char)-1)];
        }
        $hash = $hash_str.$rand_str;

        $param['org_str'] = $org_str;
        $param['hash'] = $hash;

        return $param;
    }

    function getUsablePaymentType($param) {

        $type =
            (empty($param['payment_term_day'])
        &&  !empty($param['payment_term_min'])
        ) ? '02,05' : '';

        return $type;
    }


    function adjustParam($param) {

        $lib = new \App\Models\CommonLibrary();

        if (empty($param['type'])) $param['type'] = 'none';

        if (empty($param['status'])) $param['status'] = '10';

        if (empty($param['amount']) && !empty($param['point_use'])) {
            $param['type'] = 'point'; // 全額ポイント
            $param['status'] = 40;
            
        } else {
            $param['limit_date'] = '';
        }

        return $param;
    }

    function checkParam($param) {

        $error = [];

        $OrderHistory = new \App\Models\DB\OrderHistory();

        $order_list = $OrderHistory->getPaymentOrder($param['payment_order']);

        if (count($order_list) != count($param['payment_order'])) {
            $error[] = '支払期限切れの発注が含まれています。';
        }

        return $error;
    }

    function adjustParamAdmin($param) {

        if (!empty($param['paygent_id'])) {
            $param['paygent_id'] = preg_replace('/[^0-9]/', '', $param['paygent_id']);
        }

        return $param;
    }

    function checkParamAdmin($param) {

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

        if (!empty($param['ex'])
        &&  !in_array($param['ex'], ['[]','{}','null','NULL'])
        ) {
            $a = json_decode($param['ex'], true);
            $param = array_merge($param, $a);
            unset($a, $param['ex']);
        }

        $param['type_name'] = $this->getPaymentTypeName($param['type'] ?? '');

        $param['status_name'] = $this->statusName[$param['status']] ?? '';

        if (!empty($param['mode']) && $param['mode'] == 'detail') {

            $User = new \App\Models\DB\User();
            $param['userdata'] = $User->getFromID($param['user_id']);

            $OrderHistory = new \App\Models\DB\OrderHistory();
            $param['order'] = $OrderHistory->getFromPaymentID($param['id']);
        }

        return $param;
    }

    function checkResult($param) {

        if (empty($param['id'])
        &&  empty($param['paygent_id'])
        &&  empty($param['ids'])
        ) return [];

        $CallPaygent = new \App\Models\Service\CallPaygent();

        $rr = [];
        if (!empty($param['paygent_id']))
            $rr[$param['paygent_id']] =
                $CallPaygent->checkResultFromPaygentID($param['paygent_id']);

        elseif (!empty($param['id']))
            $rr[$param['id']] = $CallPaygent->checkResult($param['id']);

        else {
            foreach($param['ids'] as $id)
                $rr[$id] = $CallPaygent->checkResult($id);
        }
        
        if (!count($rr)) return [];

        $DT_now = new \Datetime();

        $r_param = ['list' => []];
        foreach($rr as $id=>$res) {

            $data = $this->find($id);
            $data = $this->parseData($data);
            $data['payment_id'] = $id;

            $DT_limit = new \Datetime($data['limit_date']);
            $DT_limit->modify('+1 hour');

            $b_ok   = (!empty($res['result']) && $res['result'] == 'ok');
            $status = $res['payment_status'] ?? 0;
            $type   = $res['payment_type'] ?? '00';

            if ($res['error_code'] == 13001 && $DT_limit < $DT_now) {
                $b_ok = true;
                $status = 98;
            }

            if (!$b_ok) {
                $r_param['error'] = ['['.$res['error_code'].']'.$res['error_detail']];

                if ($res['error_code'] == 13002 && !empty($param['b_from_batch'])) {

                    $a = [];
                    $a[] = '処理対象の決済IDで、複数の決済が行われています。';
                    $a[] = 'ペイジェントIDを手動登録してください。';
                    $a[] = '快適印刷決済ID：'.$param['id'] ?? '';

                    $p = [
                        'query' => 'id='.$param['id'],
                        'alert_detail' => implode("\n",$a)
                    ];
                    (new \App\Models\Mail\PaymentAlert())->sendAutomail($p);
                }
            }
            
            else {
                $data['ex'] = [];

                if (!empty($res['payment_id']))
                    $data['ex']['paygent_id'] = $res['payment_id'];

                if (!empty($res['cvs_code']))
                    $data['ex']['cvs_code'] = $res['cvs_code'];

                $this->save([
                    'id'        => $id,
                    'status'    => $status,
                    'type'      => $this->getPaymentTypeText($type),
                    'ex'        => json_encode($data['ex'])
                ]);

                $this->modOrderPoint($data, $data['status'], $status);
/*
                if (in_array($status, [40,43])
                &&  !in_array($data['status'], [40,43])) {
                    (new \App\Models\DB\OrderHistory())->completePayment($data);
                    (new \App\Models\DB\Point())->setAddFromPayment($data);
                }
                if (in_array($status, [12,15,98,99])) {
                    (new \App\Models\DB\OrderHistory())->cancelPayment($data);
                    (new \App\Models\DB\Point())->resetUseFromPayment($data);
                }
*/
                $r_param['list'][$id] = $status;
            }
        }

        return $r_param;
    }

    public function modOrderPoint($payment_data, $status_before, $status_now) {

        if (empty($payment_data['id'])
        ||  empty($payment_data['user_id'])
        ||  empty($status_before)
        ||  empty($status_now)
        ) return false;

        $Order = new \App\Models\DB\OrderHistory();
        $Point = new \App\Models\DB\Point();
        $b_result = true;

        if (in_array($status_now, [40,43])
        &&  !in_array($status_before, [40,43])) {
            $b_result =
            $Order->completePayment($payment_data);
            $Point->setAddFromPayment($payment_data);
        }
        if (in_array($status_now, [12,15,98,99])
        &&  !in_array($status_before, [40,43])) {
            $Order->cancelPayment($payment_data);
            $Point->resetUseFromPayment($payment_data);
        }
        return $b_result;
    }
}