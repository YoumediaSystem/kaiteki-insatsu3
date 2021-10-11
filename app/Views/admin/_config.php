<?php

$b_auth = (!empty($_COOKIE['token']) || auth($_COOKIE['token']));

$b_public  = (strpos($_SERVER['REQUEST_URI'], 'index.php') !== false);
$b_public |= (strpos($_SERVER['REQUEST_URI'], 'login.php') !== false);

if (!$b_auth &&	!$b_public) header('location: index.php');

function is_payed($status_code) {
	return strpos($status_code,'payment') !== false;
}

function is_dataChecked($status_code) {
	return strpos($status_code,'data_ok') !== false;
}

function is_sendedOrderMail($status_code) {
	return strpos($status_code,'mail_send') !== false;
}

function is_exportSheet($status_code) {
	return strpos($status_code,'export_sheet') !== false;
}

function putStatusText($status_code) {

	// payment,mail_send,export_sheet

	$t = '';

	if (is_payed($status_code)) {

		$t .= '入金済';

		$t .= is_sendedOrderMail($status_code) ? '、受付連絡済' : '';
		$t .= is_exportSheet($status_code) ? '、発注書出力済' : '';

	} else $t .= '未入金';

	return $t;
}


function auth($token) {

	$id		= 'taiyoushuppan';
	$pass	= 'nakapanda7619';
	$org_hash	= hash('SHA256', $id.'_'.$pass);
	$org_token	= hash('SHA256', $org_hash);

	return $token == $org_token;
}

function login($id, $pass) {

	$hash	= hash('SHA256', $id.'_'.$pass);
	$token	= hash('SHA256', $hash);

	$b_auth = false;
	if (auth($token)) {

		setcookie('token', $token, get_expire());

		$b_auth = true;
	}

	return $b_auth;
}

function get_expire() {

	$DT = new Datetime();
	$DT->setTimezone(new DateTimezone('Asia/Tokyo'));
	$DT->modify('+7days');
	$time = (int)$DT->format('U');

	return $time;
}

function mod_auth() {

	if (!empty($_COOKIE['token']))
		setcookie('token', $_COOKIE['token'], ['expires' => get_expire()]);
}

function mod_orderStatusList($param) { // id, mode, data_dir

	$query = $error = [];

	$file_place = 'order_status_list.json';

	if (empty($param['data_dir']))
		$error[] = 'ファイルを開けません';
	
	else {
		$file_place = $param['data_dir'].$file_place;

		if (!file_exists($file_place))
			$error[] = 'ファイルを開けません';
	}

	if (empty($param['id']))
		$error[] = 'IDを指定してください';

	if (empty($param['mode']))
		$error[] = '更新内容を指定してください';

	if (count($error) == 0) {

		$id			= $param['id'];
		$mode		= $param['mode'];

		$status_list = json_decode(file_get_contents($file_place), true);

		$status = (!empty($status_list[$id]))
			? $status_list[$id]['status_code'] : '';

		$a = explode(',',$status);

		if ($mode == 'payment_on') {
			$a[] = 'payment';
			$query['result'] = $id.'を入金済に変更しました。';
		}

		if ($mode == 'payment_off') {
			$a = array_diff($a,['payment']);
			$query['result'] = $id.'を未入金に戻しました。';
		}

		if ($mode == 'mail_send_on') {
			$a[] = 'mail_send';
			$query['result'] = $id.'を受付連絡しました。';
		}

		if ($mode == 'mail_send_off') {
			$a = array_diff($a,['mail_send']);
			$query['result'] = $id.'を受付未連絡に戻しました。';
		}

		if ($mode == 'export_sheet_on') {
			$a[] = 'export_sheet';
			$query['result'] = $id.'の発注書を出力しました。';
		}

		if ($mode == 'export_sheet_off') {
			$a = array_diff($a,['export_sheet']);
			$query['result'] = $id.'を発注書未出力に戻しました。';
		}

		$a = array_diff($a,['',NULL]);

		$status_list[$id]['status_code'] = implode(',', array_unique($a));

		file_put_contents($file_place,
			json_encode($status_list, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)
		);

	} else $query['error'] = $error;

	return $query;
}