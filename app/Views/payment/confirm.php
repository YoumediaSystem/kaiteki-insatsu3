<?php

const PAGE_NAME = '決済内容確認・ポイント利用';

$Config = new \App\Models\Service\Config();
$point_ratio = $Config->getPointRatio();
$not_kaiteki_point_ratio = $Config->getNotKaitekiPointRatio();
$use_point_ratio = $Config->getUsePointRatio();

$max_userable_points = $Config->getMaxUserablePoints();
$max_give_points = $Config->getMaxGivePoints();


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

.buttons {
    text-align:center;
}

.text-right, .number {
    text-align:right;
}

h4 {
    margin: 1em 0;
    text-align:center;
}

.post td.number {
    text-align:right;
}

</style>

    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>
</head>

<body>

    <?= $view['header'] ?>

    <div id="wrapper">

        <section class="content">

        <h3 class="heading"><?= PAGE_NAME ?></h3>
            <article class="post">



<style>

#buttons_lang, #language, #wrap_point {
    text-align:center;
    vertical-align:middle;
}

#buttons_lang button {
    display:inline-block;
    line-height:1.5;
}

label {
    cursor:pointer;
}

#error_list {
    color:#c00;
}

ul.list_numeric li {
    list-style:none;
    list-style-position:inside;
}

#point_use {
    margin-left:1em;
}

<?php for($i=1; $i<20; $i++): ?>
<?= '.digit-'.$i ?> {width:<?= $i+1 ?>em !important;}
<?php endfor; ?>

</style>



<div id="form_area" class="text">



<?php if(!empty($error)): ?>
<ul id="error_list" class="attention">
<?php foreach($error as $message) echo '<li>'.$message.'</li>'; ?>
</ul>
<?php endif; ?>



<ul class="attention form">
<li>お支払い内容確定ボタンを押すと、全額ポイント決済はお手続き完了となります。</li>
<li>その他のお支払い方法は、決済代行会社ペイジェントが提供する決済手続フォームに進みます。</li>
<li>入金期限の前々日まではカード・コンビニ・ネットバンク・ATMいずれかの方法でお支払いいただけます。</li>
<li>入金期限前日～当日まではカード・ネットバンクいずれかの方法でお支払いいただけます。</li>
<li>決済手続はネット回線や電波が安定している場所や端末でお手続きください。</li>
<li>ATM決済番号発行後や、コンビニ決済番号発行後はメモまたはスクリーンショットを取り、お早めにお支払いください。戻って他の決済方法に変更しないでください。</li>
<li>途中でタブを閉じた場合や決済方法変更した場合、入金確認にお時間がかかったり発注をお受けできない場合がございます。</li>
<li>表記のポイントは全て快適印刷ポイントです。快適本屋ポイントはアカウント情報確認後、別途付与いたします。</li>
<li>一度に獲得できるポイントは最大<?= number_format($max_give_points) ?>ポイントとなります。</li>
<li>獲得ポイントは決済完了後に付与されます。</li>
<li>利用ポイントは決済開始後すぐに消費されます。</li>
</ul>



<?php if(isset($order_list) && count($order_list)):

    $DT_now   = new \Datetime(); ?>



<form id="form" method="post" action="/payment/form">

<table>

<tr>
<th>発注no. タイトル</th>
<th>入金期限</th>
<th>金額（税込）</th>
<th>獲得予定</th>
</tr>

<?php
$total = 0;
$point_total = 0;

foreach($order_list as $order):
    
    $DT_limit = new \Datetime($order['payment_limit']);
    
    $total += $order['price'];

    $ratio = $point_ratio; // 0.01
    if (
//        strpos($order['product_set']['name_en'],'ondemand') !== false
        empty($order['b_overprint_kaiteki'])
    &&  empty($order['number_kaiteki'])
    )   $ratio = $not_kaiteki_point_ratio * $point_ratio;// 50 * 0.01

    $add_point = (int)round($order['price'] * $ratio);

    if ($max_give_points < $add_point) $add_point = $max_give_points;

    $point_total += $add_point;
?>

<tr>
    <td>
    <input type="hidden" name="payment_order[]" value="<?= $order['id'] ?>">
    no.<?= $order['id'] ?>　<?= $order['print_title'] ?>
    </td>
<td><?= $DT_limit->format('Y/n/j H時まで'); ?></td>
<td class="number"><?= number_format($order['price'] ?? 0) ?></td>
<td class="number"><?= number_format($add_point ?? 0) ?>pt</td>
</tr>

<?php endforeach; ?>

<tr>
    <td colspan="2">決済事務手数料</td>
    <td class="number"><?= number_format($payment_fee) ?? 0 ?></td>
    <td>&nbsp;</td>
</tr>



</table>

<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->


<h4>快適印刷ポイント利用（<?= $use_point_ratio ?>ポイント ＝ 1円、端数切り捨て、最大<?= $max_userable_points ?>ptまで利用可）</h4>

<p id="wrap_point">所持快適印刷ポイント：<?= $point ?>pt

<input id="point_use" type="number" name="point_use" class="digit-6 number" value="0">pt利用する
</p>

<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->


<table>

<?php
$amount = $total + $payment_fee;
//$point_get = (int)round($total * $give_point_ratio, 0);
$point_get = $point_total;

if ($max_give_points < $point_get) $point_get = $max_give_points;
?>

<tr>
<th>合計（税込）</th>
<td>￥<span id="price_total"><?= number_format($amount ?? 0) ?></span></td>
</tr>

<tr>
<th>獲得予定の快適印刷ポイント合計</th>
<td><span id="point_get_view"><?= $point_get ?></span>pt</td>
</tr>

</table>

<?php if(300000 < $amount): ?>
<p class="attention">
    合計金額が30万を超えている為、コンビニ決済はご利用いただけません。
</p>
<?php endif; ?>

<input type="hidden" id="amount" name="amount" value="<?= $amount ?>">
<input type="hidden" id="point_get" name="point_get" value="<?= $point_get ?>">
<input type="hidden" id="fee" name="fee" value="<?= $payment_fee ?>">



<div class="buttons">

    <button id="go_prev" class="ec-blockBtn--cancel" type="button">発注選択に戻る</button>
    <button id="go_next" class="ec-blockBtn--action" type="button">内容確定し、決済に進む</button>

</div>

</form>



<?php else: ?>

<p>未入金の入稿発注は現在ありません。</p>

<?php endif; // $order_list ?>



</div><!-- form_area -->


<script type="text/javascript">

var global = global || {};

global.total = <?= $total ?? 0 ?>;
global.point = <?= $point ?? 0 ?>;
global.ratio = <?= $ratio ?? 1 ?>;
global.give_ratio = <?= $give_point_ratio ?? 0.01 ?>;
global.use_ratio = <?= $use_point_ratio ?? 1 ?>;
global.max_userable_points = <?= $max_userable_points ?>;
global.max_give_points = <?= $max_give_points ?>;
global.fee = <?= $payment_fee ?? 0 ?>;

$('#point_use').on('input', function(){

    mod_use_points();
});

function mod_use_points() {

    var n = parseInt($('#point_use').val());
    var nn = global.total+0;
    var n_fil = 0;
    var point_get = 0;
    var fee = global.fee+0;

    if (isNaN(n) || n < 0) n = 0;
    if (global.point < n) n = global.point+0;
    if (global.max_userable_points < n) n = global.max_userable_points+0;

    if (global.total < n * global.use_ratio) n = global.total * global.use_ratio;
    $('#point_use').val(n);

    n_used = parseInt(n / global.use_ratio);

    nn = global.total - n_used;
    if (nn == 0) fee = 0;
    nn += fee;

    point_get = Math.floor((global.total - n_used) * global.ratio, 0);
    if (global.max_give_points < point_get) point_get = global.max_give_points + 0;

    $('#price_total').text( nn.toLocaleString() );
    $('#point_get_view').text( point_get );

    $('#amount').val(nn);
    $('#point_get').val(point_get);
    $('#fee').val(fee);
}

mod_use_points();

function filter_points() {

    var n = $('#point_use').val();

    n -= n % global.use_ratio;

    $('#point_use').val(n);

    mod_use_points();
}



$('#go_prev').on('click', function(){
    $('#form').submit();
});

$('#go_next').on('click', function(){

    // 利用ポイントの端数切捨処理
    filter_points();

    $('#form').attr('action','/payment/do');
    $('#form').submit();
});

</script>



</section>



</div><!-- wrapper -->

<?= $view['footer'] ?>



</body>
</html>