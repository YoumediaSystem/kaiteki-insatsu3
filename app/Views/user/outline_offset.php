<?php

const PAGE_NAME = '印刷セットのご案内（オフセット）';
const TYPE = 'セット';

$Product = new \App\Models\DB\ProductSet();

$product_data = $Product->getFromID(1);

unset($Product);

$rest_product = $product_data['max_order'] - $product_data['ordered'];

$sample_page = '16p';
$sample_number = '30冊';

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

    <div id="wrapper">

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
    background-color:#00ad8a;
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
    border:1px solid #a3ccc4;
}

.post .wrap_matrix table thead th {
    position: sticky;
    z-index: 1;
    top: 0;

    background-color: #ebf6ec;
    color:#007a62;
}

.post .wrap_matrix table tbody th {
    position: sticky;
    z-index: 2;
    left: -1px;

    background-color: #ebf6ec;
    color:#007a62;
}

.post .wrap_matrix table thead th.cells_corner {
    z-index: 3;
    top: 0px;
    left: -1px;
    background-color: #a5d9a9;
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
    border:1px solid #1db089;

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
    border:1px solid #1db089;
    background-color:#ebf6ec;
}

.wrap_bonus_info {
/*    background-color:#fffae6;*/
    border:2px solid #1db089;
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

    background-image:url(/img/headline/h3_bg_offset.png);
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

.bonus_detail {
    width:calc(100% - 2em);
    margin:0 1em;
}

.wrap_bonus_info h4 {
    text-align:center;
    padding: 0.0625em 0;
    font-size:1.33rem;

    background-color:#fff;
    border:1px solid #1db089;
    border-radius:1rem;

    color:#147d61;
}

.wrap_after_notes {
    border:1px solid #1db089;
    margin-bottom:3rem;
}

.wrap_after_notes h5 {

    font-size: 1rem;

    border-top:1px solid #1db089;

    background-image:url(/img/headline/h3_bg_offset.png);
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
    border: 1px solid #1db089;
}

#bonus_event_list th,
#bonus_event_list td {
    border-top: 1px solid #1db089;
    border-bottom: 1px solid #1db089;

    border-left: 1px solid #ebf6ec;
    border-right: 1px solid #ebf6ec;
}

#bonus_event_list th:first-child,
#bonus_event_list td:first-child {
    border-left: 1px solid #1db089;
}

#bonus_event_list th:last-child,
#bonus_event_list td:last-child {
    border-right: 1px solid #1db089;
}

#bonus_event_list th {
    background-color: #ebf6ec;
}

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

</style>

<article class="post">

    <h2>
        <img src="/img/headline/title_detail_offset.png" alt="オフセット印刷
    快適すご盛セット" width="100%" style="max-width:480px">
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
            <img src="/img/headline/h3_content_offset.png" width="100%" alt="快適すご盛セット内容" class="pc_mid_only">
            <img src="/img/headline/h3_content_offset_sp.png" width="100%" alt="" class="sp_only">
        </h3>

        <table>
        <tr>
            <th>印刷仕様</th>
            <td>表紙（フルカラー）＋本文（モノクロ）＋製本（無線綴じ）</td>
        </tr>
        <tr>
            <th><small style="font-size:0.774rem;">オフセット</small><br>すご盛特典</th>
            <td>
                <ol>
                    <li>「快適本屋さん」で委託通販される方には、発注部数の10％を無料で増刷プレゼント＆販売手数料無料</li>
                    <li>納品1カ所無料＆快適本屋さんへの送料無料</li>
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
            <td>セット内容の全てを含んだお得な特別価格
                <a href="#price">
                <img src="/img/button/button_price.png" alt="価格表はこちら"></a>
            </td>
        </tr>
        </table>

        <h3>
            <img src="/img/headline/h3_spec_offset.png" width="100%" alt="オフセット印刷基本仕様" class="pc_mid_only">
            <img src="/img/headline/h3_spec_offset_sp.png" width="100%" alt="" class="sp_only">
        </h3>

        <table>
        <tr>
            <th>入稿・印刷・発送</th>
            <td>
                <ol>
                    <li>水曜日午前10時〆→金曜日発送</li>
                    <li>表紙・本文同時入稿</li>
                    <li>発注部数の10％を無料増刷（快適本屋さんに納品する場合のみ）</li>
                </ol>
            </td>
        </tr>
        </table>

    </div><!-- wrap_content_spec -->



    <div class="wrap_bonus_info">

    <h3><img src="/img/headline/h3_icon_offset_2.png"><i>無料増刷分</i>を<i>快適本屋さん</i>で<i>通信販売</i></h3>


<div class="bonus_detail">

<div class="wrap_text_img">

<div class="wrap_text">
<ol>
    <li>快適本屋さんに納品する場合のみ、発注された部数の10％を無料で印刷します<br>
        　例・100部印刷されたら、10冊余分に無料で印刷いたします<br>
        <span class="attention">仕上がった同人誌を「快適本屋さん」で委託販売される方に限定したプレゼントです</span>
    </li>
    <li>10％の無料印刷分を快適本屋さんで通信販売します<br>
        <span class="attention">快適本屋さんでの販売方法は、次項で説明いたします。</span>
    </li>
    <li>通販売上は、全額をお振込みします（通販手数料ナシ・振込料はご負担ください）<br>
        <span class="attention">お振込み規定等は、快適本屋さんに準じます。</span>
    </li>
    <li>快適本屋さんには送料無料で納品します（配送運賃がかかりません）審査用の見本誌を送る手間も省けます</li>
    <li>100部以上入稿されますと、審査なしで通販手続きにすすめます。<br>
        <span class="attention">100部未満入稿には、通販をご希望の際、簡単な作品審査があります。</span>
    </li>
    <li>快適本屋さんで通販する、しないの選択は、入稿の際の入稿フォームからできます。
        また無料増刷分に追加して通販されたい場合は、ご希望の部数を指定してください。
        <ul class="attention">
            <li>無料増刷分の委託手数料は無料ですが、追加部数には通販委託の基本手数料（30％）がかかります。</li>
            <li>お申込み方法は、次項をご覧ください。</li>
        </ul>
    </li>
</ol>
</div><!-- wrap_text -->

<div class="wrap_img">
    <img src="/img/detail_illust_1.png">
</div><!-- wrap_img -->

</div><!-- wrap_text_img -->



    <h4>快適本屋さんでの通販をご希望の皆様に</h4>

    <p>初めて快適本屋さんをご利用になる方、既に快適本屋さんの登録ナンバー（5桁ID）をお持ちのサークル様、<br>
いずれも、快適本屋さんサイトの「<a href="https://kaitekihonya.com/user_data/circle" target="_blank">サークル様へ（委託案内）</a>」から
委託作品登録をお願いします。登録は数分で終了します。</p>

<p>なお、快適印刷さん・快適本屋さん・快適本屋さんOnlineのIDは別々となります。</p>


<ul class="attention">
    <li>お振込み規定の詳細、追加通販部数をご希望の場合の申込方法等も全て「サークル様へ（委託案内）」から、ご覧いただけます。</li>
    <li>100部未満入稿の方で、通信販売をご希望の場合も「サークル様へ（委託案内）」から作品審査等のお手続きをしていただけます。</li>
</ul>

</div><!-- bonus_detail -->



    </div><!-- wrap_bonus_info -->



    <div class="wrap_bonus_info">
    
    <h3><img src="/img/headline/h3_icon_offset_3.png">納品1カ所送料無料</h3>


        <div class="bonus_detail">

        <p>全国どこでも1カ所納品無料となります（快適印刷さんへの納品無料とは別途に、1カ所への送料が無料となります）。</p>

        <ul class="attention">
            <li>分納2カ所目から、1カ所毎に＋1,500円となります。</li>
            <li>入稿時にイベント会場、ご自宅、書店などご希望の納品場所をご指定ください。</li>
            <li>納品先は国内に限ります。</li>
        </ul>

        </div><!-- bonus_detail -->


    </div><!-- wrap_bonus_info -->



    <div class="wrap_bonus_info">
    
    <h3><img src="/img/headline/h3_icon_offset_4.png">スタジオYOU主催イベントに<i>サークル参加ご招待</i></h3>


<div class="bonus_detail">

<div class="wrap_text_img">

<div class="wrap_text">

<ol>
    <li>スタジオYOU様主催等「ご招待同人誌イベント一覧表」に掲載されたイベントに無料でサークル参加できます。</li>
    <li>参加費に関係なく、指定されたイベントならどこにでも参加できます。</li>
    <li>ご招待券は入稿後、「ご招待コード」をメールにて送付いたします。<br>
スタジオYOU様主催イベントの場合、イベントご招待申込の際、参加される方のお名前と
「ご招待コード」をご記入くださるだけで完了となります。<br>
<span class="attention">イベント団体により、イベント申込方法は異なります。イベントご招待申込の際、連絡いたします。</span>
    </li>
    <li>ご招待スペースは、１スペースとなっております。<br>
        <span class="attention">追加、合体スペースでのお申し込みもできます（有料）。</span>
    </li>
    <li>スタジオYOU主催イベントへのお申込は「オンラインYOU」でのみ「ご招待コード」を使用できます。</li>
    <li>「ご招待コード」は、入稿されたご本人以外使用できません<small>（入稿者名と参加者名の一致が必要です）</small></li>
    <li>「ご招待コード」の有効期限は、発行後6カ月となっています。</li>
    <li>「ご招待コード」は１回限り有効です。</li>
</ol>

</div><!-- wrap_text -->

<div class="wrap_img">
    <img src="/img/detail_illust_2.png">
</div><!-- wrap_img -->

</div><!-- wrap_text_img -->




<div id="bonus_event_list">
    <h4>ご招待同人誌イベント一覧</h4>

<table>

<tr>
    <th scope="vertical" class="date">日時</th>
    <th scope="vertical" class="place">会場</th>
    <th scope="vertical" class="name">イベント名</th>
    <th scope="vertical" class="genre">ジャンル</th>
</tr>

<tr>
    <td class="date">2021/12/22(日)</th>
    <td class="place">東京ビッグサイト</th>
    <td class="name">サンプルイベント名</th>
    <td class="genre">ジャンル名　○○x■■</th>
</tr>

<tr>
    <td class="date">2021/12/22(日)</th>
    <td class="place">東京ビッグサイト</th>
    <td class="name">サンプルイベント名</th>
    <td class="genre">ジャンル名　○○x■■</th>
</tr>

<tr>
    <td class="date">2021/12/22(日)</th>
    <td class="place">東京ビッグサイト</th>
    <td class="name">サンプルイベント名</th>
    <td class="genre">ジャンル名　○○x■■</th>
</tr>

</table>


</div><!-- bonus_event_list -->

</div><!-- bonus_detail -->


    </div><!-- wrap_bonus_info -->



    <div class="wrap_bonus_info">
    
    <h3><img src="/img/headline/h3_icon_offset_5.png">快適本屋さんでの通販をご希望されない方　<small style="font-size:0.774rem"><i>他書店で専売されている方に好評な特典です</i></small></h3>


        <div class="bonus_detail">

            <p>
                <strong>快適印刷ポイント 何と50倍進呈!</strong>
            </p>

            <ul>
                <li>通常　快適印刷ポイントは1%進呈<br>
                <i>例：入稿料10,000円の場合、100ポイント進呈</i><br>
                （ポイント利用なしの場合、決済手数料除く）</li>
                <li>特典適用の場合は<strong>50倍！</strong><br>
                <i>例：入稿料10,000円の場合、5,000ポイント進呈</i></li>
                <li>快適印刷10ポイント＝1円</li>
                <li>快適印刷ポイントは次回発注分から使用できます。<br>
                <li>快適印刷ポイントは、快適本屋ポイントに移行して「快適本屋さんOnline」で同人誌購入することもできます。</li>
            </ul>

        </div><!-- bonus_detail -->


    </div><!-- wrap_bonus_info -->


    
    <h4 id="price">快適すご盛セット価格表（オフセット　A6・B6・A5）</h4>

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



    <h4 id="price_b5">快適すご盛セット価格表（オフセット　B5）</h4>

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



    <p class="buttons">
        <a href="/order/form?id=<?= $id ?>">
            <img class="button_img" src="/img/button/button_2_form_offset.png" alt="入稿フォーム" width="100%" style="max-width:460px"></a>
    </p>



    <h4>快適すご盛セットご提供一覧</h4>

    <div class="wrap_after_notes">

        <h5>特別印刷価格ご提供：大陽出版様</h5>

        <div class="after_notes_text">

<p>「快適すご盛りセット」は、早い、安い、きれいで定評のある同人誌印刷会社・大陽出版様とのコラボにより生まれました。</p>

<p>大陽出版様が快適印刷さんだけの「特別印刷価格」を組んで提供してくださいました。</p>

<p>それに、「快適印刷さん」がスタジオYOU様、運送会社の皆様、グループの快適本屋さんにお願いして、同人活動が楽しく快適になる、どこにもない大変お得なセットができあがりました。</p>

<p>当セットは、期間を限定して入稿受付させていただきます。</p>

<p>今回、大陽出版様が印刷してくださいますので、ご入稿、ご入金先も大陽出版様となり、発送・納品も同様となります。</p>
  
<p>入稿された原稿に「不備」などがありましたら、大陽出版さまからご連絡が入ります。予め、ご承諾いただけますようお願いいたします。</p>
 
<p>また、入稿時期、仕様変更、印刷後の手直し等、印刷料金に過不足が出た場合も、大陽出版様から連絡が入ります。万一、新たなお支払いが生じた場合、大陽出版様にお支払いください。</p>

<p>快適印刷さんでは、今後も様々な同人誌印刷、イベント、企画会社とコラボして、皆様の同人活動がより快適に、より楽しくなるお手伝いや応援をいたします。入稿をお待ちしています。</p>


        </div><!-- after_notes_text -->



        <h5>イベント招待ご提供：スタジオYOU様</h5>

        <div class="after_notes_text">

<p>イベントご招待につきましては、スタジオYOU様等のお好きなイベントに参加できます。</p>

<p>「ご招待コード」が送信されますので、お申込みの際、お忘れのないようご記入ください。</p>

<p>スタジオYOU様以外のイベントご招待につきましては、決まり次第、当サイト「ご招待同人誌イベント一覧表」にてお知らせいたします。</p>

        </div><!-- after_notes_text -->



        <h5>無料通販提供：快適本屋さん</h5>

        <div class="after_notes_text">

<p>無料増刷や委託の通信販売は、快適本屋さんが担当いたします。</p>

<p>快適本屋さんは、同人誌通販サイトです。ご入稿された方が快適本さんに委託販売の申込をされますと、様々な特典を得ることが出来ます。</p>

<p>快適本屋さんは、ご購入される皆様はもとより、委託作品をお預いただいたサークルの皆様が喜んでいただける様々なサービスを提供いたします。</p>

            </div><!-- after_notes_text -->

        </div><!-- wrap_after_notes -->



    <h4>分納・オプション申込につきまして</h4>

<ul>
    <li>ご自宅発送、イベント搬入、書店発送、在庫保管等で2カ所以上の納品をご希望の場合は、印刷された印刷会社様の規定や料金が適用されます。<br>
<!--
        今回では、「もっと」特典に該当しない方は、2カ所目から有料となります。<br>
-->
        <span class="attention">印刷された印刷会社様のサイト等からお申込みください。</span>
    </li>
    <li>イベント搬入をご希望の場合も、必ず入稿・印刷された印刷会社様の指定する連絡期日までにご連絡をお願いします。</li>
    <li>分納以外のオプション申込につきましても同様に、印刷された印刷会社の規定や料金が適用されます。</li>
</ul>



                </article>



            </section>

<!--	</section> -->

        <?php //$view['side'] ?>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>