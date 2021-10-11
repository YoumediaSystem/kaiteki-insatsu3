<?php

const PRINT_SET_NAME = '快適おまけセット・よくばりプラン';

// ----------------------------------------------

// 外税計算
$add_tax = false;

// 発注書出力URL
$export_sheet_url = $base_url.'/admin/export_sheet.php';

// メール転送先
$bcc_mail_address_2admin = 'info@taiyoushuppan.jp';


// 早期締切リスト取得
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


// ページ数冊数マトリクス取得
$file_place = __DIR__.'/matrix_base.csv';
$csv = getCSVfile($file_place);
$matrix = parseCSVindex($csv);

$file_place = __DIR__.'/matrix_base_b5.csv';
$csv = getCSVfile($file_place);
$matrix_b5 = parseCSVindex($csv);



$max_print_number = 10000;

$min_print_page = 12;
$max_print_page = 300;

// イベント合わせ入稿期限
$DT_border = new Datetime('next wednesday');
$DT_border->setTimezone(new DateTimezone('Asia/Tokyo'));
$upload_border_date = $DT_border->format('Y/n/j');

$DT_border->modify('+3days'); // saturday
$border_date = $DT_border->format('Y/n/j');

// 納品希望日上限
$DT_border_later = new Datetime('first day of');
$DT_border_later->setTimezone(new DateTimezone('Asia/Tokyo'));
$DT_border_later->modify('+3 month');
$DT_border_later->modify('last day of');
$border_later_date = $DT_border_later->format('Y/n/j');

// 管理メールアドレス
//$admin_mail_address = 'order@kaitekiinsatsu.com';


$key2name = [

// 基本情報
 'order_date'			=> '発注日'

,'upload_border_date'	=> '入稿・入金締切日'

,'print_up_date'		=> '納品希望日'
,'print_up_date_y'		=> '納品希望日(年)'
,'print_up_date_m'		=> '納品希望日(月)'
,'print_up_date_d'		=> '納品希望日(日)'

//,'delivery_split'		=> '分納'

// 発注者情報

,'mail_address'			=> 'メールアドレス'

,'real_name'			=> '氏名'
,'real_name_kana'		=> '氏名カナ'
/*
,'birth_day'			=> '生年月日'
,'birth_day_y'			=> '生年月日(年)'
,'birth_day_m'			=> '生年月日(月)'
,'birth_day_d'			=> '生年月日(日)'
*/
//,'real_r18'				=> '年齢備考'
,'sex_type'				=> '性別'
,'zipcode'				=> '郵便番号'
,'real_address_1'		=> '住所1'
,'real_address_2'		=> '住所2'
,'tel'					=> '電話番号'
,'tel_range'			=> '連絡可能時間帯'

// 発注仕様

,'print_set_name'		=> 'セット名'

,'print_data_url'		=> '原稿データURL'
,'print_title'			=> '本のタイトル'
,'print_number_all'		=> '冊数'
,'print_size'			=> '仕上がりサイズ'
,'print_page'			=> 'ページ数'
,'print_direction'		=> 'とじ方向'

,'cover_paper'					=> '表紙・用紙'
,'cover_color'					=> '表紙・印刷色'
,'cover_process'				=> '表紙・基本加工'
,'main_paper'					=> '本文・用紙'
,'main_color'					=> '本文・印刷色'
//,'main_print_type'				=> '本文・スクリーンタイプ'
,'main_buffer_paper'			=> '遊び紙'
,'main_buffer_paper_detail'		=> '遊び紙の種類'
,'binding'						=> '製本'
,'r18'							=> '対象年齢'
,'r18_check'					=> '18歳以上か'

// 納品部数

,'number_home'		=> '納品部数・自宅'
,'number_event_1'	=> '納品部数・直接搬入1'
,'number_event_2'	=> '納品部数・直接搬入2'
,'number_kaiteki'	=> '納品部数・快適本屋さんOnline'
,'number_other'		=> '納品部数・その他'

// 余部納品先
,'delivery_buffer'	=> '余部納品先'

// 納品先情報

,'event_1_date'				=> '直接搬入1・イベント開催日'
,'event_1_date_y'			=> '直接搬入1・イベント開催日(年)'
,'event_1_date_m'			=> '直接搬入1・イベント開催日(月)'
,'event_1_date_d'			=> '直接搬入1・イベント開催日(日)'
,'event_1_name'				=> '直接搬入1・イベント名'
,'event_1_place'			=> '直接搬入1・会場名'
,'event_1_hall_name'		=> '直接搬入1・ホール名'
,'event_1_space_code'		=> '直接搬入1・スペースno.'
,'event_1_circle_name'		=> '直接搬入1・サークル名'
,'event_1_circle_name_kana'	=> '直接搬入1・サークル名カナ'

,'event_2_date'				=> '直接搬入2・イベント開催日'
,'event_2_date_y'			=> '直接搬入2・イベント開催日(年)'
,'event_2_date_m'			=> '直接搬入2・イベント開催日(月)'
,'event_2_date_d'			=> '直接搬入2・イベント開催日(日)'
,'event_2_name'				=> '直接搬入2・イベント名'
,'event_2_place'			=> '直接搬入2・会場名'
,'event_2_hall_name'		=> '直接搬入2・ホール名'
,'event_2_space_code'		=> '直接搬入2・スペースno.'
,'event_2_circle_name'		=> '直接搬入2・サークル名'
,'event_2_circle_name_kana'	=> '直接搬入2・サークル名カナ'

,'other_zipcode'			=> 'その他・郵便番号'
,'other_real_address_1'		=> 'その他・住所1'
,'other_real_address_2'		=> 'その他・住所2'
,'other_real_name'			=> 'その他・受取人氏名'
,'other_real_name_kana'		=> 'その他・受取人氏名カナ'
,'other_tel'				=> 'その他・受取人電話番号'
];


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
//	,'birth_day'
	,'print_up_date'
	
	,'zipcode'
	,'real_address_1'
	,'tel'

	,'print_data_url'
	,'print_title'
	,'print_number_all'
	,'print_page'
];

$necessary_event = [ // 必須項目_直接搬入
	 '_date'
	,'_name'
	,'_place'
	,'_circle_name'
	,'_circle_name_kana'
];

$necessary_other = [ // 必須項目_その他納品先
	 'other_zipcode'
	,'other_real_address_1'
	,'other_real_name'
	,'other_real_name_kana'
	,'other_tel'
];

$deny_url_key = [ // URL入力禁止
	 'real_name'
	,'real_name_kana'
	,'real_address_1'
	,'real_address_2'

	,'print_title'
];

$max_input_length = [ // 最大文字数

'real_name'				=> 100

,'real_name'			=> 20
,'real_name_kana'		=> 20

,'mail_address'			=> 256

,'zipcode'				=> 8
,'real_address_1'		=> 200
,'real_address_2'		=> 200
,'tel'					=> 20
,'tel_range'			=> 100

,'print_up_date_y'	=> 4
,'print_up_date_m'	=> 2
,'print_up_date_d'	=> 2

,'birthday_y'		=> 4
,'birthday_m'		=> 2
,'birthday_d'		=> 2

,'print_data_url'	=> 2048
,'print_title'		=> 100
,'print_number_all'	=> 5
,'print_page'		=> 5
,'main_buffer_paper_detail'		=> 100

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

$select = [];
/*
$select['delivery_split'] = [
	'あり','なし'];
*/
$select['sex_type'] = [
	'男','女','未回答'];


$select['print_number_all'] = [];

foreach ($matrix['index_h'] as $key=>$val)
	$select['print_number_all'][] = $val;
/*
= [
	30,50,80,100,150];
	
for ($i=200; $i<=$max_print_number; $i+=100) $select['print_number_all'][] = $i;
*/


$select['print_page'] = [];

foreach ($matrix['index_v'] as $key=>$val)
	$select['print_page'][] = $val;
/*
for ($i=$min_print_page;
	$i<=$max_print_page; $i+=4) $select['print_page'][] = $i;
*/

$select['print_size'] = [
	'A6','B6','A5','B5'];

$select['sex_type'] = [
	'男','女','未回答'];

$select['print_direction'] = [
	'右綴じ','左綴じ'];

$select['cover_paper'] = [
	'アートポスト180kg'];

$select['cover_color'] = [
	'オフセット印刷フルカラー（表2・3印刷無し）'];

$select['cover_process'] = [
	'クリアPP','マットPP'];

$select['main_paper'] = [
	 '上質70kg'
	,'上質90kg'
	,'スーパーバルギー'
	,'書籍バルギー'
	//,'書籍バルギー'
	//,'上質110kg','スーパーバルギー'
];

$select['main_color'] = [
	'オフセット印刷スミ1色'];

$select['main_print_type'] = [
	'AM120線','FM'];

$select['main_buffer_paper'] = [
	'なし'];
//	'なし','前のみ','前後'];

$select['main_buffer_paper_detail'] = [
/*	
	 '色上質・やまぶき'
	,'色上質・レモン'
	,'色上質・濃クリーム'
	,'色上質・白茶'
	,'色上質・オレンジ'

	,'色上質・サーモン'
	,'色上質・空'
	,'色上質・ブルー'
	,'色上質・水'
	,'色上質・あじさい'

	,'色上質・りんどう'
	,'色上質・銀ねず'
	,'色上質・コスモス'
	,'色上質・さくら'
	,'色上質・桃'

	,'色上質・若草'
	,'色上質・もえぎ'
	,'色上質・若竹'
	,'色上質・赤'
	,'色上質・黒'

	,'クラシコトレーシング・ピンク'
	,'クラシコトレーシング・イエロー'
	,'クラシコトレーシング・ブルー'
	,'クラシコトレーシング・グレー'
	,'クラシコトレーシング・オレンジ'

	,'クラシコトレーシング・グリーン'
	,'クラシコトレーシング・バイオレット'
*/
];

$select['binding'] = [
	'無線綴じ'];

$select['r18'] = [
	'全年齢向け','成人向け表現あり'];

$select['delivery_buffer'] = [
	'自宅','直接搬入1','直接搬入2','快適本屋さん','その他'];

$select['_type'] = [
	'自宅','直接搬入','快適本屋さんOnline','その他'];


// check preview params

$preview = [

	'print_set_name'
   ,'order_date'
   ,'upload_border_date'
   ,'print_up_date'

// 発注者情報

   ,'mail_address'

   ,'real_name'
   ,'real_name_kana'
//   ,'birth_day'

   ,'sex_type'
   ,'zipcode'
   ,'real_address_1'
   ,'real_address_2'
   ,'tel'
   ,'tel_range'

// 発注仕様

   ,'print_data_url'

   ,'print_title'
   ,'print_number_all'
   ,'print_size'
   ,'print_page'
   ,'print_direction'

   ,'cover_paper'
   ,'cover_color'
   ,'cover_process'
   ,'main_paper'
   ,'main_color'
   ,'main_print_type'
   ,'main_buffer_paper'
   ,'main_buffer_paper_detail'

   ,'binding'
   ,'r18'

// 納品部数

   ,'number_home'
   ,'number_event_1'
   ,'number_event_2'
   ,'number_kaiteki'
   ,'number_other'

// 余部納品先
   ,'delivery_buffer'

// 納品先情報

   ,'event_1_date'
   ,'event_1_name'
   ,'event_1_place'
   ,'event_1_hall_name'
   ,'event_1_space_code'
   ,'event_1_circle_name'
   ,'event_1_circle_name_kana'

   ,'event_2_date'
   ,'event_2_name'
   ,'event_2_place'
   ,'event_2_hall_name'
   ,'event_2_space_code'
   ,'event_2_circle_name'
   ,'event_2_circle_name_kana'

   ,'other_zipcode'
   ,'other_real_address_1'
   ,'other_real_address_2'
   ,'other_real_name'
   ,'other_real_name_kana'
   ,'other_tel'
];

$not_preview = [
	'print_up_date_y'
   ,'print_up_date_m'
   ,'print_up_date_d'
/*
   ,'birth_day_y'
   ,'birth_day_m'
   ,'birth_day_d'
*/
	,'r18_check'

	,'event_1_date_y'
   ,'event_1_date_m'
   ,'event_1_date_d'

   ,'event_2_date_y'
   ,'event_2_date_m'
   ,'event_2_date_d'

   ,'set_id'
];

?>