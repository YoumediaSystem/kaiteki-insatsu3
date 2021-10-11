<?php

const SITE_NAME = '快適印刷さん Online入稿';

const SITE_SINCE = 2021;

const SITE_ROOT = '';

const SITE_TEL = '03-5816-1062';

const SITE_CONTACT_TIME = '平日 11:00 ～ 18:00';

define('SITE_PATH', dirname(__FILE__));



//mb_language('Japanese');
mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');

ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ERROR );

set_exception_handler(function($e) { echo $e->getMessage()."\n"; var_dump($e);} );

//include_once(__DIR__.'/php/const.php');

const DIRECTORY_SITEROOT = __DIR__;

const ONEDAY = 86400;


$admin_mail_address = 'order@kaitekiinsatsu.com';

$bcc_mail_address = 'yamato@youyou.co.jp';

//$bcc_mail_address_2admin = 'yamato@youyou.co.jp,s_yamasaki@youmedia.net';

$bcc_mail_address_2admin = 'yamato@youyou.co.jp';

$bcc_mail_address_2user = 'yamato@youyou.co.jp';


$traffic_sampling	= 600;
$traffic_border		= 5;


$site_base_dir = $base_dir = __DIR__;

$base_url = str_replace(
	DIRECTORY_SITEROOT
	,'https://kaitekiinsatsu.com'
	,$base_dir
);

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

?>