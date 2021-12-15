<?php

namespace App\Models\Service;

class Price
{
    public function __construct() {

    }

    public function adjustParam($param) {

        $DT = new \Datetime();
        $now_y = (int)$DT->format('Y');
        
        $param['set_id'] = $param['set_id'] ?? '';
        
        $param['print_up_date_y'] = $param['print_up_date_y'] ?? $now_y;
        $param['event_1_date_y'] = $param['event_1_date_y'] ?? $now_y;
        $param['event_2_date_y'] = $param['event_2_date_y'] ?? $now_y;
        
        $param['print_up_date_m'] = $param['print_up_date_m'] ?? 1;
        $param['event_1_date_m'] = $param['event_1_date_m'] ?? 1;
        $param['event_2_date_m'] = $param['event_2_date_m'] ?? 1;
        
        $param['print_up_date_d'] = $param['print_up_date_d'] ?? 1;
        $param['event_1_date_d'] = $param['event_1_date_d'] ?? 1;
        $param['event_2_date_d'] = $param['event_2_date_d'] ?? 1;
        
        $param['print_number_all'] = $param['print_number_all'] ?? '0冊';
        $param['print_size'] = $param['print_size'] ?? '';
        $param['print_page'] = $param['print_page'] ?? '';
        $param['nonble_from'] = $param['nonble_from'] ?? '3p始まり';
        $param['print_direction'] = $param['print_direction'] ?? '';
        $param['cover_paper'] = $param['cover_paper'] ?? '';
        $param['cover_color'] = $param['cover_color'] ?? '';
        $param['cover_process'] = $param['cover_process'] ?? '';
        $param['main_paper'] = $param['main_paper'] ?? '';
        $param['main_color'] = $param['main_color'] ?? '';
        $param['main_buffer_paper'] = $param['main_buffer_paper'] ?? '';
        $param['main_buffer_paper_detail'] = $param['main_buffer_paper_detail'] ?? '';
        $param['binding'] = $param['binding'] ?? '';
        $param['r18'] = $param['r18'] ?? '';

        return $param;
    }
    
    public function getPriceFormat($param)
    {
        if (empty($param['client_code'])
        ||  empty($param['product_code'])
        ) return [];

        $data = [];

        $MatrixData = new \App\Models\Service\MatrixData();
        $Config = new \App\Models\Service\Config();

//        $data['price_base_matrix']		= $matrix['data'];
//        $data['price_base_matrix_b5']	= $matrix_b5['data'];
    
        $data['print_size_group'] = [ // 用紙サイズグループ
             'A6'		=> ''
            ,'文庫(A6)' => ''
            ,'B6'		=> ''
            ,'A5'		=> ''
            ,'B5'		=> 'b5'
        ];

        foreach ($data['print_size_group'] as $key=>$val) {
            $kkey = 'price_base_matrix';
            $kkey .= !empty($val) ? '_'.$val : '';
            if (empty($data[$kkey]))
                $data[$kkey] = $MatrixData->getMatrixData([
                    'client_code' => $param['client_code'],
                    'product_code' => $param['product_code'],
                    'paper_size' => $val
                ]);
        }
    
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
/*    
        if (!empty($param['set_id']) && (
                strpos($param['set_id'],'_delivery') !== false
            ||	strpos($param['set_id'],'_both') !== false
            )
        )	$data['price_split'] = 1000;
*/        
//        $data['tax_per'] = 10 * 0.01; // 消費税率
//        $data['tax_per'] *= $add_tax ? 1 : 0; // 外税計算の有無

        $data['tax_per'] = 0;

        return $data;
    }

    public function getPrice($param) {

//        global $matrix, $matrix_b5;

        $a_price = [
             'total' => 0
            ,'detail' => []
        ];
    /*
        $price = 0;
        $price_page = 0;
        $price_num  = 0;
    */
        $data		= $this->getPriceFormat($param);
    
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
                $price = $data['price_base_matrix']['data'][
                    $param['print_number_all']
                ][	$param['print_page']
                ];
    
        } else {
    
            if (!empty($print_number_all) && !empty($print_page))
                $price = $data['price_base_matrix_b5']['data'][
                    $param['print_number_all']
                ][	$param['print_page']
                ];
        }
    
        $a_price['detail'][] = $this->text_price('基本料金', $price);
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
        &&	!empty($param['main_print_type'])
        &&  $param['main_print_type'] == 'FM'
        ) {
            $price = $data['price_FM_base'];
            $price += $print_page_main * $data['price_FM_per_page'];
            $price = $this->get_round_10((int)$price);
    
            $a_price['detail'][] = $this->text_price(
                '本文FMスクリーン印刷（300冊未満）', $price);
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
            $price = $this->get_round_10((int)$price);
    
            $a_price['detail'][] = $this->text_price('遊び紙（'
                .$param['main_buffer_paper'].'、'
                .$param['main_buffer_paper_detail']
                .'）', $price);
            $a_price['total'] += $price;
        }
    
        // 分納
        $b_delivery_kaiteki = false;
        $b_delivery_discount = false; // 分納特典無効

        if (!empty($param['number_kaiteki'])
        ||  !empty($param['b_overprint_kaiteki']))
            $b_delivery_kaiteki = true;
        


        if (!empty($param['delivery_divide'])) { // 受注変更

            $count = $param['delivery_divide'];
            if(!empty($param['number_kaiteki'])) $count -= 1;

        } else { // 新規入稿
            $count = 0;
            $count += (0 < ($param['number_home'] ?? 0))	? 1 : 0;
            $count += (0 < ($param['number_event_1'] ?? 0))	? 1 : 0;
            $count += (0 < ($param['number_event_2'] ?? 0))	? 1 : 0;
            $count += (0 < ($param['number_other'] ?? 0))	? 1 : 0;
        }
        
        if (2 <= $count) {

            $price_text = '分納'.$count.'箇所';

            if (!$b_delivery_kaiteki && $b_delivery_discount) {// 1500 → 1000
                $data['price_split'] = intval($data['price_split'] / 3 * 2);
                $price_text .= '(特典)';
            }
            
            $price = ($count - 1) * $data['price_split'];
            
            $a_price['detail'][] = $this->text_price($price_text, $price);
            $a_price['total'] += $price;
        }
    
        // 消費税
        if (!empty($data['tax_per'])) {
            $price = $a_price['total'] * $data['tax_per'];
            $a_price['detail'][] = $this->text_price(
                '消費税'.($data['tax_per'] * 100).'%', $price);
            $a_price['total'] += $price;
        }
    
        // 明細テキスト
        $a_price['price_text'] = $this->get_detail_text($a_price);
    
        return $a_price;
    }

    function count_delivery_divide($param) {
        $count = 0;
        $count += (0 < ($param['number_home'] ?? 0))		? 1 : 0;
        $count += (0 < ($param['number_event_1'] ?? 0))	? 1 : 0;
        $count += (0 < ($param['number_event_2'] ?? 0))	? 1 : 0;
        $count += (0 < ($param['number_other'] ?? 0))		? 1 : 0;
        $count += (0 < ($param['number_kaiteki'] ?? 0))	? 1 : 0;
        return $count;
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

    function get_round_10($price) {

        $b = (0 < $price % 10);
        $price -= $price % 10;
//        if (B_ROUND_UP && $b) $price += 10;
        if ($b) $price += 10;
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
//        if (B_ROUND_UP && $b) $price += 100;
        if ($b) $price += 100;
        return $price;
    }
    



    public function getFormSelector($matrix = null, $name_en = 'offset') { // 仮設定　どこに情報格納し編集するかは今後策定

        if (empty($matrix)) {
            $matrix = [
                'index_h' => ['10冊','20冊','30冊'],
                'index_v' => ['8p','12p','16p'],
            ];
        } // debug

        $b_offset   = (strpos($name_en, 'offset') !== false);
        $b_ondemand = (strpos($name_en, 'ondemand') !== false);

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
        
        $select['nonble_from'] = ['3p始まり','1p始まり'];
        
        $select['print_direction'] = [
            '右綴じ','左綴じ'];
    
        if ($b_offset) {
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
        }
        
        if ($b_ondemand) {
            $select['cover_paper'] = [
                'アートポスト180kg',
                'マットポスト180kg',
                'Mr.B（スーパーホワイト）＜180kg＞',
                'ミニッツGA（スノーホワイト）＜170kg＞',
                'シャインフェイス（ゴールド）＜180kg＞',
                'シャインフェイス（シルバー）＜180kg＞',
            ];
            $select['cover_color'] = [
                'オンデマンド印刷フルカラー（表2・3印刷無し）'];
            $select['cover_process'] = [
                'クリアPP','マットPP','なし'];
            $select['main_paper'] = [
                '上質70kg'
                ,'上質90kg'
                ,'書籍用紙90kg'
            ];
            $select['main_color'] = [
                'オンデマンド印刷スミ1色'];
        }
        
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

        return $select;
    }

}