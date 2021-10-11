<?php

//include_once('../../../config_all. php');
include_once('../../_config_site.php');
include_once('../../php/lib.php');
/*
$file_place = SITE_PATH.'/config/early_limit.csv';

echo $file_place;

$csv = getCSVfile($file_place);
*/
include_once('../_config.php');
include_once('_config.php');

foreach($_GET  as $key=>$val) $param[htmlspecialchars($key, ENT_QUOTES)] = $val;
foreach($_POST as $key=>$val) $param[htmlspecialchars($key, ENT_QUOTES)] = $val;

if (!empty($param['id'])) {
	$dir = substr($param['id'],0,6).'/';
	$file_place = $base_dir.'/data/'.$dir.$param['id'].'.json';
	$data_dir = SITE_PATH.'/order/data/';

	$data = file_get_contents($file_place);
}

if (!empty($data)) {

	if ($param['debug']) {
		var_dump(json_decode($data, true));

	} else {
		$data = json_decode($data, true);
		$data['sheet_debug'] = 1;

		$data['order_version']	= getOrderVersion($data['set_id']);
		$data['b_standard']		= is_standard($data['set_id']);
		$data['b_not_trust']	= is_notTrust($data['set_id']);

		$p = [
			'id'		=> $param['id']
			,'mode'		=> 'export_sheet_on'
			,'data_dir' => $data_dir
		];
		$query = mod_orderStatusList($p); // id, mode, data_dir

		include_once(SITE_PATH.'/php/ExportPDF.php');
		$ExportPDF = new ExportPDF();
		$ExportPDF->order_sheet($data);
	}
}
