<?php

//const PRINT_SET_NAME = '快適スタンダードセット';

// ----------------------------------------------

if (isset($_REQUEST['set_id'])) {
	$temp = preg_replace('/[^0-9A-Za-z_]/','',$_REQUEST['set_id']);
	if (is_dir($temp)) $param['set_id'] = $temp;

} else {
	$param['set_id'] = 'standard';
}

$b_standard = is_standard($param['set_id']);

$b_not_trust = is_notTrust($param['set_id']);

$order_version = getOrderVersion($param['set_id']);



include_once('../_config_site.php');

include_once(SITE_PATH.'/php/lib.php');

//$base_dir = __DIR__;
$base_dir = dirname(__FILE__);

$base_url = str_replace(
	DIRECTORY_SITEROOT
   ,'https://kaitekiinsatsu.com'
   ,$base_dir
);

// 外税計算
$add_tax = false;

// 発注書出力URL
$export_sheet_url = __DIR__.'/admin/export_sheet.php';

// メール転送先
$bcc_mail_address_2admin = 'info@taiyoushuppan.jp';


// 早期締切リスト取得
$file_place = SITE_PATH.'/config/early_limit.csv';

$csv = getCSVfile($file_place);

$early_limit = [];
$early_limit_event = [];
$early_limit_date = [];
$b_first = true; $i = 0;
foreach($csv as $row) {

	if ($b_first) {
		$a = $row;
		$b_first = false;

	} else {
		$early_limit[$i] = [];
		foreach($a as $ii=>$key)
			$early_limit[$i][$key] = $row[$ii];

		$early_limit_event[$i] = $early_limit[$i]['event'];
		$early_limit_date[$early_limit_event[$i]] = $early_limit[$i]['print_up_limit'];
		$i++;
	}
}



function getOrderVersion($set_id) {

	$ver = 1;

	if (strpos($set_id, '_trust')		!== false
	||	strpos($set_id, '_delivery')	!== false
	||	strpos($set_id, '_both')		!== false
	) $ver = 2;

	return $ver;
}

function is_standard($set_id) {

	$b = strpos($set_id, 'standard') !== false;

	return $b;
}

function is_notTrust($set_id) {

	$b = strpos($set_id, 'standard_delivery') !== false;

	return $b;
}

?>