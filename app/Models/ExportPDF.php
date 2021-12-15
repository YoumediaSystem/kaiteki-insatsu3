<?php

namespace App\Models;

//require_once('tcpdf/tcpdf.php');  // PDF作成
//require_once('fpdi/fpdi.php');  // PDF作成拡張
require_once('TCPDF-main/tcpdf.php');  // PDF作成
require_once('FPDI-2.3.6/src/autoload.php');  // PDF作成拡張

class ExportPDF {

    private $log_place = __DIR__.'/../../writable/logs/';
    private $log_file = 'ExportPDF.log';

    private $template_place = __DIR__.'/pdf_template/';
//    private $template_file = 'order_sheet_300.pdf';
    private $template_file = 'taiyou_order_sheet_20210916.pdf';

    private $template_dpi = 300;

    private $font_place = __DIR__.'/TCPDF-main/fonts/';
//	private $font_place = __DIR__.'/tcpdf/fonts2/';
//	private $font_file = 'noto_sans_mono932.ttf';
    private $font_file = 'ipaexg.ttf';

    private $template_width_pixel	= 681;
    private $template_height_pixel	= 961;

    private $template_width_mm		= 182;
    private $template_height_mm		= 257;

    private $font_name = 'ipaexg';
//    private $font_name = 'kozgopromedium';

    private $font_size = [
        'big'		=> 11
        ,'mid'		=> 9
        ,'small'	=> 6
    ];

    private $user_code_from_client = '099990';

    private $now;
    
    function __construct($country_code="jp"){

        $DT = new \DateTime();
        $this->now = $DT->format('Y-m-d H:i:s');

        $this->resetLog();
    }

    function resetLog() {
//		file_put_contents($this->log_place.$this->log_file, '');
    }

    function putLog($text) {
//		file_put_contents($this->log_place.$this->log_file, $text."\n", FILE_APPEND);
    }

    // 発注書出力

    public function order_sheet($param) {

        $CR = "\n";

        $lib = new \App\Models\CommonLibrary();

        // 注文バージョン
        $param['order_version'] = 2;
        $b_offset =
            (strpos($param['product_set']['name_en'],'offset') !== false); // スタンダードセット/パック？

        // お届け先の積み込み
        $delivery = [];
        $delivery[0] = [];

        $b_first_not_kaiteki = true;

        $print_number_all = intval(
            preg_replace('/[^0-9]/','',$param['print_number_all']));


        // オフセット余部対象か？
//        $b_buffer_kaiteki = (2 <= $param['order_version'] && $b_offset);
        $b_buffer_kaiteki = (
            2 <= $param['order_version']
        &&  !empty($param['b_overprint_kaiteki'])
        );
        $buffer_num = intval($print_number_all * 0.1);

        if (!empty($param['number_kaiteki'])) {

            $text = '快適本屋さん《納品無料》';

            $delivery[] = [
                'text'	=> $text
                ,'num'	=> $param['number_kaiteki'].
                ($b_buffer_kaiteki ? '＋'.$buffer_num : '')
                ,'b_buffer' => false
//					(bool)($param['delivery_buffer'] == '快適本屋さん')
            ];
        }

        // 本文◯P始まりの値を設定
        $nonble_from = 3; // default
        if (!empty($param['nonble_from'])) {

            $nonble_from = preg_replace(
                "/p始まり/",'',
                $param['nonble_from']
            );
        }

        if (!empty($param['number_home'])) {

            $text = '';
            if ($b_first_not_kaiteki) {
                $text .= '《無料納品対象》'.$CR;
                $b_first_not_kaiteki = false;
            }
            $text .= $lib->getYubinCode($param['userdata']['zipcode']);
            $text .= ' '.$param['userdata']['address1'];
            $text .= ' '.($param['userdata']['address2'] ?? '');
            $text .= $CR;
            $text .= $param['userdata']['name'];
            $text .= '（'.$param['userdata']['name_kana'].'）';
            $text .= $CR.$CR;
            $text .= 'TEL '.$param['userdata']['tel'];
            $text .= '（連絡時間 '.$param['userdata']['tel_range'].'）';

            $delivery[] = [
                'text'	=> $text
                ,'num'	=> $param['number_home']
                ,'b_buffer' => false
//					(bool)($param['delivery_buffer'] == '自宅')
            ];
        }

        if (!empty($param['delivery']) && count($param['delivery'])) {

            foreach($param['delivery'] as $row) {

                if ($row['type'] == 'event') {

                    $text = '';
                    if ($b_first_not_kaiteki) {
                        $text .= '《無料納品対象》'.$CR;
                        $b_first_not_kaiteki = false;
                    }

                    $DT_event = new \Datetime($row['date']);

                    $text .= $DT_event->format('Y/n/j');
                    $text .= ' '.($row['name'] ?? '');
                    $text .= $CR;
                    $text .= ($row['place'] ?? '').' ';
                    $text .= ($row['hall_name'] ?? '');
                    $text .= $CR.$CR;
                    $text .= ($row['space_code'] ?? '');
                    $text .= ' '.($row['circle_name'] ?? '');
                    $text .= '（'.($row['circle_name_kana'] ?? '').'）';
        
                    $delivery[] = [
                        'text'	=> $text
                        ,'num'	=> $row['number']
                        ,'b_buffer' => false
                    ];
        
                } else { // other

                    $text = '';
                    if ($b_first_not_kaiteki) {
                        $text .= '《無料納品対象》'.$CR;
                        $b_first_not_kaiteki = false;
                    }
                    $text .= $lib->getYubinCode($row['zipcode']).$CR;
                    $text .= ' '.($row['real_address_1'] ?? '');
                    $text .= ' '.($row['real_address_2'] ?? '');
                    $text .= $CR;
                    $text .= ($row['real_name'] ?? '');
                    $text .= '（'.($row['real_name_kana'] ?? '').'）';
                    $text .= $CR.$CR;
                    $text .= 'TEL '.($row['tel'] ?? '');
        
                    $delivery[] = [
                        'text'	=> $text
                        ,'num'	=> $row['number']
                        ,'b_buffer' => false
                    ];
                }
            }
        }

/*
        if (!empty($param['dpi'])) {
            $this->template_file	= 'order_sheet_600.pdf';
            $this->template_dpi		= 600;
        }
*/
//		$PDF = new \FPDI('P','mm',[
        $PDF = new \setasign\Fpdi\Tcpdf\Fpdi('P','mm',[
            $this->template_width_mm
            ,$this->template_height_mm
        ] //array(182, 256)
        ,true,"UTF-8",false);

        $a = [];
        $a['a_meta_charset']	= "UTF-8";
        $a['a_meta_dir']		= "jpn";
        $a['a_meta_language']	= "ja";
        $a['w_page']			= " ページ";
        $PDF->setLanguageArray($a);

        $PDF->setFont($this->font_name, '', $this->font_size['mid']);
/*
        $font = new \TCPDF_FONTS();
        $font_1 = $font->addTTFfont($this->font_place.$this->font_file);
        $PDF->SetFont($font_1 , '', $this->font_size['mid']);
*/
        $PDF->setPrintHeader(false);// 上下に線が入ってしまうので追加
        $PDF->setPrintFooter(false);// 上下に線が入ってしまうので追加
        $PDF->SetAutoPageBreak(false);// 画像配置時に改ページされるのを抑止
        $PDF->setFontSubsetting(true);
        $PDF->AddPage();
        $PDF->setSourceFile($this->template_place.$this->template_file);

        $TemplateIndex = $PDF->importPage(1);
        $PDF->useTemplate($TemplateIndex, 0, 0
            ,$this->template_width_mm
            ,$this->template_height_mm
            ,true);

        $PDF->SetFillColor(255,255,0); // debug


        // 各パラメータ出力
        $p = [];
        $p['align'] = 'left';
        $p['fill'] = !empty($param['sheet_debug']) ? 1 : 0;
        $p['stretch'] = 0;
        $p['size_adjust'] = 0;

        // 発注ID
        $p['size'] = 'small';
        $p['align'] = 'right';
        $x = 100;	$y = 5;
        $w = 77;	$h = 12;
        $p['text'] = '発注ID：'.$param['id'];
        $this->putText($PDF, $p, $x, $y, $w, $h);
        $p = $this->resetParam($p);

        // ヘッダー
        $p['size'] = 'big';
        $x = 11;	$y = 18;
        $w = 160;	$h = 16;
        $p['text'] = $param['product_set']['name'].'発注書';
        $this->putText($PDF, $p, $x, $y, $w, $h);
        $p = $this->resetParam($p);

        // ヘッダー右（納品日）
        $p['align'] = 'right';
        $x = 87.5;	$y = 19;
        $w = 64;	$h = 16;
        $DT_up = new \Datetime($param['print_up_date']);
        $p['text'] = '（'.$DT_up->format('Y/n/j').' 納品希望）';
        $this->putText($PDF, $p, $x, $y, $w, $h);
        $p = $this->resetParam($p);

        // 発注日
        $p['align'] = 'right';
        $x = 27;	$y = 25.5;
        $w = 16;	$h = 12;
        $DT_order = new \Datetime($param['create_date']);
        $p['text'] = $DT_order->format('Y');
        $this->putText($PDF, $p, $x, $y, $w, $h);
        $x = 42;
        $p['text'] = $DT_order->format('n');
        $this->putText($PDF, $p, $x, $y, $w, $h);
        $x = 57.5;
        $p['text'] = $DT_order->format('j');
        $this->putText($PDF, $p, $x, $y, $w, $h);
        $p = $this->resetParam($p);

        // タイトル
        $x = 27;	$y = 30.5;
        $w = 45;	$h = 24;
        $p['text'] = $param['print_title'];
        $this->putText($PDF, $p, $x, $y, $w, $h);

        // 原稿情報・表紙
        $x = 37;	$y = 40.5;
        $w = 44;	$h = 11;
        $p['text']  = 'データ';
        $this->putText($PDF, $p, $x, $y, $w, $h);

        // 原稿情報・本文
        $x = 37;	$y = 45.5;
        $w = 44;	$h = 11;
        $p['text']  = 'データ';
        $this->putText($PDF, $p, $x, $y, $w, $h);

        // 仕上りサイズ
        $x = 27;	$y = 50.5;
        $w = 55;	$h = 11;
        $p['align']  = 'center';
        $p['text'] = $param['print_size'];
        $this->putText($PDF, $p, $x, $y, $w, $h);

        // ページ数
        $x = 27;	$y = 55.5;
        $w = 35;	$h = 11;
        $p['text']  = str_replace('p', '', $param['print_page']);
        $this->putText($PDF, $p, $x, $y, $w, $h);

        // カラー◯P始まり
        $x = 33;	$y = 60.5;
        $w = 12;	$h = 11;
        $p['text']  = '1';
//		$this->putText($PDF, $p, $x, $y, $w, $h); // 掲載なし

        // 本文◯P始まり
        $x = 60;	$y = 60.5;
        $w = 12;	$h = 11;
        $p['text']  = $nonble_from;
        $this->putText($PDF, $p, $x, $y, $w, $h);

        // 部数
        $x = 27;	$y = 65.5;
        $w = 25;	$h = 11;
        $p['align'] = 'right';
        $p['text']  = $print_number_all;
        if ($b_buffer_kaiteki)
            $p['text'] .= '（＋'.$buffer_num.'）';
        $this->putText($PDF, $p, $x, $y, $w, $h);
        $p = $this->resetParam($p);
        
        // 綴じ
        $x = 27;	$y = 70.5;
        $w = 55;	$h = 11;
        $p['text'] = $param['print_direction'].' / '.$param['binding'];
        $this->putText($PDF, $p, $x, $y, $w, $h);
        $p = $this->resetParam($p);

        // 表紙
        $p['size'] = 'small';
        $p['size_adjust'] = 0.5;
        $x = 20;	$y = 76;
        $w = 70;	$h = 30;
        $p['text']  = '用紙　：'.$param['cover_paper'].$CR;
        $p['text'] .= 'カラー：'.$param['cover_color'].$CR;
        $p['text'] .= '加工　：'.$param['cover_process'];
        $this->putText($PDF, $p, $x, $y, $w, $h);
        
        // 本文
        $x = 20;	$y = 116;
        $w = 70;	$h = 30;
        $p['text']  = '用紙　：'.$param['main_paper'].$CR;
        $p['text'] .= 'カラー：'.$param['main_color'].$CR;
        $p['text'] .= '遊び紙：'.$param['main_buffer_paper'];
        $this->putText($PDF, $p, $x, $y, $w, $h);
        $p = $this->resetParam($p);

        // その他
        /*
        if (!empty($param['number_kaiteki'])) {
            $x = 20;	$y = 156;
            $w = 70;	$h = 30;
            $p['text']  = '快適本屋さんに '.$param['number_kaiteki'].'部納品';
            $this->putText($PDF, $p, $x, $y, $w, $h);
        }
        */

        // お届け先
        $len = count($delivery) - 1;
        $len = (5 < $len) ? 5 : $len;

        // お届け先数
        $p['align'] = 'right';
        $x = 115;	$y = 25.5;
        $w = 23;	$h = 12;
        $p['text'] = $len;
        $this->putText($PDF, $p, $x, $y, $w, $h);
        $p = $this->resetParam($p);

        for ($i=1; $i<=$len; $i++) {

            $n = $i - 1;
//            $yy = 37.6;
            $yy = 30.08;

            // お届け先ｎ
            $x = 85;	$y = 30 + $n * $yy;
            $w = 64;	$h = 45;
            $p['text'] = $delivery[$i]['text'];
            $this->putText($PDF, $p, $x, $y, $w, $h);

            // お届け先ｎ部数
//            $x = 115;	$y = 30 + 33.5 + $n * $yy;
            $p['align'] = 'right';
            $x = 91;	$y = 30 + 25.98 + $n * $yy;
            $w = 30;	$h = 12;
            $p['text'] = '';
            if (!empty($delivery[$i]['b_buffer']))
                $p['text'] .= '余部・納品書＋';

            $p['text'] .= $delivery[$i]['num'];

            $this->putText($PDF, $p, $x, $y, $w, $h);
            $p = $this->resetParam($p);
        }

        // 備考
        $a = [];
        $a[] = (!empty($param['r18'])
        &&	($param['r18'] == 'あり' || mb_strpos($param['r18'],'成人向',0,'UTF-8') !== false)
        )	? '【成人向け表現あり】' : '';

        // 備考追記
        if ($b_buffer_kaiteki) {
            $a[] = '無料印刷分('.$buffer_num.' 冊)は快適本屋さん通販部数に加える';
/*
            $a[] = !empty($param['number_kaiteki'])
                ? '無料印刷分('.$n.' 冊)は納品先の内容に準ず'
                : '無料印刷分('.$n.' 冊)は快適本屋さん通販に回す';
*/
        }
        $x = 25;	$y = 181.5;
        $w = 125;	$h = 30;
        $p['text']  = implode('　', $a);
        $this->putText($PDF, $p, $x, $y, $w, $h);

        // セット金額
        if (!empty($param['price_text'])) {
            $a = explode("\n", $param['price_text']);
            $len = count($a);
            $aa = [];
            for ($i=0; $i<$len; $i+=2)
                if (mb_strpos($a[$i],'消費税',0,'UTF-8') === false)
                    $aa[] = $a[$i].'…'.str_replace('└','',$a[$i+1]).'円';

            $text = implode("\n",$aa);
            $x = 28;	$y = 207;
            $w = 55;	$h = 30;
            $p['text']  = $text;
            $this->putText($PDF, $p, $x, $y, $w, $h);
        }

        // 小計
        // 送料（分納）

        // 合計
        $p['size'] = 'big';
        $x = 28;	$y = 239;
        $w = 70;	$h = 30;
        $p['text']  = '￥'.number_format($param['price']);
        $this->putText($PDF, $p, $x, $y, $w, $h);
        $p = $this->resetParam($p);

        // お客様コードNo.（共通）
        $x = 140;	$y = 207;
        $w = 35;	$h = 10;
        $p['align']  = 'center';
        $p['text'] = $this->user_code_from_client;
        $this->putText($PDF, $p, $x, $y, $w, $h);
        $p = $this->resetParam($p);

        // 氏名
        $x = 98;	$y = 211.75;
        $w = 50;	$h = 12;
        $p['text'] = $param['userdata']['name'];
        $this->putText($PDF, $p, $x, $y, $w, $h);

        // 住所
        $x = 98;	$y = 216.5;
        $w = 80;	$h = 20;
        $p['text'] =
            $lib->getYubinCode($param['userdata']['zipcode']).' '
            .$param['userdata']['address1'].' '
            .$param['userdata']['address2'];
        $this->putText($PDF, $p, $x, $y, $w, $h);

        // 電話番号
        $x = 98;	$y = 231.5;
        $w = 50;	$h = 12;
        $p['text'] = $param['userdata']['tel'];
        $this->putText($PDF, $p, $x, $y, $w, $h);

        // ---------------------
        
        // PDF出力
        if (!empty($param['save_file_place'])) {

            $file_place = $param['save_file_place'].'order_'.$param['id'].'.pdf';

            if (file_exists($file_place)) unlink($file_place);

            $PDF->Output($file_place,'F');
        }
        
        else
            $PDF->Output('order_'.$param['id'].'.pdf','I');

        return true;
    }

    // テキスト出力
    public function putText($TCPDF, $param, $x, $y, $w, $h) {

        $size = $this->font_size['mid'];
        if ($param['size'] == 'big')	$size = $this->font_size['big'];
        if ($param['size'] == 'small')	$size = $this->font_size['small'];
        $size += $param['size_adjust'];

        $align = 'L';
        if ($param['align'] == 'center')	$align = 'C';
        if ($param['align'] == 'right')		$align = 'R';

        $TCPDF->SetFontSize($size);
        $TCPDF->MultiCell(
            $w,$h
            ,$param['text']
            ,0 // border-width
            ,$align
            ,$param['fill']
            ,0 // position of after
            ,$x,$y
            , true //reset of height
            , $param['stretch'] // stretch
            , false //html
            , true //padding
        );
        $this->putLog($param['text']);

        if (!empty($param['sub_text'])) {
            $TCPDF->SetFontSize($size - 3);
            $TCPDF->Write($h, $param['sub_text']);
        }
    }

    public function resetParam($p) {
        $p['align'] = 'left';
        $p['stretch'] = 0;
        $p['size'] = 'mid';
        $p['size_adjust'] = 0;
        $p['sub_text'] = '';

        return $p;
    }

    public function textFilter($text) {

        $text = str_replace('・',',',$text);

        return $text;
    }

    public function parseDeliveryData($row, $param) { // event1,event2,other

        $data = [];
        if ($row['type'] == 'event') {


        } else {


        }

        return $data;
    }
}