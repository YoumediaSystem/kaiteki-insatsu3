<?php

include_once('../php/lib.php');

const B_ROUND_UP = false;



function get_price_format($matrix, $matrix_b5, $param = null) {

	global $add_tax;

	$data = [];

	$data['price_base_matrix']		= $matrix['data'];
	$data['price_base_matrix_b5']	= $matrix_b5['data'];

	$data['print_size_group'] = [ // 用紙サイズグループ
		 'A6'		=> 'small'
		,'文庫(A6)' => 'small'
		,'B6'		=> 'small'
		,'A5'		=> 'small'
		,'B5'		=> 'b5'
	];

	$data['price_FM_free_border'] = 300; // FMスクリーン無料適用冊数

	$data['price_FM_base'] = 1760; // FMスクリーン基本料金

	$data['price_FM_per_page'] = 102.5; // FMスクリーン1P当金額

	$data['price_paper_upgrade'] = 257150 / 10000 / 100; // 本文用紙の追加チャージ

	$data['buffer_table_10000'] = [ // 遊び紙 前のみ,両方
		 'normal_small'		=> [102.860, 205.720]
		,'normal_b5'		=> [113.150, 226.290]
		,'tracing_small'	=> [144.000, 288.000]
		,'tracing_b5'		=> [185.150, 370.290]
	];

	$data['price_split'] = 1500; // 分納1件あたりの追加料金

	if (!empty($param['set_id']) && (
			strpos($param['set_id'],'_delivery') !== false
		||	strpos($param['set_id'],'_both') !== false
		)
	)	$data['price_split'] = 1000;

	$data['tax_per'] = 10 * 0.01; // 消費税率
	$data['tax_per'] *= $add_tax ? 1 : 0; // 外税計算の有無

	return $data;
}

function get_round_10($price) {

	$b = (0 < $price % 10);
	$price -= $price % 10;
	if (B_ROUND_UP && $b) $price += 10;
	return $price;
}

function get_round_up10($price) {

	$b = (0 < $price % 10);
	$price -= $price % 10;
	if ($b) $price += 10;
	return $price;
}

function get_round_100($price) {

	$b = (0 < $price % 100);
	$price -= $price % 100;
	if (B_ROUND_UP && $b) $price += 100;
	return $price;
}

function get_price($param) {

	global $matrix, $matrix_b5;

	$a_price = [
		 'total' => 0
		,'detail' => []
	];
/*
	$price = 0;
	$price_page = 0;
	$price_num  = 0;
*/
	$data		= get_price_format($matrix, $matrix_b5, $param);

//	$b_round_up = true; // 端数切り上げ
	$b_round_up = false; // 端数切り捨て

	// 用紙サイズグループ
//	$print_size = ($param['print_size'] == 'B5') ? 'b5' : 'small';
	$print_size = $data['print_size_group'][$param['print_size']];

	// 冊数
	$print_number_all = intval(preg_replace('/[^0-9]/','',$param['print_number_all']));

	// 本文ページ数
	$print_page = intval(preg_replace('/[^0-9]/','',$param['print_page']));
	$print_page_main = $print_page - 4; // 表紙を含めない

	if ($print_size != 'b5') {

		if (!empty($print_number_all) && !empty($print_page))
			$price = $data['price_base_matrix'][
				$param['print_number_all']
			][	$param['print_page']
			];

	} else {

		if (!empty($print_number_all) && !empty($print_page))
			$price = $data['price_base_matrix_b5'][
				$param['print_number_all']
			][	$param['print_page']
			];
	}

	$a_price['detail'][] = text_price('基本料金', $price);
	$a_price['total'] += $price;

	// --- オプション ---

	// 本文用紙変更
/*	
	if ($param['main_paper'] == '上質110kg'
	||	$param['main_paper'] == 'スーパーバルギー'
	) {
		$price = $data['price_paper_upgrade'];
		$price *= $print_page_main;
		$price *= $param['print_number_all'];
		$price = get_round_up10((int)$price);

		$a_price['detail'][] = text_price('本文用紙変更（'.$param['main_paper'].'）', $price);
		$a_price['total'] += $price;
	}
*/
	// 本文FMスクリーン（300部未満）
	if ($param['print_number_all'] < $data['price_FM_free_border']
	&&	$param['main_print_type'] == 'FM') {

		$price = $data['price_FM_base'];
		$price += $print_page_main * $data['price_FM_per_page'];
		$price = get_round_10((int)$price);

		$a_price['detail'][] = text_price('本文FMスクリーン印刷（300冊未満）', $price);
		$a_price['total'] += $price;
	}

	// 遊び紙
	$buffer_type = '';
	if (!empty($param['main_buffer_paper'])
	&&	$param['main_buffer_paper'] != 'なし'
	&&	!empty($param['main_buffer_paper_detail'])
	) {
		$buffer_type =  (mb_strpos(
			$param['main_buffer_paper_detail'], 'クラシコ', 0, 'UTF-8') !== false
		) ? 'tracing' : 'normal';

		$buffer_double = ($param['main_buffer_paper'] == '前のみ') ? 0 : 1;
		
		$key = $buffer_type.'_'.$print_size;

		$price = $data['buffer_table_10000'][$key][$buffer_double] /* * (1 / 1000) */;
//		$price *= $param['print_page'];
		$price *= $param['print_number_all'];
		$price = get_round_10((int)$price);

		$a_price['detail'][] = text_price('遊び紙（'
			.$param['main_buffer_paper'].'、'
			.$param['main_buffer_paper_detail']
			.'）', $price);
		$a_price['total'] += $price;
	}

	// 分納
	$count = 0;
	$count += (0 < $param['number_home'])		? 1 : 0;
	$count += (0 < $param['number_event_1'])	? 1 : 0;
	$count += (0 < $param['number_event_2'])	? 1 : 0;
	$count += (0 < $param['number_other'])		? 1 : 0;

	if (2 <= $count) {

		$price = ($count - 1) * $data['price_split'];

		$a_price['detail'][] = text_price('分納'.$count.'箇所', $price);
		$a_price['total'] += $price;
	}

	// 消費税
	if (!empty($data['tax_per'])) {
		$price = $a_price['total'] * $data['tax_per'];
		$a_price['detail'][] = text_price('消費税'.($data['tax_per'] * 100).'%', $price);
		$a_price['total'] += $price;
	}

	// 明細テキスト
	$a_price['price_text'] = get_detail_text($a_price);

	return $a_price;
}

function export_price_logic() {

	return '';
}

function text_price($text, $price) {

	return [
		'text' => $text
		,'price' => $price
	];
}

function get_detail_text($array) {

	if (empty($array['detail'])) return '';

	$CR = "\n";

	$a = [];
	$b_first = true;
	foreach ($array['detail'] as $key=>$val) {

		if (!$b_first) $a[] = $CR.$CR;

		$a[] = $val['text'];
		$a[] = '└ '.$val['price'];
	}

	return implode($CR, $a);
}