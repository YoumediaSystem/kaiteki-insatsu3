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
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
    <meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
    <title><?= $site['name'] ?></title>
    <meta name="description" content="<?= $site['name'] ?>のサイトです。簡単見積もり簡単入稿！イベント間際の極道入稿も大歓迎です！">
    <meta name="keywords" content="<?= $site['name'] ?>,オンライン入稿支援">

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" type="text/css" media="all" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>

    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>

    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

</head>

<body>

    <?= $view['header'] ?>

    <div id="wrapper">

<style>

    .text-center {
        text-align:center;
    }

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

    #wrap_outline {
        position:relative;
        box-sizing:border-box;
/*				border: 1px solid #000;*/
        width:100%;

        display: grid;
        grid-template-columns:460px 460px;
        grid-column-gap: 40px;

        text-align:left;
    }

    #wrap_offset {
        grid-row: 1; grid-column: 1;
/*        background-color:#eff6ee;*/
        border:1px solid #20ba94;
        border-bottom:none;
    }

    #wrap_ondemand {
        grid-row: 1; grid-column: 2;
/*        background-color:#fcf3e2;*/
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
/*
        #wrap_offset img, #wrap_ondemand img
        {
            width:100%;
            max-width:440px;
        }
*/
    }

    #wrap_offset .limited_dates b {
        font-size:1.75rem;
        color:#20ba94;
    }

    #wrap_ondemand .limited_dates b {
        font-size:1.75rem;
        color:#ffa337;
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

    #wrap_outline em {
        font-size:1.13rem;
        background:none;
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

    #main_header_sp {
        margin:1rem 0;
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

    .outline_text h3 {
        position: relative;
    }

    .outline_text h3 span {
        position: absolute;
        top: 10px;
        left: 138px;
        font-size: 1.13rem;
        font-weight: bold;
    }

</style>

        <img src="/img/header_mid.png" width="100%" style="max-width:500px; margin:0 auto;" class="mid_sp_only">
<!--
        <img id="main_header" src="/img/main_header.png" width="100%" style="max-width:960px" alt="" class="pc_mid_only">
-->
        <div id="main_header_sp">

            <p>
                <b class="color_green">快適印刷さんOnline</b>は、
                同人誌印刷会社、同人誌イベント団体、コスプレ団体、同人関連の企画・制作会社の方々とコラボして、お得な印刷セット・パックをご提供し、皆様の<b class="color">創作活動</b>が、<b>もっと楽しく、もっとお得に、もっと豊かに、もっと</b><b class="color">快適に</b><b>なるお手伝い</b>をさせていただく同人誌入稿サイトです。<b>ご入稿はすべてOnlineで承ります。</b>
            </p>

        </div>

        <img src="/img/banner/banner_index.png" width="100%" style="max-width:960px;" class="pc_mid_only">

        <img src="/img/banner/banner_index_sp.png" width="100%" style="max-width:960px;" class="sp_only">

        <img src="/img/banner/baloon_index2_sp.png" width="100%" style="max-width:640px;" class="sp_only">

        <div id="wrap_baloon" style="display:none">
            <img src="/img/baloon.png" width="100%" style="max-width:440px; display:block; margin:0 auto" alt="">
        </div>

        <div id="wrap_outline">

            <div id="wrap_offset">
            
                <h2>
                    <img src="/img/headline/h2_offset.png" width="100%" style="max-width:460px" alt="オフセット印刷">
                </h2>

                <div class="outline_text">

                    <img src="/img/headline/h2_product_offset.png" width="100%" style="max-width:460px" alt="すご盛セット">
                    
                    <p class="text-center limited_dates">
                    <img src="/img/headline/h_limited.png" width="240" alt="期間限定販売"><br>
                    <b>2021/10/1～2021/11/30</b>
                    </p>
<!--
                    <p class="rest_products"><span><?=
                    $rest_offset
                    ? '残り <b>'.$rest_offset.'</b> セット'
                    : '完売しました'
                    ?></span></p>
-->
                    <h3>
                        <img src="/img/headline/h3_offset_1.png" width="100%" style="max-width:440px" alt="なんと">
                        <span>丁寧、きれいな仕上がり印刷価格</span>
                    </h3>

                    <p>
                        <strong>水曜日午前10時〆→金曜日発送</strong>
                    </p>

                    <p>
                        表紙・本文同時入稿<br>
                        B5なら、 16ページ・30冊で<strong>【23,400円（税込）】</strong>
                        <!--
                        <br>
                        ＆<b style="font-size:1.13rem"><i>発注部数の</i>10%<i>を</i>無料増刷</b>
-->
                    </p>

                    <p class="wrap_link">
                        <a href="/outline_offset#price">
                            <img src="/img/button/button_price.png" alt="価格表はこちら"></a>
                    </p>

                    <!-- －－－－－－－－－－－－－－－－ -->

                    <h3>
                        <img src="/img/headline/h3_offset_2.png" width="100%" style="max-width:440px" alt="さらに1">
                        <span>無料増刷</span>
                    </h3>

                    <p>
                        <strong>快適本屋さんで通信販売される方限定！<br>
発注部数の10％を無料増刷プレゼント</strong><br>
                        <em>例・100部発注なら10部無料進呈</em>
                    </p>

                    <ul>
                        <li>さらに、委託手数料無料！通販売上は全てお支払い！</li>
                        <li>しかも、100部以上の発注なら審査なしで販売開始します</li>
                        <li>無料増刷プレゼントは、オフセット印刷された方が快適本屋さんの通販の委託申込をされる方にのみ提供されるサービスです。</li>
                    </ul>

                    <!-- －－－－－－－－－－－－－－－－ -->

                    <h3 class="after_text">
                        <img src="/img/headline/h3_offset_3.png" width="100%" style="max-width:440px" alt="さらに2">
                        <span>無料納品</span>
                    </h3>

                    <p>
                        <strong>全国どこでも納品1カ所送料無料です</strong>
                    </p>

                    <ul>
                        <li>分納2カ所目から、1カ所毎に＋1500円となります</li>
                        <li>印刷所から「快適本屋さん」への納品送料も無料です</li>
                        <li>納品先は国内に限ります</li>
                    </ul>

                    <!-- －－－－－－－－－－－－－－－－ -->

                    <h3 class="after_text">
                        <img src="/img/headline/h3_offset_4.png" width="100%" style="max-width:440px" alt="さらに3">
                        <span>同人誌即売会ご招待</span>
                    </h3>

                    <p><strong>スタジオYOU主催など、<br>
                    全国の同人誌即売会にご招待</strong></p>

                    <ul>
                        <li>サークル参加１SP無料</li>
                        <li>無料ご招待イベントは<a href="/outline_offset">詳細ページ</a>でご確認ください</li>

                    </ul>

                    <!-- －－－－－－－－－－－－－－－－ -->

                    <h3 class="after_text">
                        <img src="/img/headline/h3_offset_5.png" width="100%" style="max-width:440px" alt="もっと">
                        <span>他書店専売の方に！</span>
                    </h3>

                    <p>快適本屋さんへの通販を希望されない方限定<br>
                        <strong><span>快適印刷ポイント 何と50倍進呈!</span></strong>
                    </p>

                    <ul>
                        <li>通常　快適印刷ポイントは1%進呈<br>
                        <i>例：入稿料10,000円の場合、100ポイント進呈</i></li>
                        <li>特典適用の場合は<strong>50倍！</strong><br>
                        <i>例：入稿料10,000円の場合、5,000ポイント進呈</i></li>
                        <li>快適印刷10ポイント＝1円</li>
                        <li>快適印刷ポイントは次回発注分から使用できます。<br>
                        <li>快適印刷ポイントは、快適本屋ポイントに移行して「快適本屋さんOnline」で同人誌購入することもできます。</li>
                    </ul>

                </div><!-- outline_text -->
            </div><!-- wrap_offset -->

            <div id="wrap_offset_bottom">

                <img src="/img/index_bottom_offset.png" width="100%" style="max-width:460px" alt="詳しい内容・入稿方法を見る">

                <a href="/outline_offset">
                <img src="/img/button/button_2_detail_offset.png" width="100%" style="max-width:460px" alt="オフセット印刷セット詳細"></a>

            </div><!-- wrap_offset -->

            <div id="wrap_ondemand">

                <h2>
                    <img src="/img/headline/h2_ondemand.png" width="100%" style="max-width:460px" alt="オンデマンド印刷">
                </h2>

                <div class="outline_text">

                    <img src="/img/headline/h2_product_ondemand.png" width="100%" style="max-width:460px" alt="すご盛パック">
                    
                    <p class="text-center limited_dates">
                    <img src="/img/headline/h_limited.png" width="240" alt="期間限定販売"><br>
                    <b>2021/10/1～2021/11/30</b>
                    </p>
<!--
                    <p class="rest_products"><span><?=
                    $rest_offset
                    ? '残り <b>'.$rest_ondemand.'</b> セット'
                    : '完売しました'
                    ?></span></p>
-->
                    <h3>
                        <img src="/img/headline/h3_ondemand_1.png" width="100%" style="max-width:440px" alt="なんと">
                        <span>丁寧、きれいな仕上がり印刷価格</span>
                    </h3>

                    <p>
                    <strong>水曜日午前10時〆→金曜日発送</strong>
                    </p>

                    <p>
                        表紙・本文同時入稿<br>
                        B5なら、16ページ・30冊で<strong>【9,600円（税込）】</strong>
                    </p>

                    <p class="wrap_link">
                        <a href="/outline_ondemand#price">
                            <img src="/img/button/button_price.png" alt="価格表はこちら"></a>
                    </p>

                    <!-- －－－－－－－－－－－－－－－－ -->

                    <h3>
                        <img src="/img/headline/h3_ondemand_2.png" width="100%" style="max-width:440px" alt="さらに1">
                        <span>快適本屋ポイントプレゼント</span>
                    </h3>

                    <p>同人誌通販「快適本屋さんOnline」で同人誌のお買い物ができる<br><strong>快適本屋ポイントをプレゼント</strong>
                    </p>

                    <ul>
                        <li>快適本屋ポイントはオンデマンド入稿された全ての皆様にもれなくプレゼントいたします。</li>
                        <li>快適本屋ポイントは印刷価格の1％分を進呈いたします。<br>
                        <b>例・印刷価格10,000円（税込）なら100快適本屋ポイント進呈</b></li>
                        <li>快適本屋1ポイント＝1円相当</li>
                    </ul>

                    <!-- －－－－－－－－－－－－－－－－ -->

                    <h3 class="after_text">
                        <img src="/img/headline/h3_ondemand_3.png" width="100%" style="max-width:440px" alt="さらに2">
                        <span>無料納品</span>
                    </h3>

                    <p><strong>全国どこでも納品1カ所送料無料です</strong>
                    </p>

                    <ul>
                        <li>分納2カ所目から、1カ所毎に＋1500円となります</li>
                        <li>印刷所から「快適本屋さん」への納品送料も無料です</li>
                        <li>納品先は国内に限ります</li>
                    </ul>

                    <!-- －－－－－－－－－－－－－－－－ -->

                    <h3 class="after_text">
                        <img src="/img/headline/h3_ondemand_4.png" width="100%" style="max-width:440px" alt="さらに3">
                        <span>同人誌即売会ご招待</span>
                    </h3>

                    <p>
                        <strong>スタジオYOU主催など、<br>
全国の同人誌即売会にご招待</strong>
                    </p>

                    <ul>
                        <li>サークル参加１SP無料</li>
                        <li>無料ご招待イベントは<a href="/outline_ondemand">詳細ページ</a>でご確認ください</li>
                        <li>ご招待特典を希望されない場合は、入稿料の5%分の快適印刷ポイントをプレゼント</li>
                    </ul>

                    <!-- －－－－－－－－－－－－－－－－ -->

                    <h3 class="after_text">
                        <img src="/img/headline/h3_ondemand_5.png" width="100%" style="max-width:440px" alt="もっと">
                        <span>他書店専売の方に！</span>
                    </h3>

                    <p>快適本屋さんへの通販を希望されない方限定<br>
                        <strong><span>快適印刷ポイント 何と50倍進呈!</span></strong>
                    </p>

                    <ul>
                        <li>通常　快適印刷ポイントは1%進呈<br>
                        <i>例：入稿料10,000円の場合、100ポイント進呈</i></li>
                        <li>特典適用の場合は<strong>50倍！</strong><br>
                        <i>例：入稿料10,000円の場合、5,000ポイント進呈</i></li>
                        <li>快適印刷10ポイント＝1円</li>
                        <li>快適印刷ポイントは次回発注分から使用できます。<br>
                        <li>快適印刷ポイントは、快適本屋ポイントに移行して「快適本屋さんOnline」で同人誌購入することもできます。</li>
                    </ul>

                    </div><!-- outline_text -->
            </div><!-- wrap_ondemand -->

            <div id="wrap_ondemand_bottom">

                <img src="/img/index_bottom_ondemand.png" width="100%" style="max-width:460px" alt="詳しい内容・入稿方法を見る">

                <a href="/outline_ondemand">
                <img src="/img/button/button_2_detail_ondemand.png" width="100%" style="max-width:460px" alt="オンデマンド印刷セット詳細"></a>

            </div><!-- wrap_ondemand_bottom -->

<!--
            <div class="item slick-slide"><a href="/order/?set_id=standard">
                <img src="img/slider/standard.jpg" alt="快適スタンダードセット"></a></div>
            <div class="item slick-slide"><a href="/order/?set_id=ondemand">
                <img src="img/slider/ondemand.jpg" alt="快適オンデマンドセット"></a></div>
-->
        </div>

        <script>
        /*
        $(function() {
            $('#wrap_outline').slick({
                dots: true,
                arrows: false,
                autoplay: true,
                speed: 500
            });
        });
        */
        </script>

    <section class="content">
        <h3 class="heading">快適印刷さんとは？</h3>
        <article class="post">

        <div class="ec-borderedDefs">

            <p>オンラインで印刷入稿できるサービスを提供しております。</p>

            <p>
            入稿フォームではリアルタイムに合計金額をチェックできて、
            お支払いはカード決済・コンビニ・Pay-easy（ペイジー）に対応しております。
            </p>

            <p>入稿には一般のオンラインストレージサービスをお使いいただくことで、
            短納期（たとえば、すご盛セット/パックは水曜10時締切）での受付も可能となっております。FTPソフト設定などの面倒な作業は不要です！</p>

            <p><small>※当サービスは全てオンラインによる入稿のみ受付しております。原稿用紙や物理メディアでの入稿はできません。</small></p>

            <p>私たちは「趣味の生活を楽しむ同人誌即売会参加者の最上級の夢をかなえ、関わる文化の向上と浸透に貢献する」をミッションに、 紙の同人誌作りとそれを頒布する場の提供をはじめ、非日常の交流を支え応援する活動をしております。</p>

        </div>

        </article>
    </section>

    <section class="content">
        <h3 class="heading">お知らせ</h3>
        <article class="post">

        <div class="ec-borderedDefs">

            <dl>
            <dt>
            <h4>2021.3.3</h4></dt>
            <dd>
                快適印刷さんサイトオープン！2つのセットから選んでオンライン入稿できます。
            </dd>
            </dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

        </div>

        </article>
    </section>

<!--
    <section class="gridWrapper">
        <article class="grid">
            <div class="box">
                <img width="320" height="320" src="images/banners/eyecatch1.jpg" alt="" />
                <h3>サンプル株式会社の取り組み</h3>
                <p>ホームページサンプル株式会社の取り組み ホームページサンプル株式会社では最新技術と自然との調和を目指します。革 &#8230;</p>
                <p class="readmore"><a href="sample.html">詳細を確認する</a></p>
            </div>
        </article>
        <article class="grid">
            <div class="box">
                <img width="320" height="320" src="images/banners/eyecatch2.jpg" alt="" />
                <h3>自然との調和を目指す企業</h3>
                <p>ホームページサンプル株式会社の取り組み ホームページサンプル株式会社では最新技術と自然との調和を目指します。革 &#8230;</p>
                <p class="readmore"><a href="sample.html">詳細を確認する</a></p>
            </div>
        </article>
        <article class="grid">
            <div class="box">
                <img width="320" height="320" src="images/banners/eyecatch3.jpg" alt="" />
                <h3>ホームページ株式会社の取り組み</h3>
                <p>ホームページサンプル株式会社の取り組み ホームページサンプル株式会社では最新技術と自然との調和を目指します。革 &#8230;</p>
                <p class="readmore"><a href="sample.html">詳細を確認する</a></p>
            </div>
        </article>
    </section>
-->

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>