<?php

//include_once(SITE_PATH.'/../_config_all. php');
//include_once(SITE_PATH.'/_config_site.php');

//include_once(SITE_PATH.'/php/lib.php');

$base_dir = dirname(__FILE__);

$base_url = 'http://print.l/contact';

const PRINT_SET_NAME = '';



// ----------------------------------------------

// メール転送先
$bcc_mail_address_2admin = 'info@taiyoushuppan.jp';

$admin_mail_address = 'order@kaitekiinsatsu.com';


$key2name = [

 'contact_type'			=> 'お問合せの種類'
,'id'				    => '発注no.'

,'mail_address'			=> 'メールアドレス'

,'real_name'			=> '氏名'
,'real_name_kana'		=> '氏名カナ'

,'tel'					=> '電話番号'
,'tel_range'			=> '連絡可能時間帯'

,'payment_date'			=> '入金日'
,'payment_date_y'		=> '入金日(年)'
,'payment_date_m'		=> '入金日(月)'
,'payment_date_d'		=> '入金日(日)'

,'payment_type'			=> '入金方法'
,'payment_name'			=> '入金名義人名'

,'detail'				=> 'お問合せ内容'
];

foreach($key2name as $key=>$val)
    if (empty($param[$key])) $param[$key] = '';

function init_delivery($i) {
/*
	global $key2name_delivery, $now_y;

	$a = [];
	$k = 'delivery_'.$i;

	foreach($key2name as $key => $val)
		if (substr($key,0,1) == '_')
			$a[$k.$key] = '';

	$a[$k.'_event_date_y'] = $now_y;
	$a[$k.'_type'] = '自宅';

	return $a;
*/
}

$necessary = [ // 必須項目
	 'mail_address'
	,'real_name'
	,'real_name_kana'
//	,'tel'
	,'detail'
];

$deny_url_key = [ // URL入力禁止
	 'real_name'
	,'real_name_kana'
	,'payment_name'
	,'tel_range'
];

$max_input_length = [ // 最大文字数

'real_name'				=> 100

,'real_name'			=> 20
,'real_name_kana'		=> 20

,'mail_address'			=> 256

,'tel'					=> 20
,'tel_range'			=> 100

,'payment_date_y'	=> 4
,'payment_date_m'	=> 2
,'payment_date_d'	=> 2

,'auth_key' => 256
];


$DT = new DateTime();
$now_y = (int)$DT->format('Y');

$now_datetext = $DT->format('Y/n/j');

$select_y = range($now_y - 100, $now_y + 1);
$select_m = range(1,12);
$select_d = range(1,31);


$fixdata = [];

$fixdata['print_set_name'] = PRINT_SET_NAME;
/*
$select = [];

$select['contact_type'] = [
	'入稿発注について','お支払いについて','ポイント移動申請','システム不具合','その他質問'];

$select['payment_type'] = [
	'Paypal','銀行振込'];
*/


// check preview params

$preview = [

	'contact_type'
   ,'id'
   ,'mail_address'
   ,'real_name'
   ,'real_name_kana'
   ,'tel'
   ,'tel_range'

   ,'payment_date'
   ,'payment_type'
   ,'payment_name'
   ,'detail'
];

$not_preview = [
	'payment_date_y'
   ,'payment_date_m'
   ,'payment_date_d'
];


$NGword_data = '
980585.com
115458.com
QQ邮箱
您好
自动回复
';


$NGwords = explode("\n", str_replace("\r", "", $NGword_data));

foreach ($NGwords as $key => $val)
	if (!strlen($val)) unset($NGwords[$key]);

function is_include_NGword($text) {
		
		global $NGwords;
		
		$b = false;
		
		foreach ($NGwords as $needle) {
			
			$b |= (
				mb_strpos($text, $needle, 0, 'UTF-8') !== false);
		}
		return $b;
	}
	
function is_url($url) {
	return false !==
		filter_var($url, FILTER_VALIDATE_URL)
	&&	preg_match('@^https?+://@i', $url);
}

function is_mail($email) { $check_dns = true;
	
	switch (true) {
		case false === filter_var($email, FILTER_VALIDATE_EMAIL):
		case !preg_match('/@(?!\[)(.++)\z/', $email, $m):
			return false;

		case !$check_dns:
		case checkdnsrr($m[1], 'MX'):
		case checkdnsrr($m[1], 'A'):
		case checkdnsrr($m[1], 'AAAA'):
			return true;

		default:
			return false;
	}
}

function get_referer_ip() {

	$from_ip_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	return $from_ip_list[0];
}

function join_date($param, $key = null) {

	if (empty($key)) {

		if (empty($param['y'])
		||	empty($param['m'])
		||	empty($param['d'])
		) return NULL;
	
		return	 $param['y']
			.'/'.$param['m']
			.'/'.$param['d'];

	} elseif (is_string($key)) {

		if (empty($param[$key.'_y'])
		||	empty($param[$key.'_m'])
		||	empty($param[$key.'_d'])
		) return NULL;
	
		return	 $param[$key.'_y']
			.'/'.$param[$key.'_m']
			.'/'.$param[$key.'_d'];
	}

	return NULL;
}

function split_date($date) {

	if (mb_substr_count($date, '/', 'UTF-8') < 2)
		return ['','',''];

	return explode('/', $date);
}


// echo shortHash($string, 7); // 7桁のハッシュ値

function shortHash($string, $len=6, $algo='sha512')
{
	$hash   = hash($algo, $string);  // ハッシュ値の取得
	$number = hexdec($hash);         // 16進数ハッシュ値を10進数
	$result = dec62th($number);      // 62進数に変換

	return substr($result, 0, $len); //$len の長さぶん抜き出し
}

function dec62th($number)
{
	// 0-9,a-z,A-Z の 62 文字
	$char = array_merge(
		range('0', '9'),
		range('a', 'z'),
		range('A', 'Z')
	);
	
	return decNth($number, $char);
}

function decNth($number, array $char)
{
	$base   = count($char);
	$result = "";

	while ($number > 0) {
		$result = $char[ fmod($number, $base) ] . $result;
		$number = floor($number / $base);
	}

	return empty($result) ? 0 : $result;
}
