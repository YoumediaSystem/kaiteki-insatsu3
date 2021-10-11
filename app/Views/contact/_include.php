<?php
/*
foreach($_POST as $key=>$val) {
	
	if ($key != 'file')
		$param[htmlspecialchars($key, ENT_QUOTES)] = $val;
}
*/

// 入力値調整

$param['payment_date']	= join_date($param, 'payment_date');

$param['tel']			= preg_replace('/-|－|ー|―/u', '', $param['tel']);

$encode_param = [
 'tel'
];

foreach($encode_param as $key) {

	if (!empty($param[$key]))
		$param[$key] = mb_convert_kana($param[$key], 'KVa');
}

// 入力チェック

$param['error'] = [];

foreach($necessary as $key) { // 必須入力チェック
	if(empty($param[$key]))
		$param['error'][] = $key2name[$key].'を入力してください。';
}

foreach($max_input_length as $key=>$val) { // 文字数上限チェック
	if(!empty($param[$key]) && $val < mb_strlen($param[$key],'UTF-8'))
		$param['error'][] = $key2name[$key].'は'.$val.'文字以内で入力してください。';
}

foreach($deny_url_key as $key) { // URL混入チェック
	if(!empty($param[$key]) && is_url($param[$key]))
		$param['error'][] = $key2name[$key].'に送信できない文字が含まれています。';
}

$param['error'] = array_merge($param['error'], check_param($param));


function check_param($param) {
	
	global $key2name;
	
	$error = [];

	// 電話番号
	$key = 'tel'; // 連絡先
	if (!empty($param[$key]) && in_array($param['contact_type'], ['入金連絡','システム不具合']))
		$error[] = $key2name[$key].'を入力してください。';

	// メールアドレス
	$key = 'mail_address'; // 連絡先
	if (!empty($param[$key]) && !is_mail($param[$key]))
		$error[] = $key2name[$key].'を確認してください。';

	return $error;
}

?>