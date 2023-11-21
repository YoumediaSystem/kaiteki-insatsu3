<?php

$Product = new \App\Models\DB\ProductSet();

$p_offset = $Product->getFromID(1);
$p_ondemand = $Product->getFromID(2);

unset($Product);

$rest_offset   = $p_offset['max_order'] - $p_offset['ordered'];

$rest_ondemand = $p_ondemand['max_order'] - $p_ondemand['ordered'];

?><!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">

<?php if(!empty($environ) && $environ != 'real'): ?>
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
<?php endif; ?>

    <meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
    <title><?= $site['name'] ?></title>
    <meta name="description" content="<?= $site['name'] ?>のサイトです。簡単見積もり簡単入稿！イベント間際の極道入稿も大歓迎です！">
    <meta name="keywords" content="<?= $site['name'] ?>,オンライン入稿支援">

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" type="text/css" media="all" href="/css/style.css">

    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>

<!--
<link rel="stylesheet" type="text/css" media="screen" href="css/slick/slick.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="css/slick/slick-theme.css"/>
-->
    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>

    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

</head>

<body>

    <?= $view['header'] ?>

    <div id="wrapper">

    <div id="main">

<style>

    .slick-slider {
        margin-bottom: 30px;
    }

    .slick-dots {
        position: absolute;
        bottom: -45px;
        display: block;
        width: 100%;
        padding: 0;
        list-style: none;
        text-align: center;
    }

    .slick-dots li {
        position: relative;
        display: inline-block;
        width: 20px;
        height: 20px;
        margin: 0 5px;
        padding: 0;

        cursor: pointer;
    }

    .slick-dots li button {
        font-size: 0;
        line-height: 0;
        display: block;
        width: 20px;
        height: 20px;
        padding: 5px;
        cursor: pointer;
        color: transparent;
        border: 0;
        outline: none;
        background: transparent;
    }

    .slick-dots li button:hover,
    .slick-dots li button:focus {
        outline: none;
    }

    .slick-dots li button:hover:before,
    .slick-dots li button:focus:before {
        opacity: 1;
    }

    .slick-dots li button:before {
        content: " ";
        line-height: 20px;
        position: absolute;
        top: 0;
        left: 0;
        width: 12px;
        height: 12px;
        text-align: center;
        opacity: .25;
        background-color: black;
        border-radius: 50%;

    }

    .slick-dots li.slick-active button:before {
        opacity: .75;
        background-color: black;
    }

    .slick-dots li button.thumbnail img {
        width: 0;
        height: 0;
    }
/*
    #wrap_outline {
        position:relative;
        box-sizing:border-box;
        width:100%;

        display: grid;
        grid-template-columns:460px 460px;
        grid-column-gap: 40px;

        text-align:left;
    }

    #wrap_offset {
        grid-row: 1; grid-column: 1;
        background-color:#eff6ee;
        border:1px solid #20ba94;
        border-bottom:none;
    }

    #wrap_ondemand {
        grid-row: 1; grid-column: 2;
        background-color:#fcf3e2;
        border:1px solid #ffa337;
        border-bottom:none;
    }

    #wrap_offset_bottom {
        grid-row: 2; grid-column: 1;
    }

    #wrap_ondemand_bottom {
        grid-row: 2; grid-column: 2;
    }

    #wrap_offset_bottom,
    #wrap_ondemand_bottom
    {
        padding-bottom: 3rem;
    }

    .outline_text {
        max-width:440px;
        margin:0 10px;
    }

    h3.after_text
    {
        margin-top:1rem;
    }

    @media screen and (max-width:959px) {
        #wrap_outline {
            display: block;
        }

        #wrap_offset, #wrap_ondemand,
        #wrap_offset_bottom, #wrap_ondemand_bottom
        {
            max-width:460px;
            margin:0 auto;
        }
    }


    #wrap_outline dt {
        font-weight: bold;
        color: #fff;
        border-radius: 0.5em;
        width: 5.5em;
        padding: 0.25em 0.5em;
    }

    #wrap_outline dd + dt {
        margin-top:1em;
    }

    dt.specialty {
        background-color:#00836e;
    }

    dt.merit {
        background-color:#0044cc;
    }

    dt.demerit {
        background-color:#cc00aa;
    }


    #wrap_baloon {
        height:173px;
        overflow-y:hidden;
    }

    .wrap_link {
        text-align:right;
    }

    #wrap_outline strong {
        font-size:1.13rem;
    }

    #wrap_outline strong i {
        font-size:0.875rem;
        color:#222;
    }

    #wrap_outline ul li {
        list-style: none;
        list-style-position: inside;
        background-repeat: no-repeat;
        background-size: 1em 1em;
        background-position: left 0.33em;
        padding-left:1.25em;
    }

    #wrap_offset li {
        background-image: url(/img/listmark_offset.png);
    }

    #wrap_ondemand li {
        background-image: url(/img/listmark_ondemand.png);
    }

    #wrap_outline a img {
        filter: drop-shadow(0.25rem 0.25rem 0.25rem rgba(0,0,0,0.33));
    }


    @media screen and (max-width:959px) {

        #wrap_baloon {
            height:auto;
            overflow-y:visible;
        }

        #main_header {
            margin-top:1rem;
        }
    }

    b.color {
        color:#00ad8d;
    }

    #main_header_sp {
        margin:1rem 0;
        padding: 0.774rem;

        border:0.0125rem solid #1db089;
        border-radius:0.875rem;

        background-color:#fffae6;
    }

    #main_header_sp p + p {
        margin-top:1em;
    }

    p.rest_products {
        text-align:center;
        font-size:1rem;
    }

    p.rest_products span {
        display: inline-block;
        background-color: #fff;
        padding: 0.33rem 0.66rem;
        border-radius: 0.5rem;
    }
*/
    #main {
        padding-bottom:10px;
    }

    #main section {
        width:680px;
        margin:0 auto;
    }

    #main h2.limited,
    #main h2.common {
        border-bottom:none;
    }

    #main h2.common {
        
        margin-top:40px;
        padding-left:1em;

        color:#fff;
        font-weight:bold;
        letter-spacing:0.05em;

        background-image:url(/img/ph2/headline/h_common.png);
        background-repeat:no-repeat;

text-shadow:
2px 0 0 #333,
0 2px 0 #333,
-2px 0 0 #333,
0 -2px 0 #333,
1px 1px 0 #333,
-1px 1px 0 #333,
1px -1px 0 #333,
-1px -1px 0 #333,
0 0 2px #333
;

    }

    #main h2.limited img {
        display:block;
    }

    @media screen and (min-width:725px) {

        .wrap_col2 {
            display:grid;
            grid-template-columns:335px 335px;
            grid-column-gap: 10px;
        }
        
        .wrap_col3 {
            display:grid;
            grid-template-columns:220px 220px 220px;
            grid-column-gap: 10px;
        }
    }

    @media screen and (max-width:724px) {

        #main,
        #main section {
            width:100%;
        }
    }


    .wrap_product {
        margin-bottom:10px;
        background-color:#edf6ec;
        background-image:url(/img/ph2/headline/h_product.png);
        background-position:top left;
        background-repeat:repeat-x;
    }
/*
    .wrap_product,
    .icon_right,
    .icon_right .product_info,
    .icon_right .wrap_product_icon {
        min-height:120px;
    }
*/
    .wrap_product.limited {
        background-color:#fff5fa;
        background-image:url(/img/ph2/headline/h_product_limited.png);
    }

    .wrap_product.ondemand {
        background-color:#fff4e3;
        background-image:url(/img/ph2/headline/h_product_ondemand.png);
    }

    .wrap_product h3 {
        margin: 0;
        padding: 0 0 0 0.5em !important;
        line-height: 26px;

        border: none;
        color: #fff;
        font-weight: bold;
    }

    .product_info {
        padding:0 10px;
        font-size:0.774rem;
    }

    .product_icon {
        display:block;
    }

    .icon_bottom img.product_icon {
        margin:0 auto;
    }

    .icon_right {
        display:grid;
        grid-template-columns:auto 90px;
    }
/*
    .icon_right .product_info,
    .icon_right .wrap_product_icon {
        display:table-cell;
    }
*/
    .icon_right .wrap_product_icon {
        /*
        text-align:center;
        vertical-align:middle;
        */
        display: flex;
        justify-items:center;
        align-items:center;
        padding:10px;
    }

    .icon_right .wrap_product_icon img.product_icon {
        display:inline-block;
    }


    #main_header_sp {
        margin:0 0 1rem;
        padding: 0.774rem;

        border:0.0125rem solid #1db089;
        border-radius:0.875rem;

/*        background-color:#fffae6;*/
    }

    #main_header_sp p + p {
        margin-top:1em;
    }

    #main_header_sp b {
        font-size:1rem;
    }

    b.color_green {
        color:#00ad8d;
    }

    b.color {
        color:#ff4b78;
    }

.slick-arrow{
  position: absolute;
  top: 50%;
  margin-top: -16px;
  width: 20px;
  height: 33px;
  opacity:0.7;
  z-index:10;
}
.prev-arrow{
    cursor:pointer;
	left: 5px;
}
.next-arrow{
    cursor:pointer;
	right: 5px;
}

</style>

<div id="mainBanner">

    <div>
        <a href="/outline_offset">
        <img src="/img/slider/slider_offset_taiyou.jpg" alt="快適すご盛セット"></a>
    </div>

    <div>
        <a href="/outline_ondemand">
        <img src="/img/slider/slider_ondemand_taiyou.jpg" alt="快適すご盛パック"></a>
    </div>

    <div>
        <a href="/outline_offset_pico">
        <img src="/img/slider/slider_offset_pico.jpg" alt="PICOスマートオフセット"></a>
    </div>

    <div>
        <a href="/outline_ondemand_pico">
        <img src="/img/slider/slider_ondemand_pico.jpg" alt="PICOスマートオンデマンド"></a>
    </div>

    <div>
        <a href="/outline_offset_kanbi">
        <img src="/img/slider/slider_offset_kanbi.jpg" alt="RGBカラー表紙パック"></a>
    </div>

</div><!-- mainBanner -->
<script>

$(function() {

    $('#mainBanner').slick({

		autoplay:true,
		autoplaySpeed:5000,
		speed:500,
		slidesToShow: 1,
		fade:true,
		easing:'quart',
		swipeToSlide:false,
        arrows:true,
        prevArrow: '<img src="/img/icon/icon_prev.png" class="slick-arrow prev-arrow">',
        nextArrow: '<img src="/img/icon/icon_next.png" class="slick-arrow next-arrow">',

		responsive: [
			{
				breakpoint: 743,
				settings: {
					autoplay:true,
					autoplaySpeed:5000,
					speed:500,
					slidesToShow: 1,
					fade:false,
					easing:'quart',
					swipeToSlide:true,
					arrows:false
				}
			}
		]
	});

});

</script>

    <section class="content">
        <h3 class="heading">お知らせ</h3>
        <article class="post">

        <div class="ec-borderedDefs">

<!--
<dl>

            <dt data-open_date="20231201"><h4>2023.12.1</h4></dt>
            <dt data-open_date="20231201">

    <dt>【対象】</dt>
    <dd>クレジットカード決済（決済接続先：NTTデータ CAFIS）<br class="pc_mid_only">
        《オーソリ、各種取消、カード情報お預かり機能）》
    </dd>

    <dt>【期間】</dt>
    <dd>2023/12/14(木) 1:00 ～ 6:00<br>
        2023/12/21(木) 1:00 ～ 6:00
    </dd>

    <dt>【影響】</dt>
    <dd>上記時間帯は、お支払い手続きできません。</dd>

    <dd>※リンクタイプ、ペイジェントオンライン、ファイル決済について、<br class="pc_mid_only">
        　上記機能を用いた場合も対象となります。<br>
        ※NTTデータ CAFIS接続の決済が対象となります。
    </dd>

</dl>
-->
<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－ -->
    <dt><h4>更新：2023/11/12</h4></dt><br>
       
    <dt>【対象】</dt>
    <dd>クレジットカード決済（決済接続先：日本カードネットワーク）<br class="pc_mid_only">
        《オーソリ、各種取消、カード情報お預かり機能》</dd>

    <dt>【期間】</dt>
    <dd>2023/11/6(月) 2:00 ～ 8:30</dd>

    <dt>【影響】</dt>
    <dd>上記時間帯の内、2:00 ～ 4:00の間に複数回、異常応答が発生する可能性がございます。<br class="pc_mid_only">
        エラーとなった場合はリトライをお願いいたします。</dd>

<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－ -->
    <dt>【対象】</dt>
    <dd>Google Pay（決済接続先：日本カードネットワーク）<br class="pc_mid_only">
        《オーソリ、各種取消、カード情報お預かり機能》</dd>

    <dt>【期間】</dt>
    <dd>2023/11/6(月) 2:00 ～ 8:30</dd>

    <dt>【影響】</dt>
    <dd>上記時間帯の内、2:00 ～ 4:00の間に複数回、異常応答が発生する可能性がございます。<br class="pc_mid_only">
        エラーとなった場合はリトライをお願いいたします。<dd>

<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－ -->
    <p>下記日程にて<a href="https://www.paygent.co.jp/" target="_blank">決済代行サービス ペイジェント様</a>でメンテナンス作業を実施する為、
        全てお手続きに影響がございます。お早めのお手続きをお願いいたします。</p>

    <dt>【対象】</dt>
    <dd>全ての決済方法</dd>

    <dt>【期間】</dt>
    <dd>2023/11/13(月) 1:00 ～ 5:00</dd>

    <dt>【影響】</dt>
    <dd>上記時間帯は、お支払い手続きできません。<br class="pc_mid_only">
        メンテナンス中のオンラインYOUご利用は控えますようお願い申し上げます。</dd>

<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－ -->
    <p>※2:30 ～ 3:30に予定されておりましたメンテナンスを延期（期日未定）します※（更新：2023/11/12）</p>

    <dt>【対象】</dt>
    <dd>クレジットカード決済（決済接続先：日本カードネットワーク）<br class="pc_mid_only">
        《オーソリ、各種取消、カード情報お預かり機能》</dd>

    <dt>【期間】</dt>
    <dd>2023/11/13(月) 1:00 ～ 5:00</dd>

    <dt>【影響】</dt>
    <dd>作業に伴い、日本カードネットワークから弊社への応答遅延が発生<br class="pc_mid_only">
        （上記メンテナンス期間中に1回。先方作業中止の際は追加で1回（合計2回）発生する可能性がございます。）<br class="pc_mid_only">
        そのタイミングで異常応答を返却する可能性がございます。</dd>

    <dd>※リンクタイプ、ペイジェントオンライン、ファイル決済について、上記機能を用いた場合も対象となります。<br>
        ※日本カードネットワーク接続の決済が対象となります。<br>
        ※2023年9月19日に延期となったメンテナンスの再実施となります。</dd>

<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－ -->
    <dt>【対象】</dt>
    <dd>コンビニ決済（番号方式）<br>
        ※ローソン・ミニストップ</dd>

    <dt>【期間】</dt>
    <dd>2023/11/15(水) 0:30 ～ 4:00</dd>

    <dt>【影響】</dt>
    <dd>ローソンおよびミニストップ店頭のLoppi操作不可<br>
        ローソンおよびミニストップ店頭のお支払い不可</dd>

    <dd>※メンテナンス開始時間までに申込券を出力された場合、発券後の有効期限30分以内まではレジにてお支払いは可能となります。</dd>
<br>
<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－ -->
<dl>
            <dt><h4>更新：2023/10/01</h4></dt><br>

<p>下記日程にて<a href="https://www.paygent.co.jp/" target="_blank">決済代行サービス ペイジェント様</a>でメンテナンス作業を実施する為、
全てお手続きに影響がございます。お早めのお手続きをお願いいたします。</p>

    <dt>【対象】</dt>
    <dd>口座振替決済</dd>

    <dt>【期間】</dt>
    <dd>2023/10/4(水) 20:00 ～ 8:00<br>
        2023/10/31(火) 1:00 ～ 6:00</dd>

    <dt>【影響】</dt>
    <dd>ネット口座振替受付/ネット口座振替受付照会/口座振替受付情報削除/請求/請求補正/請求取消で、P017エラーが返ります。</dd>
</dl>
<br>
<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－ -->

</dd>

<dl>
            <dt data-open_date="20230901"><h4>更新：2023/09/01</h4></dt><br>
            <dd data-open_date="20230901">

<p>下記日程にて<a href="https://www.paygent.co.jp/" target="_blank">決済代行サービス ペイジェント様</a>でメンテナンス作業を実施する為、
全てお手続きに影響がございます。お早めのお手続きをお願いいたします。</p>

<dl>
    <dt>【対象】</dt>
    <dd>口座振替決済</dd>

    <dt>【期間】</dt>
    <dd>2023/9/5(火) 1:00 ～ 8:00</dd>
    <dd>2023/9/26(火) 1:00 ～ 8:00</dd>


    <dt>【影響】</dt>
    <dd>ネット口座振替受付/ネット口座振替受付照会/口座振替受付情報削除/請求/請求補正/請求取消で、P017エラーが返ります。</dd>
</dl>

<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－ -->

<dl>
    <dt>【対象】</dt>
    <dd>マルチペイメントネットワーク（ペイジー）による銀行ネット決済・ＡＴＭ決済</dd>

    <dt>【期間】</dt>
    <dd>2023/9/17(日) 0:00 ～ 6:15</dd>


    <dt>【影響】</dt>
    <dd>決済申込時、P017のエラーが返ります。</dd>
    <dd>対象金融機関で支払ができなくなります（支払の停止）。</dd>
</dl>

<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－ -->

<dl>
    <dt>【対象】</dt>
    <dd>クレジットカード決済（決済接続先：日本カードネットワーク）</dd>
    <dd>※オーソリ、各種取消、カード情報お預かり機能</dd>

    <dt>【期間】</dt>
    <dd>2023/9/19(火) 2:30 ～ 3:30</dd>


    <dt>【影響】</dt>
    <dd>先方作業に伴い通信断が発生。</dd>
    <dd>その影響で異常応答を返却する可能性がございます。</dd>
</dl>

<ul class="attention">
<li>リンクタイプ、ペイジェントオンライン、ファイル決済について、上記機能を用いた場合も対象となります。</li>
<li>日本カードネットワーク接続の決済が対象となります。</li>
</ul>
</dd>
<br>
<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－ -->
<dl>
            <dt data-open_date="20230801"><h4>更新：2023/08/10</h4></dt><br>
            <dd data-open_date="20230801">

<p>下記日程にて<a href="https://www.paygent.co.jp/" target="_blank">決済代行サービス ペイジェント様</a>でメンテナンス作業を実施する為、
全てお手続きに影響がございます。お早めのお手続きをお願いいたします。</p>

<dl>
    <dt>【対象】</dt>
    <dd>カード決済</dd>

    <dt>【期間】</dt>
    <dd>2023/8/7(月) 2:00 ～ 8:30</dd>


    <dt>【影響】</dt>
    <dd>上記時間帯のカード決済や預かりカード情報読込はエラーになる場合があります。その際は、5分以上時間をあけてから再度お試しください。</dd>
</dl>

<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－ -->

<dl>
    <dt>【対象】</dt>
    <dd>コンビニ決済（ローソン・ミニストップ）</dd>

    <dt>【期間】</dt>
    <dd>2023/8/3(木) 0:30 ～ 4:00</dd>


    <dt>【影響】</dt>
    <dd>メンテナンス期間中は、ローソンおよびミニストップ店頭のLoppi操作・店頭お支払いが不可となります。</dd>
</dl>

<ul class="attention">
<li>期間中エラーが出た場合はメンテナンス終了後、再度お手続きください。</li>
<li>メンテナンス中も、他のお支払い方法は平常どおりお手続き可能です。</li>
<li>コンビニ決済メンテナンス前に申込券を出力された場合、出力後30分以内のお支払いは可能です。</li>
</ul>

<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－ -->

<dl>
    <dt>【対象】</dt>
    <dd>コンビニ決済（三菱ＵＦＪニコスの払込票）</dd>

    <dt>【期間】</dt>
    <dd>2023/8/6(日) 19:00 ～ 2023/8/7(月) 4:00</dd>


    <dt>【影響】</dt>
    <dd>決済申込の停止</dd>
</dl>

<ul class="attention">
<li>期間中エラーが出た場合はメンテナンス終了後、再度お手続きください。</li>
<li>メンテナンス中も、他のお支払い方法は平常どおりお手続き可能です。</li>
</ul>
<br>
</dd>
<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－ -->
                <dt><h4>更新：2023/07/31</h4></dt><br>
            
                <dd>

<p>下記日程にて<a href="https://www.paygent.co.jp/" target="_blank">決済代行サービス ペイジェント様</a>でメンテナンス作業を実施する為、
一部お手続きに影響がございます。記載の入金方法をご予定されている方は、お早めのお手続きをお願いいたします。</p>

<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－ -->

<dl>
    <dt>【対象】</dt>
    <dd>カード決済</dd>

    <dt>【期間】</dt>
    <dd>2023/7/21(金) 1:00 ～ 6:00</dd>

    <dt>【影響】</dt>
    <dd>上記時間帯のカード決済はエラーになる場合があります。<br class="pc_mid_only">
その際は、5分以上時間をあけてから再度お試しください。</dd>
</dl>

<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－ -->

<dl>
    <dt>【対象】</dt>
    <dd>コンビニ決済（ローソン・ミニストップ）</dd>

    <dt>【期間】</dt>
    <dd>2023/7/13(木) 0:30 ～ 4:00</dd>

    <dt>【影響】</dt>
    <dd>メンテナンス期間中は、ローソンおよびミニストップ店頭のLoppi操作・店頭お支払いが不可となります。</dd>
</dl>

<ul class="attention">
<li>期間中エラーが出た場合はメンテナンス終了後、再度お手続きください。</li>
<li>メンテナンス中も、他のお支払い方法は平常どおりお手続き可能です。</li>
<li>コンビニ決済メンテナンス前に申込券を出力された場合、出力後30分以内のお支払いは可能です。</li>
</ul>

<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－ -->

                </dd>

                <dt><h4>2022.7.8</h4></dt>
                <dd>
                    新セット
「<a href="/outline_offset_kanbi">RGBカラー表紙パック</a>」を追加！
                </dd>

                <dt><h4>2022.5.5</h4></dt>
                <dd>
                    新セット
「<a href="/outline_offset_pico">PICOスマートオフセット</a>」
「<a href="/outline_ondemand_pico">PICOスマートオンデマンド</a>」を追加！
                </dd>

                <dt><h4>2022.4.6</h4></dt>
                <dd>
                    事前相談をともなう入稿方式に対応いたしました。
くわしくは「<a href="/faq">よくあるご質問</a>」をご覧ください。
                </dd>

                <dt><h4>2021.12.16</h4></dt>
                <dd>
                    快適印刷さんサイトオープン！
「<a href="/outline_offset">快適すご盛セット（オフセット）</a>」
「<a href="/outline_ondemand">快適すご盛パック（オンデマンド）</a>」
2つのセットから選んでオンライン入稿できます。
                </dd>

            </dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

        </div>

        </article>
    </section>



    <section class="content">
        <h3 class="heading">快適印刷さんonlineとは？</h3>
        <article class="post">

        <div id="main_header_sp">

            <p>
                <b class="color_green">快適印刷さんonline β版</b>は、
                同人誌印刷会社、同人誌イベント団体、コスプレ団体、同人関連の企画・制作会社の方々とコラボして、お得な印刷セット・パックをご提供し、皆様の<b class="color">創作活動</b>が、<b>もっと楽しく、もっとお得に、もっと豊かに、もっと</b><b class="color">快適に</b><b>なるお手伝い</b>をさせていただく同人誌入稿サイトです。<b>ご入稿はすべてOnlineで承ります。</b>快適印刷さんonlineはβ版となります。皆さまのご意見やご感想をお待ちしております。
            </p>

        </div>



        <div class="ec-borderedDefs">

            <p>快適印刷さんonlineは、オンラインで自動見積り入稿できるサービスを提供しております。</p>

            <p>
            入稿フォームではリアルタイムに合計金額をチェックできて、
            お支払いはカード決済・コンビニ・Pay-easy（ペイジー）に対応しております。
            </p>

            <p>入稿は<b>東名阪エリア開催イベント合わせなら事前相談不要</b>、
かつ一般のオンラインストレージをお使いいただくことで<b>短納期入稿可能</b>
（たとえば、すご盛セット/パックは火曜12時まで受付可）となっております。</p>

            <p>FTPソフト設定などの面倒な作業は不要で、しかも大きいイベント直前によくある「FTP接続できない」「FTPアップロードが完了できない」心配も無用です！</p>

            <p><small>※当サービスは全てオンラインによる入稿のみ受付しております。原稿用紙や物理メディアでの入稿はできません。また事前相談をご希望の場合はあらかじめ各提供印刷会社様にご相談ください。</small></p>

<!--
            <p>私たちは「趣味の生活を楽しむ同人誌即売会参加者の最上級の夢をかなえ、関わる文化の向上と浸透に貢献する」をミッションに、 紙の同人誌作りとそれを頒布する場の提供をはじめ、非日常の交流を支え応援する活動をしております。</p>
-->
        </div>



        </article>
    </section>



</div><!-- main -->



    <?= $view['side'] ?>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>