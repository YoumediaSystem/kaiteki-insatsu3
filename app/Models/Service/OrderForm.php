<?php

namespace App\Models\Service;

class OrderForm
{
    protected $key2name = [

        // 基本情報

         'name'		            => 'セット名'
        ,'print_set_name'		=> 'セット名'

        ,'order_date'			=> '発注日'

        ,'upload_border_date'	=> '入稿・入金締切日'
        ,'payment_limit'	    => '入金締切日'

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

        ,'print_data_url'		=> '原稿データURL'
        ,'print_data_password'	=> '原稿データパスワード'

        ,'print_title'			=> '本のタイトル'
        ,'print_number_all'		=> '冊数'
        ,'print_size'			=> '仕上がりサイズ'
        ,'print_page'			=> 'ページ数'
        ,'nonble_from'			=> '本文始まりページ数'
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
        ,'b_extra_order'				=> '事前相談'
        ,'extra_order_note'				=> '事前相談内容'

        // 納品部数

        ,'number_home'		=> '納品部数・自宅'
        ,'number_event_1'	=> '納品部数・直接搬入1'
        ,'number_event_2'	=> '納品部数・直接搬入2'
        ,'number_kaiteki'	=> '納品部数・快適本屋さんOnline'
        ,'number_other'		=> '納品部数・その他'

        // 余部納品先
        ,'delivery_buffer'  	=> '余部納品先'
        ,'b_overprint_kaiteki'	=> '余部特典'

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


    protected $preview = [

        'name'
       ,'order_date'
       ,'upload_border_date'
       ,'print_up_date'
    
    // 発注者情報
    
       ,'mail_address'
    
       ,'real_name'
       ,'real_name_kana'
    
       ,'sex_type'
       ,'zipcode'
       ,'real_address_1'
       ,'real_address_2'
       ,'tel'
       ,'tel_range'
    
    // 発注仕様
    
       ,'print_data_url'
       ,'print_data_password'
    
       ,'print_title'
       ,'print_number_all'
       ,'print_size'
       ,'print_page'
       ,'nonble_from'
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
       ,'b_extra_order'
       ,'extra_order_note'
    
    // 納品部数
    
       ,'number_home'
       ,'number_event_1'
       ,'number_event_2'
       ,'number_kaiteki'
       ,'number_other'
    
    // 余部納品先
//       ,'delivery_buffer'
       ,'b_overprint_kaiteki'
    
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
    
    protected $not_preview = [
        'print_up_date_y'
       ,'print_up_date_m'
       ,'print_up_date_d'
       ,'r18_check'
    
       ,'event_1_date_y'
       ,'event_1_date_m'
       ,'event_1_date_d'
    
       ,'event_2_date_y'
       ,'event_2_date_m'
       ,'event_2_date_d'
    
       ,'set_id'
       ,'id'
       ,'name_en'
       ,'client_code'
       ,'product_code'
       ,'payment_limit'
       ,'border_event_date'
       ,'border_later_date'

    ];
    
    function getKey2Names() {
        return (array)$this->key2name;
    }

    function getPreviewKeys() {
        return (array)$this->preview;
    }

    function getNotPreviewKeys() {
        return (array)$this->not_preview;
    }
    

}