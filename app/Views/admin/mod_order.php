<?php

const PAGE_NAME = '入稿ステータス変更';

//include_once('../../../config_all. php');
include_once('../../_config_site.php');
include_once('../../php/lib.php');
include_once('_config.php');

foreach($_GET  as $key=>$val) $param[htmlspecialchars($key, ENT_QUOTES)] = $val;
foreach($_POST as $key=>$val) $param[htmlspecialchars($key, ENT_QUOTES)] = $val;

$data_dir = SITE_PATH.'/order/data/';
$param['data_dir'] = $data_dir;

$query = mod_orderStatusList($param); // id, mode, data_dir
/*
$file_place = $data_dir.'order_status_list.json';

$query = $error = [];

if (empty($param['id']))
	$error[] = 'IDを指定してください';

if (empty($param['mode']))
	$error[] = '更新内容を指定してください';

if (count($error) == 0) {

	$id = $param['id'];
	$mode = $param['mode'];

	$status_list = json_decode(file_get_contents($file_place), true);

	$status = (!empty($status_list[$id]))
		? $status_list[$id]['status_code'] : '';

	$a = explode(',',$status);

	if ($mode == 'payment_on')
		$a[] = 'payment';

	if ($mode == 'payment_off')
		$a = array_diff($a,['payment']);

	$a = array_diff($a,['',NULL]);

	$status_list[$id]['status_code'] = implode(',', $a);

	file_put_contents($file_place,
		json_encode($status_list, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)
	);

	$query['result'] = $id.'を入金済に変更しました。';


} else $query['error'] = $error;
*/

$query['mode'] = ($param['mode'] == 'payment_on')
	? 'not_payment' : 'payment';

header('location:list.php?'.http_build_query($query));
