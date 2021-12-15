<?php

namespace App\Controllers;

class Batch extends BaseController
{
    protected $CR = "\n";

    public function test() {
        if (!is_cli()) show_403();

        echo 'ok.'.$this->CR;
    }

    public function db_test() {
        if (!is_cli()) show_403();

        $ProductSet = new \App\Models\DB\ProductSet();
        $data = $ProductSet->find(1);

        if (!empty($data['name_en'])) {
            echo 'DB connect ok.'.$this->CR;
            echo 'name_en: '.$data['name_en'].$this->CR;

        } else {
            echo 'DB connect ok, but data not found.'.$this->CR;
        }
    }

    public function send_report_4_client() {
        if (!is_cli()) show_403();

        $Client = new \App\Models\DB\Client();
        $b = $Client->sendDailyReport();

        echo 'daily report 4 client send.'.$this->CR;
    }

    public function send_mail_buffer() {
        if (!is_cli()) show_403();

        $Config = new \App\Models\Service\Config();

        $number_limit = $Config->getSendMagazinePerMinute();

        $result = '';
        $MailSendlist = new \App\Models\DB\MailSendlist();
        $MailBuffer = new \App\Models\DB\MailBuffer();
        $Magazine = new \App\Models\Mail\Magazine();

        $DT = new \Datetime();
        $now_text = $DT->format('Y-m-d H:i:s');
        echo $now_text.$this->CR;

        $buffer_list = $MailBuffer
        ->where('request_date <=', $now_text)
        ->findAll($number_limit);

        if (empty($buffer_list) || !count($buffer_list)) {
            echo 'buffer is none.'.$this->CR;
            return;
        }

        foreach ($buffer_list as $buffer) {
            
            $param = $MailBuffer->parseData($buffer);
            $b = $Magazine->sendBuffer($param);

            $sendlist = $MailSendlist->find($param['sendlist_id']);
            $sendlist['status'] = $b ? 1 : -1;
            $MailSendlist->save($sendlist);
            
            if ($b)
                $MailBuffer->where('sendlist_id', $param['sendlist_id'])->delete();
            
            $result = $b ? 'send_OK' : 'send_NG';

            echo $result.' ID: '.$param['sendlist_id'].$this->CR;
        }
    }

    public function make_order_sheet($id) {
        if (!is_cli()) show_403();

        if (empty($id) || !is_numeric($id)) return false;

        $Config = new \App\Models\Service\Config();
        $Order = new \App\Models\DB\OrderHistory();

        $data   = $Order->getDetailFromID($id);

        $data['save_file_place'] =
            $Config->getClientOrderSheetPlace($data['product_set']['client_code']);

        ini_set('memory_limit', '2G');
        ini_set("max_execution_time",6000);

        $ExportPDF = new \App\Models\ExportPDF();
        $ExportPDF->order_sheet($data);

        return true;
    }

    public function check_unknown_payment() {
        if (!is_cli()) show_403();

        // Payment->checkResult 実行する前に以下設定を行う
        // ペイジェントモジュールがPHP8非推奨記述を含む＆契約者判断で修正できない為
        ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED & ~E_NOTICE);

        $ids = [];
        $Payment = new \App\Models\DB\Payment();
        $ids = $Payment->getUnknownTypeIDs();

        if (!empty($ids) && count($ids)) {
            $res = $Payment->checkResult(['ids' => $ids, 'b_from_batch' => 1]);

            if (!empty($res['list']) && count($res['list']))
                foreach($res['list'] as $id=>$status)
                    echo 'payment_id '.$id.' status : '.$status.$this->CR;
            
            else echo 'not modified.'.$this->CR;
        } else echo 'not unknown.'.$this->CR;
    }

    public function check_payment_expired() {
        if (!is_cli()) show_403();

        // Payment->checkResult 実行する前に以下設定を行う
        // ペイジェントモジュールがPHP8非推奨記述を含む＆契約者判断で修正できない為
        ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED & ~E_NOTICE);

        $ids = [];
        $Payment = new \App\Models\DB\Payment();
        $ids = $Payment->getWaitingPaymentIDs();

        if (!empty($ids) && count($ids)) {
            $res = $Payment->checkResult(['ids' => $ids]);

            if (!empty($res['list']) && count($res['list']))
                foreach($res['list'] as $id=>$status)
                    echo 'payment_id '.$id.' status : '.$status.$this->CR;
            
            else echo 'not modified.'.$this->CR;
        } else echo 'not waiting.'.$this->CR;
    }

    public function update_order_expired() {
        if (!is_cli()) show_403();

        $DT = new \Datetime();
        $DT->modify('-30 minutes');

        $Order = new \App\Models\DB\OrderHistory();

        $data = $Order
        ->where('status', 10)
        ->where('payment_limit < ', $DT->format('Y-m-d H:i:s'))
        ->findAll(100, 0);

        if (count($data)) {

            foreach($data as $row) {

                $a = explode('　', $row['note']);
                $a[] = '理由：入金期限切れ';
                $note = implode('　', $a);

                $Order->save([
                    'id'        => $row['id'],
                    'status'    => 190,
                    'note'      => $note,
                    'admin_id'  => 0
                ]);                
            }
        }
    }

    public function send_order_mail($status) {

        if (!is_cli()) show_403();

        $Order = new \App\Models\DB\OrderHistory();

        $record = $Order
        ->where('status', $status)
        ->findAll(10, 0);

        if (count($record)) {

            foreach($record as $row) {

                $temp = $row;
                $temp['mode'] = 'detail';
                $temp['b_background'] = true;
                $data = $Order->parseData($temp);

                switch($status) {

                    case 110:
                        $Model = new \App\Models\Mail\PaymentNG();
                        $data = $Model->adjust($data);
                        $Model->sendAutomail($data);
                        $data['payment_id'] = null;
                        break;

                    case 140:
                        $Model = new \App\Models\Mail\PaymentOK();
                        $data = $Model->adjust($data);
                        $Model->sendAutomail($data);
                        break;

                    case 141:
                    case 151:
                        $Model = new \App\Models\Mail\OrderNG();
                        $data = $Model->adjust($data);
                        $Model->sendAutomail($data);
                        break;

                    case 150:
                    case 160:
                        $Model = new \App\Models\Mail\OrderOK();
                        $data = $Model->adjust($data);
                        $Model->sendAutomail($data);
                        break;

                    case 190:
                        $Model = new \App\Models\Mail\OrderExpired();
                        $data = $Model->adjust($data);
                        $Model->sendAutomail($data);
                        break;
                }

                $Order->save([
                    'id'         => $data['id'],
                    'payment_id' => $data['payment_id'],
                    'status'     => $status - 100,
                    'admin_id'   => 0
                ]);
            }
        }
    }
}
