<?php

const PAGE_NAME = '印刷セットのご案内（オンデマンド）';
const TYPE = 'パック';

$Product = new \App\Models\DB\ProductSet();
$LimitDate = new \App\Models\Service\LimitDate();

$product_data = $Product->getFromID(2);

unset($Product);

$rest_product = $product_data['max_order'] - $product_data['ordered'];

$sample_page = '16p';
$sample_number = '30冊';

$Config = new \App\Models\Service\Config();
$point_ratio = $Config->getPointRatio();
$not_kaiteki_point_ratio = $Config->getNotKaitekiPointRatio();
$use_point_ratio = $Config->getUsePointRatio();

$limit_date_text = $LimitDate->getLimitText4outline();

$max_userable_points = $Config->getMaxUserablePoints();
$max_give_points = $Config->getMaxGivePoints();

$lib = new \App\Models\CommonLibrary();
$youbi = $lib->getYoubiArray();
$early_limit = (new \App\Models\DB\LimitDateList())
    ->getList4OrderForm([
        'client_code'   => 'taiyou',
        'date_from'     => '2022-04-01',
        'date_to'       => '2022-05-31'
    ]);



?><!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
	<meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
	<title><?= PAGE_NAME ?> | <?= $site['name'] ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="stylesheet" type="text/css" media="all" href="/css/style.css">
	<script src="/js/jquery.js"></script>
	<script src="/js/script.js"></script>
	<script src="/js/pagetop.js"></script>
</head>

<body>

	<?= $view['header'] ?>

	<div id="wrapper" style="padding-top:0">

<!--	<section id="main"> -->

			<section class="content">

			<style>

h2, h2 img, h2 span {
    vertical-align:top;
}

h2 span {
    display: inline-block;
    max-width: 470px;
    text-align: center;
    width: 100%;
}

#wrap_subtype {
/*	
	display:grid;
	grid-template-columns: calc(33% - 5px) calc(33% - 5px) calc(33% - 5px);
	gap: 10px;
*/
	margin-top:3em;
}

ul.common_list {
	margin:0;
	padding-inline-start: 1rem;
}

ul.common_list li {
	list-style:disc;
	padding-left:0;
}
/*
#wrap_subtype .trust_only {
	grid-column: 1;
}

#wrap_subtype .delivery_only {
	grid-column: 2;
}

#wrap_subtype .trust_delivery {
	grid-column: 3;
}

#wrap_subtype .header { grid-row: 1; }
#wrap_subtype .detail { grid-row: 2; }
#wrap_subtype .footer { grid-row: 3; }
*/

h4, h5, h6 {
	padding:1em 0;
	margin:1em 0;
	border-top:1px solid #ebebeb;
	border-bottom:1px solid #ebebeb;
}

h4 {
	font-size:1rem;
    margin-top:0;
}

h4, h5 {
	font-weight:bold;
}

h5, h6 {
	text-align:center;
}

.wrap_link {
	text-align:right;
	font-size:1.33em;
}

.post table th,
.post table td {
	vertical-align:middle;
}

.buttons {
    text-align:center;
    margin:3em auto;
}

#price, #price_b5 {
    background-color:#ff8d00;
    color:#fff;

    margin-bottom:0;
    padding:0.5em 1em;

    font-size:1.33rem;
}

#price_b5 {
    margin-top:3rem;
}

.wrap_matrix {
    width:100%;
    height:50vmin;
    overflow:scroll;

    box-shadow:
    inset 0em 0em 2em rgba(0,0,0,0.1);
}

.post .wrap_matrix table {
    background-color:#fff;
}

.post .wrap_matrix table th {
    box-shadow: 1px 1px 0 #d4d4d4, -1px -1px 0 #d4d4d4;
    width:5em;
}

.post .wrap_matrix table th,
.post .wrap_matrix table td {
    border:1px solid #deb995;
}

.post .wrap_matrix table thead th {
    position: sticky;
    z-index: 1;
    top: 0;

    background-color: #fff3e4;
    color:#ab6c2e;
}

.post .wrap_matrix table tbody th {
    position: sticky;
    z-index: 2;
    left: -1px;

    background-color: #fff3e4;
    color:#ab6c2e;
}

.post .wrap_matrix table thead th.cells_corner {
    z-index: 3;
    top: 0px;
    left: -1px;
    background-color: #ffd096;
}

.post .wrap_matrix table td {
    text-align:right;
}

section.content img.button_img {
    width:calc(100% - 2em);
    max-width:480px;
}

.post h2 {
    border-bottom: none;
    background-image: url(/img/illust.png);
    background-position: right 1.5rem;
    background-repeat: no-repeat;

    margin:0;
    padding:0;
}

section.content h3 {
    margin:0;
    padding:0;
    text-align:center;
    border-bottom:none;
}

section.content h3 img {
    max-width:none;
}

#wrap_content_spec table {
    width:100%;
    margin-top:0;
    margin-bottom:3rem;
    border:1px solid #de8d3b;

}

#wrap_content_spec th {
    width:9em;
    font-weight:bold;
    font-size:1.13rem;
    color:#555;
}

#wrap_content_spec td {
    color:#222;
}

#wrap_content_spec th,
#wrap_content_spec td {
    border:1px solid #de8d3b;
    background-color:#fff3e4;
}

.wrap_bonus_info {
/*    background-color:#fffae6;*/
    border:2px solid #de8d3b;
    margin-bottom:3rem;
}


section.content .wrap_bonus_info h3 {
    position: relative;
    text-align:left;

    min-height:45px;
    line-height:45px;
    padding-left:130px;
    margin-bottom:0.75rem;

    font-size: 1.33rem;
    font-weight:bold;

    background-image:url(/img/headline/h3_bg_ondemand.png);
    background-position:left bottom;
    background-repeat:no-repeat;
}

.wrap_bonus_info h3 img {
    position: absolute;
    left:0;
    top:-30px;
}

.wrap_bonus_info h3 i {
    color:#ff4b78;
}

.wrap_bonus_info h4 {
    text-align:center;
    padding: 0.0625em 0;
    font-size:1.33rem;

    background-color:#fff;
    border:1px solid #de8d3b;
    border-radius:1rem;

    color:#ab6c2e;
}

.bonus_detail {
    width:calc(100% - 2em);
    margin:0 1em;
}

.wrap_after_notes {
    border:1px solid #de8d3b;
    margin-bottom:3rem;
}

.wrap_after_notes h5 {

    font-size: 1rem;

    border-top:1px solid #de8d3b;

    background-image:url(/img/headline/h3_bg_ondemand.png);
    background-position:left bottom;
    background-repeat:no-repeat;
}

.wrap_after_notes h5:first-child {
    margin-top:0;
    border-top:none;
}

.after_notes_text {
    width:calc(100% - 2em);
    margin:0 1em;
}

@media screen and (max-width:923px) {

    .post h2 {
        height:640px;
        text-align:center;
        background-position:center 320px;
        background-size: contain;
    }
}

@media screen and (max-width:511px) {

    .post h2 {
        background-position:center 64vw;
    }
}

#bonus_event_list table {
    width:100%;
    border: 1px solid #de8d3b;
}

#bonus_event_list th,
#bonus_event_list td {
    border-top: 1px solid #de8d3b;
    border-bottom: 1px solid #de8d3b;

    border-left: 1px solid #fff3e4;
    border-right: 1px solid #fff3e4;
}

#bonus_event_list th:first-child,
#bonus_event_list td:first-child {
    border-left: 1px solid #de8d3b;
}

#bonus_event_list th:last-child,
#bonus_event_list td:last-child {
    border-right: 1px solid #de8d3b;
}

#bonus_event_list th {
    background-color: #fff3e4;
}
/*
    border:1px solid #de8d3b;
    background-color:#fff3e4;

*/

.wrap_text_img {
    display:grid;
    grid-template-columns:1fr 210px;
}

.wrap_img {
    text-align:center;
}

@media screen and (max-width:728px) {

    .wrap_text_img {display:block;}
}

#early_limit_list td {
    padding:1em 0.5em;
    border-left:none;
    border-right:none;
}

</style>

<article class="post" style="padding-top:0">

    <h2 style="border-bottom:none;">
        <img src="/img/headline/title_detail_ondemand.png" alt="オンデマンド印刷
    快適すご盛パック" width="100%" style="max-width:480px">
<!--
        <span><?=
        $rest_product
        ? '残り <b>'.$rest_product.'</b> セット'
        : '完売しました'
        ?></span>
-->
    </h2>

    <div id="wrap_content_spec">

        <h3>
            <img src="/img/headline/h3_content_ondemand.png" width="100%" alt="快適すご盛パック内容" class="pc_mid_only">
            <img src="/img/headline/h3_content_ondemand_sp.png" width="100%" alt="" class="sp_only">
        </h3>

        <table>
        
        <tr>
            <th>印刷仕様</th>
            <td>
                仕上がりサイズ：A6（文庫）・B6・A5・B5から1種を選択<br>
                表紙：オンデマンド4色フルカラー ＋
                アートポスト180kg
                ・マットポスト180kg
                ・Mr.Bスーパーホワイト180kg
                ・ミニッツGAスノーホワイト170kg
                ・シャインフェイスゴールド180kg
                ・シャインフェイスシルバー180kgから選択<br>
                本文：オンデマンド スミ刷り ＋ 上質70kg・上質90kg・書籍用紙90kgの中から1種選択<br>
                製本：無線綴じ
            </td>
        </tr>

        <tr>
            <th><small style="font-size:0.774rem;">オンデマンド</small><br>すご盛特典</th>
            <td>
                <ol>
                    <li>同人誌のお買物にも使える快適印刷ポイント進呈（要ポイント移行）</li>
                    <li>通販納品＆納品1カ所送料無料</li>
                    <li>同人誌イベント1SP無料招待</li>
<!--                    
                    <li>「快適本屋さん」で通販されない方には、分納2カ所目の送料割引<br>
                        <span class="attention">他書店で専売されている方に好評な特典</span>
                    </li>
-->
                </ol>
            </td>
        </tr>
        <tr>
            <th>価格</th>
            <td>パック内容の全てを含んだお得な特別価格
                <a href="#price">
                <img src="/img/button/button_price.png" alt="価格表はこちら"></a>
            </td>
        </tr>
        </table>

        <h3>
            <img src="/img/headline/h3_spec_ondemand.png" width="100%" alt="オフセット印刷基本仕様" class="pc_mid_only">
            <img src="/img/headline/h3_spec_ondemand_sp.png" width="100%" alt="" class="sp_only">
        </h3>

        <table style="margin-bottom:1rem">
        <tr>
            <th>入稿・印刷・発送</th>
            <td>
                <ol>
                    <li><?= $limit_date_text ?>→金曜日発送※</li>
                    <li>表紙・本文同時入稿</li>
                </ol>
            </td>
        </tr>
        </table>

    </div><!-- wrap_content_spec -->


<p>
    遠隔地（北海道・東北・九州・四国・沖縄など）
    開催イベントへの搬入は〆切が早まる場合がございます。
</p>

<p style="margin-bottom:3rem">
    変則的な〆切をご案内させて頂く場合がございます為、
    お早めにご相談ください。
</p>


    <h3>※ゴールデンウィーク期間合わせは繁忙期の為、入稿締切が前倒しとなります。</h3>

<table id="early_limit_list" style="margin-bottom:6em">

<tr>
    <th>納品希望日</th>
    <th>入稿締切日時</th>
</tr>

<?php foreach ($early_limit as $row):
            
$DT1 = new \Datetime($row['print_up_date']);
$DT2 = new \Datetime($row['limit_date']); ?>

<tr>
<td><?= $DT1->format('Y/n/j').$youbi[$DT1->format('w')] ?></td>
<td><b><?= $DT2->format('Y/n/j').$youbi[$DT2->format('w')] ?>　12時まで</b></td>
</tr>

<?php endforeach; ?>

</table>



    <div class="wrap_bonus_info">

    <h3><img src="/img/headline/h3_icon_ondemand_2.png">同人誌のお買い物に使える<i>快適本屋ポイントプレゼント</i></h3>


<div class="bonus_detail">

<p>
ご注文印刷価格の1％を快適本屋ポイントとして進呈
</p>

<ol>
    <li>快適本屋ポイントはオンデマンド入稿された全ての皆様にもれなくプレゼントいたします。</li>
    <li>快適本屋ポイントは印刷価格の1％分を進呈いたします。<br>
    <b>例・印刷価格10,000円（税込）なら<em>100快適本屋ポイント進呈</em></b></li>
    <li>快適本屋1ポイント＝1円相当</li>
    <li><a href="https://www.kaitekihonya.com" target="_blank">快適本屋さんOnline</a>から、どなたもお買い物できます。</li>
    <li>あらかじめ快適本屋さんOnlineへの会員登録が必要となります。
    なお、快適印刷さん・快適本屋さん・快適本屋さんOnlineのIDは別々となりますので、ご注意ください。
    </li>
</ol>

</div><!-- bonus_detail -->

    </div><!-- wrap_bonus_info -->



    <div class="wrap_bonus_info">
    
    <h3><img src="/img/headline/h3_icon_ondemand_3.png"><i>らくらく無料納品</i><small style="font-weight: normal; font-size: 0.774rem;">（送料無料）</small><i>快適本屋さんへの納品も無料</i></h3>

        <div class="bonus_detail">

<div class="wrap_text_img">

<div class="wrap_text">

        <p><b>審査用の見本誌を送る手間も省けます</b></p>


<ol>
    <li>
        納品1カ所の送料無料 全国どこでも1カ所納品無料となります。<br>
        <span class="attention">分納2カ所目から、1カ所毎に＋1,500円となります。</span>
    </li>
    <li>
        入稿された同人誌を「快適本屋さん」で通販を希望される場合、さらに「快適本屋さん」への納品送料が無料となります。<br>
        初めて快適本屋さんをご利用になる方、既に快適本屋さんの登録ナンバー（5桁）をお持ちのサークル様、
　いずれも、快適本屋さんサイトの「<a href="https://kaitekihonya.com/user_data/circle" target="_blank">サークル様へ（委託案内）</a>」から委託作品登録をお願いします。登録は数分で終了します。<br>

なお、快適印刷さん・快適本屋さん・快適本屋さんOnlineのIDは別々となります。
    </li>
    <li>100部以上入稿された作品は、審査なしで「快適本屋さん」の通販を開始することができます。

        <ul class="attention">
            <li>100部未満入稿には、通信販売お申込みの際、簡単な作品審査があります。</li>
            <li>100部未満入稿の方で、通信販売をご希望の場合も「サークル様へ（委託案内）」から作品審査等のお手続きをしていただけます。</li>
        </ul>

    </li>
    <li>残部の納品もまた、1カ所送料を無料とさせていただきます。</li>
    <li>入稿時に「入稿フォーム」にイベント会場、ご自宅、書店などご希望の納品場所（分納先）をご指定ください。<br>
        <span class="attention">納品先は国内に限ります</span>
    </li>
</ol>
</div><!-- wrap_text -->

<div class="wrap_img">
    <img src="/img/detail_illust_1.png">
</div><!-- wrap_img -->

</div><!-- wrap_text_img -->



        <h4>快適本屋さんでの通販をご希望の皆様に</h4>

<p>初めて快適本屋さんをご利用になる方、既に快適本屋さんの登録ナンバー（5桁）をお持ちのサークル様、いずれも、快適本屋さん
サイトの「<a href="https://kaitekihonya.com/user_data/circle" target="_blank">サークル様へ（委託案内）</a>」から
委託作品登録をお願いします。登録は数分で終了します。</p>

<p>なお、快適印刷さん・快適本屋さん・快適本屋さんOnlineのIDは別々となります。</p>


<ul class="attention">
<li>お振込み規定の詳細、追加通販部数をご希望の場合の申込方法等も全て「サークル様へ（委託案内）」から、ご覧いただけます。</li>
<li>100部未満入稿の方で、通信販売をご希望の場合も「サークル様へ（委託案内）」から作品審査等のお手続きをしていただけます。</li>
</ul>

        </div><!-- bonus_detail -->


    </div><!-- wrap_bonus_info -->



    <div class="wrap_bonus_info">
    
    <h3><img src="/img/headline/h3_icon_ondemand_4.png">スタジオYOU主催イベントに<i>サークル1SP参加ご招待</i></h3>


<div class="bonus_detail">

<div class="wrap_text_img">

<div class="wrap_text">

<ol>
    <li>スタジオYOU様主催等「ご招待同人誌イベント一覧表」に掲載されたイベントに無料でサークル参加できます。</li>
    <li>参加費に関係なく、指定されたイベントならどこにでも参加できます。</li>
    <li>ご招待券は入稿後、「ご招待コード」をメールにて送付いたします。<br>
スタジオYOU様主催イベントの場合、イベントご招待申込の際、参加される方のお名前と「ご招待コード」をご記入くださるだけで完了となります。<br>
<span class="attention">イベント団体により、イベント申込方法は異なります。イベントご招待申込の際、連絡いたします。</span>
    </li>
    <li>ご招待スペースは、１スペースとなっております。<br>
        <span class="attention">追加、合体スペースでのお申し込みもできます（有料）。</span>
    </li>
    <li>スタジオYOU主催イベントへのお申込は「オンラインYOU」でのみ「ご招待コード」を使用できます。</li>
    <li>「ご招待コード」は、入稿されたご本人以外使用できません（入稿者名と参加者名の一致が必要です）。</li>
    <li>「ご招待コード」の有効期限は、発行後6カ月となっています。期限内にご利用ください。</li>
    <li>「ご招待コード」は１回限り有効です。</li>


    <h4 style="margin-top:2em">ご招待イベント申込み時のご注意</h4>

<ol>
    <li>ご招待イベントの受付は、各イベント先着30名様までとなっております。30名様のお申込みに達しましたら、締切日前でもそのイベントの受付は終了とさせていただきます。</li>
    <li>受付終了イベントは、当ページにてご確認ください。</li>
    <li>ご招待イベントは、決定次第続々、掲載いたします。ご招待期間内にお好きなイベントをお選びください。</li>
</ol>

</div><!-- wrap_text -->

<div class="wrap_img">
    <img src="/img/detail_illust_2.png">
</div><!-- wrap_img -->

</div><!-- wrap_text_img -->




<div id="bonus_event_list" style="margin-top:2em">
    <h4>ご招待同人誌イベント一覧</h4>

<?php include_once(__DIR__.'/_event_list.php'); ?>

</div><!-- bonus_event_list -->


</div><!-- bonus_detail -->


    </div><!-- wrap_bonus_info -->



    <div class="wrap_bonus_info">
    
    <h3><img src="/img/headline/h3_icon_ondemand_5.png">快適本屋さんでの通販をご希望されない方　<small style="font-size:0.774rem"><i>他店で委託販売されている方に好評な特典です</i></small></h3>


        <div class="bonus_detail">

            <p>
                <strong>快適印刷ポイント 何と<?= $not_kaiteki_point_ratio ?>倍進呈!</strong>
            </p>

            <ul>
                <li>通常　快適印刷ポイントは<?= $point_ratio * 100 ?>%進呈<br>
                <i>例：印刷代金10,000円の場合、<?= number_format(10000 * $point_ratio) ?>ポイント進呈</i><br>
                （ポイント利用なしの場合、決済手数料除く）</li>
                <li>特典適用の場合は<strong><?= $not_kaiteki_point_ratio ?>倍！</strong><br>
                <i>例：印刷代金10,000円の場合、<?=
                        number_format(10000 * $point_ratio * $not_kaiteki_point_ratio) ?>ポイント進呈</i></li>
                <li>快適印刷<?= $use_point_ratio ?>ポイント＝1円</li>

                <li>一度に獲得できるポイントは最大<?= number_format($max_give_points) ?>ポイントとなります。</li>
                <li>一度に利用できるポイントは最大<?= number_format($max_userable_points) ?>ポイントとなります。</li>

                <li>快適印刷ポイントは次回発注分から使用できます。<br>
                <li>快適印刷ポイントは、快適本屋ポイントに移行して「快適本屋さんOnline」で同人誌購入することもできます。</li>
            </ul>

        </div><!-- bonus_detail -->


    </div><!-- wrap_bonus_info -->






    <h4 id="price">快適すご盛パック価格表（オンデマンド　A6・B6・A5）</h4>

    <div class="wrap_matrix">
    <table>

        <thead>
            <tr>
            <th class="cells_corner">&nbsp;</th>
            <?php foreach($matrix['index_h'] as $key_h): ?>
                <th><?= $key_h ?></th>
            <?php endforeach; ?>
            </tr>
        </thead>

        <tbody>
            <?php foreach($matrix['index_v'] as $key_v): ?>
            <tr>
                <th><?= $key_v ?></th>
                <?php foreach($matrix['index_h'] as $key_h): ?>
                    <td><?= number_format($matrix['data'][$key_h][$key_v]) ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    </div><!-- wrap_matrix -->



    <h4 id="price_b5">快適すご盛パック価格表（オンデマンド　B5）</h4>

    <div class="wrap_matrix">
    <table>

        <thead>
            <tr>
            <th class="cells_corner">&nbsp;</th>
            <?php foreach($matrix_b5['index_h'] as $key_h): ?>
                <th><?= $key_h ?></th>
            <?php endforeach; ?>
            </tr>
        </thead>

        <tbody>
            <?php foreach($matrix_b5['index_v'] as $key_v): ?>
            <tr>
                <th><?= $key_v ?></th>
                <?php foreach($matrix_b5['index_h'] as $key_h): ?>
                    <td><?= number_format($matrix_b5['data'][$key_h][$key_v]) ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    </div><!-- wrap_matrix -->


<!--
<pre>
<?= print_r($matrix, true) ?>
</pre>
-->


    <p class="buttons">
        <a href="/order/form?id=<?= $id ?>">
            <img class="button_img" src="/img/button/button_2_form_ondemand.png" alt="入稿フォーム"></a>
    </p>







    <h4>快適すご盛パックご提供一覧</h4>

    <div class="wrap_after_notes">

        <h5>特別印刷価格ご提供：大陽出版様</h5>

        <div class="after_notes_text">

        <p>「<b>快適すご盛りパック</b>」は、早い、安い、きれいで定評のある同人誌印刷会社・大陽出版様とのコラボにより生まれました。</p>

<p>大陽出版様が快適印刷さんだけの「特別印刷価格」を組んで提供してくださいました。</p>

<p>そして、「快適印刷さん」がスタジオYOU様、グループの快適本屋さん等にお願いして、同人活動が楽しく快適になる、どこにもない大変お得なパックができあがりました。</p>

<p>当パックは、期間を限定して入稿受付させていただきます。</p>

<p>当パックは、大陽出版様が印刷してくださいますので、ご入稿、ご入金先も大陽出版様となり、発送・納品も同様となります。</p>
  
<p>
    入稿された原稿に「不備」などがありましたら、大陽出版さまからご連絡が入ります。<br>
    予め、ご承諾いただけますようお願いいたします。
</p>

<!--
<p>また、入稿時期、仕様変更、印刷後の手直し等、印刷料金に過不足が出た場合も、大陽出版様から連絡が入ります。</p>

<p>万一、新たなお支払いが生じた場合、大陽出版様にお支払いください。</p>
-->

<p>発注入金後、ご登録頂いた内容に誤り（ページ数の数え間違い等）があり差額が発生した場合、大陽出版にお支払いください。</p>

<p>快適印刷さんでは、今後も様々な同人誌印刷、イベント、企画会社とコラボして、皆様の同人活動がより快適に、より楽しくなるお手伝いや応援をいたします。</p>

<p>入稿をお待ちしています。</p>


        </div><!-- after_notes_text -->



        <h5>イベント招待ご提供：スタジオYOU様</h5>

        <div class="after_notes_text">

<p>イベントご招待につきましては、スタジオYOU様開催イベント等のご希望のイベントに参加できます。</p>

<p>「ご招待コード」が送信されますので、お申込みの際、お忘れのないようご記入ください。</p>

<p>スタジオYOU様以外のイベントご招待につきましては、当サイト「ご招待同人誌イベント一覧表」にてお知らせしています。</p>

        </div><!-- after_notes_text -->



        <h5>委託手数料無料らくらく通販提供：快適本屋さん</h5>

        <div class="after_notes_text">

<p>無料増刷や委託の通信販売は、快適本屋さんが担当いたします。</p>

<p>同人誌通販サイトでご入稿された方が快適本屋さんに委託販売の申込をされますと、様々な特典を得ることが出来ます。</p>

<p>快適本屋さんは、ご購入される皆様はもとより、委託作品をお預いただいたサークルの皆様が喜んでいただける様々なサービスを提供いたします。</p>

            </div><!-- after_notes_text -->

        </div><!-- wrap_after_notes -->



    <h4>分納・オプション申込につきまして</h4>

<ul>

    <li>ご自宅発送、イベント搬入、書店発送、在庫保管等で2カ所以上の納品をご希望の場合は、印刷された印刷会社様の規定や料金が適用されます。<br>
<!--    
今回の”<b>すご盛り</b>”企画では「もっと」特典に該当しない方は、2カ所目から有料となります。<br>
-->
        <span class="attention">印刷された印刷会社様のサイト等からお申込みください。</span>
    </li>

    <li>イベント搬入をご希望の場合も、必ず入稿・印刷された印刷会社様の指定する連絡期日までにご連絡をお願いします。</li>
<!--
    <li>分納以外のオプション申込につきましても同様に、印刷された印刷会社の規定や料金が適用されます。</li>
-->
<li>当セットは、印刷会社様が他セット・商品で提供されているオプションにはご利用頂けません。</li>

</ul>

<!--
<p>快適印刷おまけパックは「<a href="#outline_trust">通販プラン▼</a>」「<a href="#outline_delivery">納品プラン▼</a>」「<a href="#outline_both">通販＆納品よくばりプラン▼</a>」計3つのプランからお選びいただけます。</p>

<table>

	<tr>
		<th>全プラン共通の特徴</th>
		<td>
		
			<ul style="margin:0;">
				<li>表紙・本文同時入稿</li>
				<li>水曜日締切・同週金曜日発送</li>
			</ul>
		
		</td>
	</tr>

</table>



<h4 id="outline_trust">快適印刷おまけパック・通販おまけプラン</h4>

<p>2大特典！Ｗおまけプレゼント付</p>


<h5>特典１　印刷数のうち最大10％まで快適本屋さん販売手数料無料！</h5>

<p>さらに100冊以上印刷の方や快適本屋さんで委託経験のある方は、審査なしですぐに通販できます！</p>

<p>納品送料は無料です！</p>

<ul class="attention">
	<li>無料対象外の部数には基本手数料（30％）がかかります。</li>
	<li>委託販売作品の納品先への送料は無料です。</li>
	<li>残部の納品は1箇所（ご自宅・イベント搬入・書店発送・在庫保管）送料無料です。</li>
	<li>100冊未満の方で初めて快適本屋さんの委託販売を申し込まれる方は、審査が必要です。<a href="https://www.youclub.jp/circle/new/" target="_blank">新規サークル様用登録フォーム ＞＞</a></li>
</ul>


<h5>特典２　スタジオYOU主催イベント 1SPご招待券</h5>

<p>全国のスタジオYOU主催イベントで使用できます！</p>

<ul class="attention">
	<li>ご利用の際は、オンラインYOUでイベント申込する必要がございます。</li>
	<li>ご招待IDは、快適本屋さん委託申請フォームでご申請いただいたメールアドレスに返信いたします。</li>
	<li>オンラインYOUでイベント申込後、お支払いページで「ご招待」を選択し、テキストボックスにご招待IDを含む所定のテキストをご記入ください。</li>
	<li>ご招待券（ご招待ID）の有効期限は発行から1年、1回限りご利用可能です。</li>
</ul>

<p class="wrap_link">
	<a href="order/?set_id=ondemand_trust">通販おまけプラン 入稿フォーム ＞＞</a>
</p>



<h4 id="outline_delivery">快適印刷おまけパック・納品おまけプラン</h4>

<p>割引＆プレゼント付！</p>


<h5>特典１　追加納品手数料割引</h5>

<p>まとめて1箇所納品の場合、送料無料</p>

<p>納品2箇所目からの追加料金が33％オフの 1500→1000円（何箇所でも、合計4箇所まで）</p>

<ul class="attention">
	<li>4箇所を超える納品希望は受付できません。</li>
</ul>


<h5>特典２　スタジオYOU主催イベント プレゼントチケット</h5>

<p>全国のスタジオYOU主催イベントで使用できます！</p>

<p>プレゼントチケットは「パンフレット」または「追加イス1脚」いずれかと交換できます！</p>

<ul class="attention">
	<li>ご利用の際は、オンラインYOUでイベント申込する必要がございます。</li>
	<li>ご招待IDは、快適本屋さん委託申請フォームでご申請いただいたメールアドレスに返信いたします。</li>
	<li>オンラインYOUでイベント申込する際、備考欄に納品プランIDを含む所定のテキストをご記入ください。</li>
	<li>ご招待券（ご招待ID）の有効期限は発行から1年、1回限りご利用可能です。</li>
</ul>

<p class="wrap_link">
	<a href="order/?set_id=ondemand_delivery">納品おまけプラン 入稿フォーム ＞＞</a>
</p>





<h4 id="outline_both">快適印刷おまけパック・通販＆納品よくばりプラン</h4>

<p>200冊発注される方は、両プランのいいとこ取りサービスを受けられます！</p>


<h5>7大特典！</h5>

<ul class="common_list">
	<li>注文冊数のうち最大10％までの快適本屋さん販売手数料が無料</li>
	<li>はじめて快適本屋さんに委託の場合は審査通過保証</li>
	<li>快適本屋さん委託手数料無料（無料印刷分のみ）</li>
	<li>無料分通販納品＋納品1箇所の送料無料</li>
	<li>納品2箇所目からの追加料金33％オフ 1500→1000円（何箇所でも、最大4箇所まで）</li>
	<li>スタジオYOUイベント 1SPご招待券プレゼント</li>
	<li>スタジオYOUイベント パンフレット引換/追加イス券プレゼント</li>	</ul>


<p class="wrap_link">
	<a href="order/?set_id=ondemand_both">通販＆納品よくばりプラン 入稿フォーム ＞＞</a>
</p>
-->



				</article>



			</section>

<!--	</section> -->

		<?php //$view['side'] ?>

	</div><!-- wrapper -->

	<?= $view['footer'] ?>

</body>
</html>