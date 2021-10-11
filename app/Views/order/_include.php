<?php

foreach($_POST as $key=>$val) {
	
	if ($key != 'file')
		$param[htmlspecialchars($key, ENT_QUOTES)] = $val;
}


// 入力値調整

$param['print_up_date']	= join_date($param, 'print_up_date');
$param['birth_day']		= join_date($param, 'birth_day');
$param['event_1_date']	= join_date($param, 'event_1_date');
$param['event_2_date']	= join_date($param, 'event_2_date');

$param['zipcode']		= preg_replace('/-|－|ー|―/u', '', $param['zipcode']);
$param['other_zipcode']	= preg_replace('/-|－|ー|―/u', '', $param['other_zipcode']);

$param['tel']			= preg_replace('/-|－|ー|―/u', '-', $param['tel']);
$param['other_tel']		= preg_replace('/-|－|ー|―/u', '-', $param['other_tel']);

$encode_param = [
 'zipcode'
,'tel'
,'other_zipcode'
,'other_tel'
,'real_name_kana'
,'other_real_name_kana'
,'event_1_circle_name_kana'
,'event_2_circle_name_kana'
];

$encode_param_kana = [
	'real_name_kana'
   ,'other_real_name_kana'
   ,'event_1_circle_name_kana'
   ,'event_2_circle_name_kana'
];

foreach($encode_param as $key) {

	if (!empty($param[$key]))
		$param[$key] = mb_convert_kana($param[$key], 'KVCa');
}

foreach($encode_param_kana as $key) {

	if (!empty($param[$key]))
		$param[$key] = preg_replace('/[^　ァ-ヶー]/u', '', $param[$key]);
}



// 入稿ID生成
$salt = '_kaitekiPrint';
$DT = new Datetime();
$DT->setTimezone(new DateTimeZone('Asia/Tokyo'));

$param['id'] = $DT->format('Ymd_His_');

$hash = hash('SHA256',$param['mail_address'].$salt);
$hash = str_replace('a','r',$hash);
$hash = str_replace('b','t',$hash);
$hash = str_replace('c','u',$hash);
$hash = str_replace('d','m',$hash);
$hash = str_replace('e','o',$hash);
$hash = str_replace('f','w',$hash);
$param['id'] .= substr($hash, 0, 8);



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
/*
$n = 0;
$n += $param['number_home'];
$n += $param['number_event_1'];
$n += $param['number_event_2'];
$n += $param['number_kaiteki'];
$n += $param['number_other'];
if ($n < $param['print_number_all'])
	$param['number_home'] += ($param['print_number_all'] - $n);
*/

function check_param($param) {
	
	global $key2name, $necessary_event, $necessary_other, $DT;

	global $early_limit_event, $early_limit_date;

	global $order_version, $b_not_trust;

	$number_ratio = $b_not_trust ? 1.1 : 1;
	
	$error = [];

	$DT_border = new Datetime('next wednesday');
	$DT_border->setTimezone(new DateTimezone('Asia/Tokyo'));
	
	$DT_up_border = new Datetime('next wednesday');
	$DT_up_border->setTimezone(new DateTimezone('Asia/Tokyo'));
	$DT_up_border->modify('+3days'); // saturday

	$DT_border_later = new Datetime('first day of');
	$DT_border_later->setTimezone(new DateTimezone('Asia/Tokyo'));
	$DT_border_later->modify('+3 month');
	$DT_border_later->modify('last day of');

	$DT_up = new Datetime($param['print_up_date']);
	$DT_up->setTimezone(new DateTimezone('Asia/Tokyo'));

	$b_border = true;

	// 冊数
	if ($param['print_number_all'] == '0冊')
		$error[] = '冊数を選択してください。';

	// ページ数
	if ($param['print_page'] == '0p')
		$error[] = 'ページ数を選択してください。';

	// 納品希望日オーバー
	if ($DT_border_later < $DT_up) {
		$t = '納品日は '.$DT_border_later->format('Y/n/j').' 以前の日付を指定してください。';
		$error[] = $t;
		$b_border = false;
	}

	// 誕生日チェック
	if (mb_strpos($param['r18'],'成人向',0,'UTF-8') !== false) {

		if (empty($param['r18_check']))
			$error[] = '18歳未満は成人向け作品発注できません。';
		/*
		$now_ymd = (int)$DT->format('Ymd');

		$DT_birth = new Datetime($param['birth_day']);
		$DT_birth->setTimezone(new DateTimezone('Asia/Tokyo'));

		$birth_ymd = (int)$DT_birth->format('Ymd');
		if ($now_ymd - $birth_ymd < 18000000)
			$error[] = '18歳未満は成人向け作品発注できません。';
		*/
	}

	// メールアドレス
	$key = 'mail_address'; // 連絡先
	if (!empty($param[$key]) && !is_mail($param[$key]))
		$error[] = $key2name[$key].'を確認してください。';

	// 表紙の紙と加工の組み合わせ
	if (mb_strpos($param['print_set_name'], 'オンデマンド', 0, 'UTF-8') !== false) {

		$b_process = (mb_strpos($param['cover_paper'], 'ポスト', 0,'UTF-8') !== false);

		if ($b_process && $param['cover_process'] == 'なし')
			$error[] = 'ご選択された表紙では、表紙・基本加工はクリアPP・マットPPいずれかをご選択ください。';
		
		if (!$b_process && $param['cover_process'] != 'なし')
			$error[] = 'ご選択された表紙ではクリアPP・マットPPは選択できません。表紙・基本加工「なし」をご選択ください。';
		}

	// 発行部数と納品部数
	$n = 0;
	$n += $param['number_home'];
	$n += $param['number_event_1'];
	$n += $param['number_event_2'];
	$n += $param['number_kaiteki'];
	$n += $param['number_other'];
	if ($param['print_number_all'] * $number_ratio != $n)
		$error[] = '発行部数と納品部数の合計が異なります。確認してください。';

	// 分納先の数
	$n = 0;
	$n += $param['number_home'] ? 1 : 0;
	$n += $param['number_event_1'] ? 1 : 0;
	$n += $param['number_event_2'] ? 1 : 0;
	$n += $param['number_kaiteki'] ? 1 : 0;
	$n += $param['number_other'] ? 1 : 0;
	if (4 < $n)
		$error[] = '分納は最大4箇所までとなります。納品先部数を確認してください。';

	// 余部納品先チェック
	if (!$param['number_home'] && $param['delivery_buffer'] == '自宅')
		$error[] = '余部納品先（'.$param['delivery_buffer'].'）の納品部数を確認してください。';

	if (!$param['number_event_1'] && $param['delivery_buffer'] == '直接搬入1')
		$error[] = '余部納品先（'.$param['delivery_buffer'].'）の納品部数を確認してください。';

	if (!$param['number_event_2'] && $param['delivery_buffer'] == '直接搬入2')
		$error[] = '余部納品先（'.$param['delivery_buffer'].'）の納品部数を確認してください。';

	if (!$param['number_kaiteki'] && $param['delivery_buffer'] == '快適本屋さん')
		$error[] = '余部納品先（'.$param['delivery_buffer'].'）の納品部数を確認してください。';

	if (!$param['number_other'] && $param['delivery_buffer'] == 'その他')
		$error[] = '余部納品先（'.$param['delivery_buffer'].'）の納品部数を確認してください。';

	// 直接搬入1 必須項目チェック
	if (!empty($param['number_event_1'])) {

		foreach($necessary_event as $key) { // 必須入力チェック
			if(empty($param['event_1'.$key]))
				$error[] = $key2name['event_1'.$key].'を入力してください。';
		}
		// 納品日以降チェック
		$DT_event = new Datetime($param['event_1_date']);
		$DT_event->setTimezone(new DateTimezone('Asia/Tokyo'));

		if ($DT_border_later < $DT_event) {
			$t = '直接搬入1のイベント開催日は '.$DT_border_later->format('Y/n/j').' 以前に限ります。';
			$error[] = $t;
		}

		if ($DT_event < $DT_up_border) {
			$t = '直接搬入1のイベント開催日は '.$DT_up_border->format('Y/n/j').' 以降に限ります。';
			$error[] = $t;
		}

		// 早期締切対象
		if (in_array($param['event_1_date'], $early_limit_event)) {

			$DT_limit = new Datetime($early_limit_date[$param['event_1_date']]);
			$DT_limit->setTimezone(new DateTimezone('Asia/Tokyo'));

			if ($DT_limit < $DT_up_border)
				$DT_up_border->modify($DT_limit->format('Y/n/j'));
		}
	}

	// 直接搬入2 必須項目チェック
	if (!empty($param['number_event_2'])) {

		foreach($necessary_event as $key) { // 必須入力チェック
			if(empty($param['event_2'.$key]))
				$error[] = $key2name['event_2'.$key].'を入力してください。';
		}
		// 納品日以降チェック
		$DT_event = new Datetime($param['event_2_date']);
		$DT_event->setTimezone(new DateTimezone('Asia/Tokyo'));

		if ($DT_border_later < $DT_event) {
			$t = '直接搬入2のイベント開催日は '.$DT_border_later->format('Y/n/j').' 以前に限ります。';
			$error[] = $t;
		}

		if ($DT_event < $DT_up_border) {
			$t = '直接搬入2のイベント開催日は '.$DT_up_border->format('Y/n/j').' 以降に限ります。';
			$error[] = $t;
		}

		// 早期締切対象
		if (in_array($param['event_2_date'], $early_limit_event)) {

			$DT_limit = new Datetime($early_limit_date[$param['event_2_date']]);
			$DT_limit->setTimezone(new DateTimezone('Asia/Tokyo'));

			if ($DT_limit < $DT_up_border)
				$DT_up_border->modify($DT_limit->format('Y/n/j'));
		}
	}

	// その他発送先 必須項目チェック
	if (!empty($param['number_other'])) {

		foreach($necessary_other as $key) { // 必須入力チェック
			if(empty($param[$key]))
				$error[] = $key2name[$key].'を入力してください。';
		}
	}

	// 納品締切
	if ($DT_up < $DT_up_border) {
		$t = '納品日は '.$DT_up_border->format('Y/n/j').' 以降の日付を指定してください。';
		$error[] = $t;
		$b_border = false;
	}

	return $error;
}

?>