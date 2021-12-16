<?php

namespace App\Models\Service;

//require('/home/xsvx2010092/paygent/module/vendor/autoload.php');
require_once("/home/kaitekihonya/paygent/module/vendor/autoload.php");

use \PaygentModule\System\PaygentB2BModule;

class CallPaygent
{
    function checkResult($id) {

        $ng_result = [
            'result' => 'ng',
            'error_code' => 'xxx',
            'error_detail' => '決済IDがありません'
        ];
        if (empty($id)) return $ng_result;

        $Config = new \App\Models\Service\Config();
        
        $Module = new PaygentB2BModule();
        $Module->init();
        $Module->reqPut('merchant_id',      $Config->getMerchantID());
        $Module->reqPut('telegram_version', '1.0' );
        $Module->reqPut('telegram_kind',    '094');
        $Module->reqPut('trading_id',       $id);
//        $p->reqPut("payment_id", $payment_seq);

        $result = [];
        $temp = $Module->post();

        $b_failed = $Module->getResultStatus();
        $result['result'] = !$b_failed ? 'ok' : 'ng';

        if ($b_failed) { // error
            $result['error_code'] = $Module->getResponseCode();
            $result['error_detail'] = mb_convert_encoding(
                $Module->getResponseDetail(), 'UTF-8', 'sjis-win'
            );
        }

        if($Module->hasResNext())
            $result += $Module->resNext();

        return $result;
    }

    function checkResultFromPaygentID($paygent_id) {

        $ng_result = [
            'result' => 'ng',
            'error_code' => 'xxx',
            'error_detail' => '決済IDがありません'
        ];
        if (empty($paygent_id)) return $ng_result;

        $Config = new \App\Models\Service\Config();
        
        $Module = new PaygentB2BModule();
        $Module->init();
        $Module->reqPut('merchant_id',      $Config->getMerchantID());
        $Module->reqPut('telegram_version', '1.0' );
        $Module->reqPut('telegram_kind',    '094');
//        $Module->reqPut('trading_id',       $id);
        $Module->reqPut("payment_id", $paygent_id);

        $result = [];
        $temp = $Module->post();

        $b_failed = $Module->getResultStatus();
        $result['result'] = !$b_failed ? 'ok' : 'ng';

        if ($b_failed) { // error
            $result['error_code'] = $Module->getResponseCode();
            $result['error_detail'] = mb_convert_encoding(
                $Module->getResponseDetail(), 'UTF-8', 'sjis-win'
            );
        }

        if($Module->hasResNext())
            $result += $Module->resNext();

        return $result;
    }
}