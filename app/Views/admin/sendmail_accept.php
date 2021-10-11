<?php

$subject_admin = '【快適印刷さん】入稿受付'; // 送信しない

$subject_customer = '【快適本屋さん】入稿を受付しました';

//$b_debug = true;

$b_debug = false;

//include_once('../../../config_all. php');
include_once('../../_config_site.php');
include_once('../../php/lib.php');
include_once('_config.php');

foreach($_GET  as $key=>$val) $param[htmlspecialchars($key, ENT_QUOTES)] = $val;
foreach($_POST as $key=>$val) $param[htmlspecialchars($key, ENT_QUOTES)] = $val;

$data_dir = SITE_PATH.'/order/data/';

require_once(DIRECTORY_SITEROOT.'/php/mail_limit.php');

/*if (!$MailLimit->isCookie())
	$param['error'][] = 'ブラウザの設定で、Cookieを有効にしてください。携帯端末をご利用の方は、PC・タブレットまたはスマートフォンからお問合せください。';

if (!$MailLimit->isSendable())
$param['error'][] = '既にお問合せ済みです。時間をおいてから再度お問合せください。';
*/
//$file_dir = $base_dir.'/upload/';
//$file_url = $base_url.'/upload/';

$ignore_keys = [
	'submit'
	,'error'
];
$CR = "\n";

//$MailLimit->initFromName($param['set_id']);
//$MailLimit->initTrafficLogFile($base_dir.'/count_contact.dat');

if (empty($param['id'])) {
	$param['error'][] = '発注IDがありません。';

} else {
	$file = $data_dir.substr($param['id'],0,6).'/'.$param['id'].'.json';
	$param = json_decode(file_get_contents($file), true);
}


// メール送信
if (count($param['error']) == 0) {

	// データファイル保存
//	$file_dir = export_data($param);
//	$param['sheet_url'] = $export_sheet_url.'?id='.$param['id'];

	// 管理者宛メール
	$param['subject'] = $subject_admin;
	
//	$body = file_get_contents('template_admin.cgi');

	$delivery_text = $number_text = '';

	// 拡張テンプレート設定
//	$body_event = file_get_contents('template_event.cgi');
//	$body_other = file_get_contents('template_other.cgi');

	if (!empty($param['number_event_1'])) 
		$number_text .= '直接搬入1に '
		.$param['number_event_1'].'部'.$CR;

	if (!empty($param['number_event_2'])) 
		$number_text .= '直接搬入2に '
		.$param['number_event_2'].'部'.$CR;

	if (!empty($param['number_kaiteki'])) 
		$number_text .= '快適本屋さんOnlineに '
		.$param['number_kaiteki'].'部'.$CR;

	if (!empty($param['number_other'])) 
		$number_text .= 'その他発送先に '
		.$param['number_other'].'部'.$CR;

	if (!empty($param['number_home'])) 
		$number_text .= '自宅に '
		.$param['number_home'].'部'.$CR;

	$param['number_text'] = $number_text;
/*
	if (!empty($param['number_event_1'])) {

		$temp = $body_event;
		foreach($param as $key=>$val) {

			if (strpos($key, 'event_1') !== false) {
				$kkey = str_replace('event_1', '', $key);
				$temp = str_replace('['.$kkey.']', $val, $temp);
			}
		}
		$delivery_text .= '■直接搬入1'.$CR.$CR;
		$delivery_text .= $temp.$CR.$CR;
	}

	if (!empty($param['number_event_2'])) {

		$temp = $body_event;
		foreach($param as $key=>$val) {

			if (strpos($key, 'event_2') !== false) {
				$kkey = str_replace('event_2', '', $key);
				$temp = str_replace('['.$kkey.']', $val, $temp);
			}
		}
		$delivery_text .= '■直接搬入2'.$CR.$CR;
		$delivery_text .= $temp.$CR.$CR;
	}

	if (!empty($param['number_other'])) {

		$temp = $body_other;
		foreach($param as $key=>$val) {

			if (strpos($key, 'other') !== false) {
//				$kkey = str_replace('other', '', $key);
				$temp = str_replace('['.$key.']', $val, $temp);
			}
		}
		$delivery_text .= '■その他'.$CR.$CR;
		$delivery_text .= $temp.$CR.$CR;
	}

	$param['delivery_text'] = $delivery_text;

	// メールテンプレート流し込み＆送信	
	foreach($param as $key=>$val)
		$body = str_replace('['.$key.']', $val, $body);

    $body .= "---------------------------------------------------------------\n";
    $body .= "Processed       : ".date("Y/m/d (D) H:i:s")."\n";
    $body .= "Server-Name     : ".$_SERVER['SERVER_NAME']."\n";
    $body .= "Script-Name     : ".$_SERVER['PHP_SELF']."\n";
    $body .= "HTTP-Referer    : ".$_SERVER['HTTP_REFERER']."\n";
    $body .= "HTTP-User-Agent : ".$_SERVER['HTTP_USER_AGENT']."\n";
    $body .= "Remote-Addr     : ".get_referer_ip()."\n";
    $body .= "---------------------------------------------------------------\n";

	$result = send_mail([
		 'from'		=> $param['mail_address']
		,'to'		=> $admin_mail_address
		,'bcc'		=> $bcc_mail_address_2admin
		
		,'subject'	=> $param['subject']
		,'body'		=> $body
//		,'file'		=> $param['make_filename']
//		,'file_dir'	=> $file_dir
	]);
	
	if (!$result) $param['error'][] =
	'フォーム送信できませんでした。何度もこのエラーが出る場合はお問合せフォームか、'
	.$admin_mail_address.'までお問合せください。';
	
	else {
		$MailLimit->modInfo(); // 正常に送信したことを記録する（連投防止）
		$MailLimit->destroySessionKey(); // セッションキー解除
		session_destroy();
	}
*/
}

if (count($param['error']) == 0) {$complete_param = [];

	// 申請者宛メール（自動返信）
	
	$body = file_get_contents('template_customer.cgi');

	foreach($param as $key=>$val)
		$body = str_replace('['.$key.']', $val, $body);

	$result = send_mail([
		 'from'		=> $admin_mail_address
		,'to'		=> $param['mail_address']
		,'bcc'		=> $bcc_mail_address_2user
		
		,'subject'	=> $subject_customer
		,'body'		=> $body
//		,'file'		=> $param['make_filename']
//		,'file_dir'	=> $file_dir
	]);
	
	// エラーではなく完了画面へ遷移
	$complete_param = [];
	if (!$result) $complete_param['auto_reply'] = 'ng';
/*
	$MailLimit->modInfo(); // 正常に送信したことを記録する（連投防止）
	$MailLimit->destroySessionKey(); // セッションキー解除
	session_destroy();
*/
}

function send_mail($array) { $m = $array; unset($array);

	global $b_debug;

	if ($b_debug) var_dump($m);
	
	$orgEncoding = mb_internal_encoding();
//	$mailEncoding = 'ISO-2022-JP';
	$mailEncoding = 'UTF-8';
	$CR = "\n";
	
	$header  = 'From: '.$m['from'].$CR;

	if (!empty($m['cc'])) $header .= 'Cc: '.$m['cc'].$CR;
	
	if (!empty($m['bcc'])) $header .= 'Bcc: '.$m['bcc'].$CR;
	
//	$header .= 'Content-Type: text/plain; charset="'.$mailEncoding.'"'.$CR;
	
	if (isset($m['file'])) {

		// マルチパートメールの区切りIDを設定
		$boundary = md5(uniqid(mt_rand()));
		
		$header .= 'Content-Type: multipart/mixed;'.$CR
				."\t".'boundary="'.$boundary.'"'.$CR;
		
		$file = file_get_contents($m['file_dir'].$m['file']);
		
		$f_encoded = chunk_split(base64_encode($file)); //エンコードして分割

		$m['body'] =
			"This is a multi-part message in MIME format.\n\n"
			."--$boundary\n"
			."Content-Type: text/plain; charset=\"$mailEncoding\"\n\n"
			
			.$m['body'].

			 "\n--$boundary\n"
			."Content-Type: image/".substr($m['file'], -3).";\n"
			."\tname=\"".$m['file']."\"\n"
			."Content-Transfer-Encoding: base64\n"
			."Content-Disposition: attachment;\n"
			."\tfilename=\"".$m['file']."\"\n\n"
			.$f_encoded
			."\n"
			."--$boundary--";
		
	} else {
		$header .= 	'Content-Type: text/plain; charset='.$mailEncoding.$CR.$CR;
	}

	mb_internal_encoding($mailEncoding);
	
	$subject = mb_encode_mimeheader(
		mb_convert_encoding($m['subject'], $mailEncoding, $orgEncoding)
		,$mailEncoding);
	
	mb_internal_encoding($orgEncoding);
	
	$mailbody = mb_convert_encoding($m['body'], $mailEncoding, $orgEncoding);
	
	return mail($m['to'], $subject, $mailbody, $header);
}

if (count($param['error']) > 0)
	header('location: index.php?'.http_build_query($param));


$p = [
	'id'		=> $param['id']
	,'mode'		=> 'mail_send_on'
	,'data_dir' => $data_dir
];

$query = mod_orderStatusList($p); // id, mode, data_dir

$complete_param['result'] = '受付メールを送信しました（発注ID：'.$param['id'].'）';
$complete_param['mode'] = 'payment';

$header_query = count($complete_param) ? '?'.http_build_query($complete_param) : '';

if (!$b_debug)
	header('location:list.php'.$header_query);

/*
function export_data($param) {

	global $base_dir;

	if (isset($param['submit'])) unset($param['submit']);
	if (isset($param['error'])) unset($param['error']);

	$file_dir = $base_dir.'/data/'.substr($param['id'],0,6);
 
	if (!file_exists($file_dir)){

		if (mkdir($file_dir, 0777)){
			chmod($file_dir, 0777);
		}
	}	

	return file_put_contents(
		$file_dir.'/'.$param['id'].'.json'
		,json_encode($param)
	) ? $file_dir.'/' : false;
}
*/

?>