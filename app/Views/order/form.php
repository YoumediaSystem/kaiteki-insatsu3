<?php

const PAGE_NAME = '入稿フォーム';

$DT = new \Datetime();
$now_y = (int)$DT->format('Y');
unset($DT);

$lib = new \App\Models\CommonLibrary();
$a = $lib->getSelectItemsYMD();
foreach($a as $key=>$val) $$key = $val;

$youbi = $lib->getYoubiArray();


$Price = new \App\Models\Service\Price();

$price_format = $Price->getPriceFormat([
    'client_code' => $client_code,
    'product_code' => $name_en
]);

$select = $Price->getFormSelector(
    $price_format['price_base_matrix'],
    $name_en
);

$LimitDate = new \App\Models\Service\LimitDate();
$limit_list = $LimitDate->getLimitList4OrderForm($client_code);

$upload_border_date = $LimitDate->getUploadBorderDate();
$payment_limit = $payment_limit ?? $LimitDate->getPaymentLimitDate();

$border_event_date = $LimitDate->getBorderEventDate();
$border_later_date = $LimitDate->getBorderLaterDate();

$b_delivery_discount = false;

$LimitDateList = new \App\Models\DB\LimitDateList();

$early_limit = (new \App\Models\DB\LimitDateList())
    ->getList4OrderForm([
        'client_code'   => $client_code,
        'date_from'     => $border_event_date,
        'date_to'       => $border_later_date
    ]);

$DT1 = new \Datetime($border_event_date);

if (empty($print_up_date_y))
    $print_up_date_y = (int)$DT1->format('Y');

if (empty($print_up_date_m))
    $print_up_date_m = (int)$DT1->format('m');

if (empty($print_up_date_d))
    $print_up_date_d = (int)$DT1->format('d');

unset($DT1);


$checked	= ' checked="checked"';
$selected	= ' selected="selected"';
$disabled	= ' disabled="disabled"';
$hidden		= ' style="display:none"';

function get_class_text($key, $necessary) {
    return in_array($key, $necessary) ? 'class="ec-label required"' : 'class="ec-label"';}

function get_must_text($key, $necessary) {
    return in_array($key, $necessary) ? '<span class="input_must">必須</span>' : '';}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
    <meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
    <title><?= PAGE_NAME ?> | <?= $site['name'] ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/form.css">

<style>

input[name="other_real_address_1"],
input[name="other_real_address_2"]
{
    margin-top:1em;
}

td input.width_full {
    width:calc(100% - 1.2em);
}

table {
    width:calc(100% - 2em);
}

.ec-input label {
    display: inline-block;
    border: 1px solid #00ad8d;
    border-radius: 0.33rem;
    padding: 0.25em 0.5em;
}

</style>

    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>

    <script src="/js/warning_freemail.js"></script>
</head>

<body>

<?= $view['header'] ?>

<div id="wrapper">

    <section class="content">




<h2 class="heading"><?= PAGE_NAME ?></h2>

<style>

<?php for($i=1; $i<20; $i++): ?>
<?= '.digit-'.$i ?> {width:<?= $i+1 ?>em !important;}
<?php endfor; ?>

</style>



<div id="form_area" class="text">



<?php if(!empty($error) && count($error) > 0): ?>
<ul id="error_list" class="attention">
<?php foreach($error as $message) echo '<li>'.$message.'</li>'; ?>
</ul>
<?php endif; ?>



<form id="form1" method="post" action="/order/confirm">
<input type="hidden" name="set_id" value="<?= $set_id ?? '' ?>">


<h3>基本情報</h3><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->



<div class="ec-borderedDefs">

<dl>
<dt><h4>印刷セット名</h4></dt>
<dd>
    <div class="ec-input">
        <input type="hidden" name="id" value="<?= $id ?? '' ?>">
        <input type="hidden" name="name" value="<?= $name ?? '' ?>">
        <input type="hidden" name="name_en" value="<?= $name_en ?? '' ?>">
        <input type="hidden" name="client_code" value="<?= $client_code ?? '' ?>">
        <input type="hidden" name="product_code" value="<?= $name_en ?? '' ?>">
        <b><?= $name ?? '' ?></b>
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><?php

$key = 'print_up_date';

?>

<h4>納品希望日</h4></dt>
<dd>
    <div class="ec-select">
        <input type="hidden" id="print_up_date_y" name="print_up_date_y" value="<?= $print_up_date_y ?? '' ?>">
        <input type="hidden" id="print_up_date_m" name="print_up_date_m" value="<?= $print_up_date_m ?? '' ?>">
        <input type="hidden" id="print_up_date_d" name="print_up_date_d" value="<?= $print_up_date_d ?? '' ?>">

        <select id="print_up_date" name="print_up_date">
<?php
$datetext  = $print_up_date_y.'-';
$datetext .= $print_up_date_m.'-';
$datetext .= $print_up_date_d;

$DT = new \Datetime($datetext);
$print_up_date = $DT->format('Y-m-d');

foreach($limit_list as $datekey=>$dateval):

    $datetext = str_replace('_','-',$datekey);
    $DT = new \Datetime($datetext);

    $prop = ($print_up_date == str_replace('_','-',$datekey)) ? $selected : '';

    $a = explode('_', $datekey);
?>
            <option value="<?= $datetext ?>" data-limit="<?= $dateval ?>"<?= $prop ?>>
                <?= str_replace('-','/',$datetext).$youbi[$DT->format('w')] ?>
            </option>
<?php endforeach; ?>
        </select>

        <strong style="font-weight:bold; color:#c00"><?= $border_event_date ?? '' ?>以降～<?= $border_later_date ?? '' ?>まで</strong>

<script>

$('#print_up_date').on('change', function(){ mod_print_up_date() });

function mod_print_up_date() {

    var t = $('#print_up_date').val();
    
    if (typeof t == 'undefined' || t == '') return;

    var a = t.split('-');
    var key = a.join('_');

    $('#print_up_date_y').val(parseInt(a[0]));
    $('#print_up_date_m').val(parseInt(a[1]));
    $('#print_up_date_d').val(parseInt(a[2]));

    var date = $('#print_up_date option[value="'+ t +'"]').attr('data-limit');
    if (typeof date == 'undefined' || date == '') return;

    var a2 = date.split('-').join('/').split(' ');

    $('#upload_border_date').text(a2[0]);
    $('#payment_limit').val(a2[0]+' '+a2[1]);
}

</script>

<!--
        <?php $kkey = $key.'_y'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-6">
            <option value="">-</option>
            <?php foreach($select_y as $val):
                if ($val < $now_y) continue;
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_m'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_m as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_d'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_d as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>
-->



    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt>
<h4>入稿・入金締切</h4></dt>
<dd>
    <input type="hidden" name="data_limit" value="<?= $data_limit ?? '' ?>">
    <input type="hidden" id="payment_limit" name="payment_limit" value="<?= $payment_limit ?? '' ?>">
    <input type="hidden" name="border_event_date" value="<?= $border_event_date ?? '' ?>">
    <input type="hidden" name="border_later_date" value="<?= $border_later_date ?? '' ?>">

    <p><em><span id="upload_border_date"><?= $upload_border_date ?? '' ?></span>　12時まで</em></p>

<?php if (isset($early_limit) && count($early_limit)): ?>

    <p>ただし以下納品日は入稿件数が多いため、通常よりも早い締切日が適用されます。</p>

    <ul class="attention">
        <?php foreach ($early_limit as $row):
            
            $DT1 = new \Datetime($row['print_up_date']);
            $DT2 = new \Datetime($row['limit_date']); ?>

            <li>納品日 <?= $DT1->format('Y/n/j').$youbi[$DT1->format('w')] ?>の場合、
            入稿締切は <b><?= $DT2->format('Y/n/j').$youbi[$DT2->format('w')] ?>　12時まで</b></li>

        <?php endforeach; ?>
    </ul>

<?php endif; ?>

<script>
    
mod_print_up_date();

</script>

</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->


</div><!-- ec-borderedDefs -->



<h3>発注仕様</h3><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<!--<table>-->
<div class="ec-borderedDefs">

<dl>
<dt>
<h4>原稿データURL</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="print_data_url" value="<?= htmlspecialchars($print_data_url ?? '', ENT_QUOTES) ?>" class="width_full" placeholder="原稿データをダウンロードできるURL（オンラインストレージ等）をご入力ください">
        <br>
        <input type="text" name="print_data_password" value="<?= htmlspecialchars($print_data_password ?? '', ENT_QUOTES) ?>" class="digit-8" placeholder="パスワード">

        <ul class="attention">
            <li>原稿データは<a href="/data_format" target="_blank">原稿形式ページ</a>に記載のサイズ・解像度になっていることをご確認のうえ、zip圧縮してオンラインストレージにアップしてください。</li>
            <li>オンラインストレージは
            <a href="https://gigafile.nu/" target="_blank">GigaFile便</a>を推奨します。ダウンロード期間は入金期限より1～2日以上先の日数でご登録ください。</li>
            <li>ダウンロードにパスワードが必要な場合は、2番目の欄にご入力ください。</li>
        </ul>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>本のタイトル</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="print_title" value="<?= htmlspecialchars($print_title ?? '', ENT_QUOTES) ?>" class="width_full">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>冊数</h4></dt>
<dd>
    <div class="ec-input">

        <select id="print_number_all" name="print_number_all" class="digit-6 price_factor">
            <?php
                $prop = ($val == $print_number_all) ? $selected : '';
            ?>
                <option value="0冊"<?= $prop ?>>未選択</option>

            <?php foreach($select['print_number_all'] as $val):
                $prop = ($val == $print_number_all) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>仕上がりサイズ</h4></dt>
<dd>
    <div class="ec-input">

        <select id="print_size" name="print_size" class="price_factor">
            <?php foreach($select['print_size'] as $val):
                $prop = ($val == $print_size) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>ページ数</h4></dt>
<dd>
    <div class="ec-input">

        <select id="print_page" name="print_page" class="digit-6 price_factor">
            <?php
                $prop = ($val == $print_page) ? $selected : '';
            ?>
                <option value="0p"<?= $prop ?>>未選択</option>

            <?php foreach($select['print_page'] as $val):
                $prop = ($val == $print_page) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <span class="attention" style="display:inline-block">表紙4ページを含むページ数をご選択ください</span>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>本文始まりページ数</h4></dt>
<dd>
    <div class="ec-input">

        <select id="nonble_from" name="nonble_from">
            <?php foreach($select['nonble_from'] as $val):
                $prop = ($val == $nonble_from) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>とじ方向</h4></dt>
<dd>
    <div class="ec-input">

        <select id="print_direction" name="print_direction">
            <?php foreach($select['print_direction'] as $val):
                $prop = ($val == $print_direction) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>表紙・用紙</h4></dt>
<dd>
    <div class="ec-input">

        <select id="cover_paper" name="cover_paper" class="price_factor">
            <?php foreach($select['cover_paper'] as $val):
                $prop = ($val == $cover_paper) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>表紙・印刷色</h4></dt>
<dd>
    <div class="ec-input">

        <select id="cover_color" name="cover_color" class="price_factor">
            <?php foreach($select['cover_color'] as $val):
                $prop = ($val == $cover_color) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>表紙・基本加工</h4></dt>
<dd>
    <div class="ec-input">

        <select id="cover_process" name="cover_process" class="price_factor">
            <?php foreach($select['cover_process'] as $val):
                $prop = ($val == $cover_process) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php if($set_id == 'ondemand'): ?>
            <span class="attention" style="display:inline-block">クリアPP・マットPPは表紙がアートポストまたはマットポストのみ選択可能です。</span>
        <?php endif; ?>
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>本文・用紙</h4></dt>
<dd>
    <div class="ec-input">

        <select id="main_paper" name="main_paper" class="price_factor">
            <?php foreach($select['main_paper'] as $val):
                $prop = ($val == $main_paper) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>本文・印刷色</h4></dt>
<dd>
    <div class="ec-input">

        <select id="main_color" name="main_color" class="price_factor">
            <?php foreach($select['main_color'] as $val):
                $prop = ($val == $main_color) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<?php /*
<!--
<dl>
<dt><h4>本文加工</h4></dt>
<dd>
    <div class="ec-input">

        <select id="main_print_type" name="main_print_type" class="price_factor">
            <?php foreach($select[$key] as $val):
                $prop = ($val == $param[$key]) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <em>※300冊以上発注の場合、FMが無料となります</em>

    </div>
</dd>
</dl>
-->
<!-- －－－－－－－－－－－－－－－－－－－－－ -->
*/ ?>

<dl>
<dt><h4>遊び紙</h4></dt>
<dd>
    <div class="ec-input">

        <select id="main_buffer_paper" name="main_buffer_paper" class="price_factor">
            <?php foreach($select['main_buffer_paper'] as $val):
                $prop = ($val == $main_buffer_paper) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>遊び紙の種類</h4></dt>
<dd>
    <div class="ec-input">

        <select id="main_buffer_paper_detail" name="main_buffer_paper_detail" class="price_factor">
            <option value="">（未選択）</option>

            <?php foreach($select['main_buffer_paper_detail'] as $val):
                $prop = ($val == $main_buffer_paper_detail) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>製本</h4></dt>
<dd>
    <div class="ec-input">

        <select id="binding" name="binding" class="price_factor">
            <?php foreach($select['binding'] as $val):
                $prop = ($val == $binding) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>対象年齢</h4></dt>
<dd>
    <div class="ec-input">

        <select id="r18" name="r18">
            <?php foreach($select['r18'] as $val):
                $prop = ($val == $r18) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <span class="attention" style="display:inline-block">18歳以上ですか？
            <label>
                <?php $prop = !empty($r18_check) ? $checked : ''; ?>
                <input type="checkbox" name="r18_check" value="はい"<?= $prop ?>>はい
            </label>
        </span>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

</div><!-- ec-borderedDefs -->



<?php

$h3_text = !empty($b_not_trust) ? '（無料印刷分を含む）' : '';

?>
<h3>納品部数<?= $h3_text ?></h3><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<!--<table>-->
<div class="ec-borderedDefs">

<dl>
<dt><span id="number_all"></span>冊を</dt>
<dd>
    <div class="ec-input multi">

        <div class="wrap_input_number home">
        自宅に
            <input type="number" id="number_home" name="number_home" value="<?= $number_home ?? 0 ?>" class="digit-3">冊
        </div>
        
        <div class="wrap_input_number event_1">
        直接搬入1に
            <input type="number" id="number_event_1" name="number_event_1" value="<?= $number_event_1 ?? 0 ?>" class="digit-3">冊
        </div>
        
        <div class="wrap_input_number event_2">
        直接搬入2に
            <input type="number" id="number_event_2" name="number_event_2" value="<?= $number_event_2 ?? 0 ?>" class="digit-3">冊
        </div>
        
        <div class="wrap_input_number kaiteki">
        快適本屋さんOnlineに
            <input type="number" id="number_kaiteki" name="number_kaiteki" value="<?= $number_kaiteki ?? 0 ?>" class="digit-3">冊
        </div>
        
        <div class="wrap_input_number other">
        その他に
            <input type="number" id="number_other" name="number_other" value="<?= $number_other ?? 0 ?>" class="digit-3">冊
        </div>
        
    </div>

    <p>現在<span id="number_info"></span>冊
    　<span class="attention">1箇所まで無料、2箇所から1箇所ごとに
    +<?= number_format($price_format['price_split']) ?>円（快適本屋さんOnlineは納品無料）</span></p>


    <?php if(strpos($name_en, 'offset') !== false): ?>
        <div class="ec-input">

            <?php $prop = !empty($b_overprint_kaiteki) ? $checked : ''; ?>
            <label>
                <input type="checkbox" id="b_overprint_kaiteki" name="b_overprint_kaiteki"<?= $prop ?> value="余部を快適本屋さんに委託する" class="price_factor"> 余部特典：快適本屋さんに委託する
            </label>

        </div>
    <?php endif; ?>

</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->



<div id="wrap_group_event_1">

<h3>納品先・直接搬入1</h3><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<!--<table>-->
<div class="ec-borderedDefs">

<p>※直接搬入できるイベントは、<a href="https://www.taiyoushuppan.co.jp/doujin/discount/kojinevent/link.php" target="_blank">支援企画対象イベント</a>に限ります。あらかじめご確認ください。</p>

<dl>
<dt><?php $k = 'event_1'; $key = '_date'; ?>
<h4>直接搬入1・イベント開催日</h4></dt>
<dd>
    <div class="ec-select">
        <input type="hidden" id="event_1_date_y" name="event_1_date_y" value="<?= $event_1_date_y ?? '' ?>">
        <input type="hidden" id="event_1_date_m" name="event_1_date_m" value="<?= $event_1_date_m ?? '' ?>">
        <input type="hidden" id="event_1_date_d" name="event_1_date_d" value="<?= $event_1_date_d ?? '' ?>">

        <select id="event_1_date" name="event_1_date">
<?php
$datetext  = $event_1_date_y.'-';
$datetext .= $event_1_date_m.'-';
$datetext .= $event_1_date_d;

$DT = new \Datetime($datetext);
$event_1_date = $DT->format('Y-m-d');

foreach($limit_list as $datekey=>$dateval):

    $datetext = str_replace('_','-',$datekey);
    $DT = new \Datetime($datetext);

    $prop = ($event_1_date == str_replace('_','-',$datekey)) ? $selected : '';

    $a = explode('_', $datekey);
?>
            <option value="<?= $datetext ?>"<?= $prop ?>>
                <?= str_replace('-','/',$datetext).$youbi[$DT->format('w')] ?>
            </option>
<?php endforeach; ?>
        </select>

        <strong style="font-weight:bold; color:#c00">納品希望日～<?= $border_later_date ?>まで</strong>



<script>

$('#event_1_date').on('change', function(){ mod_event_1_date() });

function mod_event_1_date() {

    var t = $('#event_1_date').val();
    
    if (typeof t == 'undefined' || t == '') return;

    var a = t.split('-');

    $('#event_1_date_y').val(parseInt(a[0]));
    $('#event_1_date_m').val(parseInt(a[1]));
    $('#event_1_date_d').val(parseInt(a[2]));
}

mod_event_1_date();

</script>



<!--
        <?php $kkey = $key.'_y'; $fullkey2 = $k.$kkey; ?>
        <select id="<?= $fullkey2 ?>" name="<?= $fullkey2 ?>" class="digit-6">
            <option value="">-</option>
            <?php foreach($select_y as $val):
                if ($val < $now_y) continue;
                $prop = ($val == $$fullkey2) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_m'; $fullkey2 = $k.$kkey; ?>
        <select id="<?= $fullkey2 ?>" name="<?= $fullkey2 ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_m as $val):
                $prop = ($val == $$fullkey2) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_d'; $fullkey2 = $k.$kkey; ?>
        <select id="<?= $fullkey2 ?>" name="<?= $fullkey2 ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_d as $val):
                $prop = ($val == $$fullkey2) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>
-->

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>直接搬入1・イベント名</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="event_1_name" value="<?= $event_1_name ?? '' ?>" class="width_full">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>直接搬入1・会場名</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="event_1_place" value="<?= $event_1_place ?? '' ?>" class="width_full">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>直接搬入1・ホール名</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="event_1_hall_name" value="<?= $event_1_hall_name ?? '' ?>" class="width_full">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>直接搬入1・スペースno.</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="event_1_space_code" value="<?= $event_1_space_code ?? '' ?>" class="width_full">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>直接搬入1・サークル名</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="event_1_circle_name" value="<?= $event_1_circle_name ?? '' ?>" class="width_full">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>直接搬入1・サークル名カナ</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="event_1_circle_name_kana" value="<?= $event_1_circle_name_kana ?? '' ?>" class="width_full">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

</div><!-- ec-borderedDefs -->

</div><!-- wrap_group_event_1 -->






<div id="wrap_group_event_2">

<h3>納品先・直接搬入2</h3><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<!--<table>-->
<div class="ec-borderedDefs">

<p>※直接搬入できるイベントは、<a href="https://www.taiyoushuppan.co.jp/doujin/discount/kojinevent/link.php" target="_blank">支援企画対象イベント</a>に限ります。あらかじめご確認ください。</p>

<dl>
<dt><?php $k = 'event_2'; $key = '_date'; ?>
<h4>直接搬入2・イベント開催日</h4></dt>
<dd>
    <div class="ec-select">
        <input type="hidden" id="event_2_date_y" name="event_2_date_y" value="<?= $event_2_date_y ?? '' ?>">
        <input type="hidden" id="event_2_date_m" name="event_2_date_m" value="<?= $event_2_date_m ?? '' ?>">
        <input type="hidden" id="event_2_date_d" name="event_2_date_d" value="<?= $event_2_date_d ?? '' ?>">

        <select id="event_2_date" name="event_2_date">
<?php
$datetext  = $event_2_date_y.'-';
$datetext .= $event_2_date_m.'-';
$datetext .= $event_2_date_d;

$DT = new \Datetime($datetext);
$event_2_date = $DT->format('Y-m-d');

foreach($limit_list as $datekey=>$dateval):

    $datetext = str_replace('_','-',$datekey);
    $DT = new \Datetime($datetext);

    $prop = ($event_2_date == str_replace('_','-',$datekey)) ? $selected : '';

    $a = explode('_', $datekey);
?>
            <option value="<?= $datetext ?>"<?= $prop ?>>
                <?= str_replace('-','/',$datetext).$youbi[$DT->format('w')] ?>
            </option>
<?php endforeach; ?>
        </select>

        <strong style="font-weight:bold; color:#c00">納品希望日～<?= $border_later_date ?>まで</strong>



<script>

$('#event_2_date').on('change', function(){ mod_event_2_date() });

function mod_event_2_date() {

    var t = $('#event_2_date').val();
    
    if (typeof t == 'undefined' || t == '') return;

    var a = t.split('-');

    $('#event_2_date_y').val(parseInt(a[0]));
    $('#event_2_date_m').val(parseInt(a[1]));
    $('#event_2_date_d').val(parseInt(a[2]));
}

mod_event_2_date();

</script>



<!--
        <?php $kkey = $key.'_y'; $fullkey2 = $k.$kkey; ?>
        <select id="<?= $fullkey2 ?>" name="<?= $fullkey2 ?>" class="digit-6">
            <option value="">-</option>
            <?php foreach($select_y as $val):
                if ($val < $now_y) continue;
                $prop = ($val == $$fullkey2) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_m'; $fullkey2 = $k.$kkey; ?>
        <select id="<?= $fullkey2 ?>" name="<?= $fullkey2 ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_m as $val):
                $prop = ($val == $$fullkey2) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_d'; $fullkey2 = $k.$kkey; ?>
        <select id="<?= $fullkey2 ?>" name="<?= $fullkey2 ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_d as $val):
                $prop = ($val == $$fullkey2) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>
-->

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>直接搬入2・イベント名</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="event_2_name" value="<?= $event_2_name ?? '' ?>" class="width_full">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>直接搬入2・会場名</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="event_2_place" value="<?= $event_2_place ?? '' ?>" class="width_full">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>直接搬入2・ホール名</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="event_2_hall_name" value="<?= $event_2_hall_name ?? '' ?>" class="width_full">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>直接搬入2・スペースno.</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="event_2_space_code" value="<?= $event_2_space_code ?? '' ?>" class="width_full">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>直接搬入2・サークル名</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="event_2_circle_name" value="<?= $event_2_circle_name ?? '' ?>" class="width_full">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>直接搬入2・サークル名カナ</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="event_2_circle_name_kana" value="<?= $event_2_circle_name_kana ?? '' ?>" class="width_full">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

</div><!-- ec-borderedDefs -->

</div><!-- wrap_group_event_2 -->





<div id="wrap_group_other">

<h3>納品先・その他</h3><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<?php $k = 'other'; ?>

<div class="ec-borderedDefs">

<dl>
<dt>
    <h4 class="ec-label required">その他・郵便番号/住所</h4>
</dt>
<dd>
    <div class="ec-input">

        <div class="h-adr">
        <span class="p-country-name" style="display:none;">Japan</span>
        
        <input name="other_zipcode" type="text" class="p-postal-code digit-8" size="8" maxlength="8" value="<?=
        $other_zipcode ?? '' ?>" placeholder="123-4567" style="display:inline-block"> 郵便番号をご入力ください<br>

        <input name="other_real_address_1" type="text" class="p-region p-locality p-street-address p-extended-address width_full" value="<?=
        $other_real_address_1 ?? '' ?>" placeholder="都道府県・市区町村（自動入力）">

        <input name="other_real_address_2" type="text" class="width_full" value="<?=
        $other_real_address_2 ?? '' ?>" placeholder="番地・建物名・部屋番号">
        
        </div>
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>その他・受取人氏名</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="other_real_name" value="<?= $other_real_name ?? '' ?>" class="width_full">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>その他・受取人氏名カナ</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="other_real_name_kana" value="<?= $other_real_name_kana ?? '' ?>" class="width_full">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4>その他・受取人電話番号</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="other_tel" value="<?= $other_tel ?? '' ?>" class="width_full">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

</div><!-- ec-borderedDefs -->

</div><!-- wrap_group_other -->



<h3>ご注意</h3>

<p>当印刷セットは大陽出版株式会社からの提供を受けて販売しております。</p>

<p>
    ご入金の後、印刷データや印刷内容に問題が生じた場合・ご発注内容の変更・不足額のお支払い・イベント当日の納品トラブルなどに関しましては、各印刷会社の担当者に直接ご相談ください。
</p>

<p>
    上記につきまして快適印刷さんOnlineでは直接お答えできかねますので、あらかじめご理解ご了承ください。
</p>



<div class="text-center buttons">

    <button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="confirm">確認ページへ</button>

</div><!-- text-center -->



</form>

</div><!-- form_area -->


<script type="text/javascript">

var global = global || {};

var array_target = [];

array_target[0] = 'input[name="mail_address"]';
//array_target[1] = 'input[name="product_mail_address"]';

warning_freemail(array_target); // フリーメールドメインが入力されたら警告表示する


// 選択肢1個の場合はロックをかける

$('select').each(function(){

    if ($('option',this).length == 1) {
        $(this)
        .attr('disabled','disabled')
        .parent().parent().parent().addClass('disable_input');
    }
});

// disabled の中身も送信する

$('#go_next').on('click', function(){

    $('select:disabled').removeAttr('disabled');
    $('#form1').submit();
});

</script>



</section>



</div><!-- wrapper -->

<?= $view['footer'] ?>



<div id="box_price_info">
    <div id="wrap_price">

        <span id="price_header">現在の合計</span>
        <span id="price">￥0</span>
        <span id="price_footer">(税込)</span>

    </div><!-- wrap_price -->
</div><!-- box_price_info -->

<script>

var global = global || {};

global.price = 0;

global.delivery_divide = 0; // 分納先の数
global.delivery_discount = <?= $b_delivery_discount ? 'true' : 'false' ?>;

$('select[name="print_number_all"], .wrap_input_number input')
    .on('input', function(){ mod_number() });

function fix_number_input(id) {

    var j = $('#'+id);
    var val = j.val();

    if (typeof val == 'undefined' || isNaN(val) || val == '') j.val(0);
}

function mod_number() {

    var num_ratio = <?= !empty($b_not_trust) ? 1.1 : 1 ?>;

    var t_num_all = $('select[name="print_number_all"]').val();
    var num_all  = parseInt(t_num_all.replace('冊','')) * num_ratio;
    var num = 0, n = 0, nn = 0, rest = num_all;
    var count = 0;
    var t = '';

    $('#number_all').text(num_all);

    fix_number_input('number_home');
    fix_number_input('number_event_1');
    fix_number_input('number_event_2');
    fix_number_input('number_other');
    fix_number_input('number_kaiteki');

    var number_home		= parseInt($('#number_home').val());
    var number_event_1	= parseInt($('#number_event_1').val());
    var number_event_2	= parseInt($('#number_event_2').val());
    var number_other	= parseInt($('#number_other').val());

    var number_kaiteki	= parseInt($('#number_kaiteki').val()); // 分納カウント対象外

    n = number_home;
    nn = n;
    count += n ? 1 : 0;

    n = number_event_1;
    nn += n;
    count += n ? 1 : 0;
    
    if (0 < n) {	$('#wrap_group_event_1').addClass('visible');
    } else {		$('#wrap_group_event_1').removeClass('visible'); }

    n = number_event_2;
    nn += n;
    count += n ? 1 : 0;
    
    if (0 < n) {	$('#wrap_group_event_2').addClass('visible');
    } else {		$('#wrap_group_event_2').removeClass('visible'); }

    n = number_kaiteki;
    nn += n;

    n = number_other;
    nn += n;
    count += n ? 1 : 0;
    
    if (0 < n) {	$('#wrap_group_other').addClass('visible');
    } else {		$('#wrap_group_other').removeClass('visible'); }

    t = nn + '/' + num_all;
    $('#number_info').text(t);

    if (nn == num_all) {
        $('#number_info').addClass('even');
    } else {
        $('#number_info').removeClass('even');
    }

    global.delivery_divide = count+0;
    mod_price();
}

mod_number();



function mod_price() {

    var total = 0;

    var size = $('select[name="print_size"]').val();
    var t_page = $('select[name="print_page"]').val();
    var t_num = $('select[name="print_number_all"]').val();
    var page = parseInt(t_page);
    var num  = parseInt(t_num);

    var data = JSON.parse('<?= json_encode($price_format) ?>');

    var print_size = data.print_size_group[size];

    var print_page_main = page - 4; // 表紙を含めない

    var add_tax = <?= !empty($add_tax) ? 1 : 0 ?>;

    // 表紙・本文

    if (print_size != 'b5') {
        if (t_num != '0冊' && t_page != '0p')
            total += parseInt(data.price_base_matrix['data'][t_num][t_page]);

    } else {
        if (t_num != '0冊' && t_page != '0p')
            total += parseInt(data.price_base_matrix_b5['data'][t_num][t_page]);
    }

    // --- オプション ---

    // 本文用紙変更

    var main_paper = $('select[name="main_paper"]').val();

    // 本文FMスクリーン（300部未満）
    if (num < data.price_FM_free_border
    &&	$('select[name="main_print_type"]').val() == 'FM') {

        price = data.price_FM_base+0;
        price += print_page_main * data.price_FM_per_page;
        total += roundDown(price, 10);
    }

    // 遊び紙
    var main_buffer_paper = $('select[name="main_buffer_paper"]').val();
    var main_buffer_paper_detail = $('select[name="main_buffer_paper_detail"]').val();

    if (main_buffer_paper
    &&	main_buffer_paper != 'なし'
    &&	main_buffer_paper_detail) {

        var buffer_type = (main_buffer_paper_detail.indexOf('クラシコ') != -1)
            ? 'tracing' : 'normal';

        var buffer_double = (main_buffer_paper == '前のみ') ? 0 : 1;

        var key = buffer_type+'_'+print_size;

        price  = data.buffer_table_10000[key][buffer_double]+0;
        price *= num;
        total += roundDown(price, 10);
    }

    // 分納
    var price_split = data.price_split+0;
    var delivery_divide = 0;

    if (parseInt($('#number_home').val()) > 0)      delivery_divide++;
    if (parseInt($('#number_event_1').val()) > 0)   delivery_divide++;
    if (parseInt($('#number_event_2').val()) > 0)   delivery_divide++;
    if (parseInt($('#number_other').val()) > 0)     delivery_divide++;

    var b_delivery_kaiteki = false;
    if (parseInt($('#number_kaiteki').val()) > 0
    ||  $('#b_overprint_kaiteki').prop('checked')
    ) b_delivery_kaiteki = true;

    if (2 <= delivery_divide) {

        if (!b_delivery_kaiteki && global.b_delivery_discount) {
            price_split = price_split / 3 * 2;
        }

        total += (delivery_divide - 1) * price_split; // 1500 or 1000
    }

    // 消費税
    if (add_tax) total += roundDown(total * data.tax_per, 1);

    $('#price').text( get_price_strings(total) );

    global.price = total+0;
}

$('.price_factor').on('input, change', function(){
    
    mod_price();
});

mod_price();


function get_price_strings(price) {

    return '￥' + String(price).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
}

function mod_caution_r18() {

    if ($('#r18').val().indexOf('成人向') != -1) {
        $('#r18 + span').show();

    } else {
        $('#r18 + span').hide();
    }
}

$('#r18').on('input', function(){
    
    mod_caution_r18();
});

mod_caution_r18();


function roundMiddle(value, base) { // base as 10, 100, 0.1
    return Math.round(value / base) * base;
}

function roundUp(value, base) {
    return Math.ceil(value / base) * base;
}

function roundDown(value, base) {
    return Math.floor(value / base) * base;
}
</script>



<script src="//yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>

</body>
</html>