<?php

include_once('../php/lib.php');


function is_over_10hour() {

	// 10時前か10時以後かで分ける
	$DT = new DateTime();
	$timetext = $DT->format('H:i:s');

	return ('10:00:00' <= $timetext);
}

function get_youbi_text($date_text = '') {

	$DT = new Datetime($date_text);
	$w = (int)$DT->format('w');

	$youbi = ['日','月','火','水','木','金','土'];
	return '('.$youbi[$w].')';
}

function get_upload_border_date($date_text = '') { // イベント合わせ入稿期限

	if (!empty($date_text)) {
		$DT_border = new Datetime($date_text);
		$DT_border->setTimezone(new DateTimezone('Asia/Tokyo'));
		$DT_border->modify(is_over_10hour() ? 'next wednesday' : 'this wednesday');

	} else {
		$DT_border = new Datetime(is_over_10hour() ? 'next wednesday' : 'this wednesday');
		$DT_border->setTimezone(new DateTimezone('Asia/Tokyo'));
	}

	$upload_border_date = $DT_border->format('Y/n/j');

	return $upload_border_date;
}

function get_border_delivery_date($date_text = '') { // 最短合わせ日程

	if (!empty($date_text)) {
		$DT_border = new Datetime($date_text);
		$DT_border->setTimezone(new DateTimezone('Asia/Tokyo'));
		$DT_border->modify(is_over_10hour() ? 'next wednesday' : 'this wednesday');

	} else {
		$DT_border = new Datetime(is_over_10hour() ? 'next wednesday' : 'this wednesday');
		$DT_border->setTimezone(new DateTimezone('Asia/Tokyo'));
	}

	$DT_border->modify('+3days'); // saturday
	$border_date = $DT_border->format('Y/n/j');

	return $border_date;
}

function get_early_limits($type = 'default') { // 早期締切リスト取得

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

	if ($type == 'event') return $early_limit_event;
	if ($type == 'date') return $early_limit_date;
	return $early_limit;
}

function get_border_later_date() {

	// 納品希望日上限
	$DT_border_later = new Datetime('first day of');
	$DT_border_later->setTimezone(new DateTimezone('Asia/Tokyo'));
	$DT_border_later->modify('+3 month');
	$DT_border_later->modify('last day of');
	$border_later_date = $DT_border_later->format('Y/n/j');

	return $border_later_date;
}
