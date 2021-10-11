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

    <div id="main">

<style>
/*    
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

</style>

<div id="mainBanner">
<img src="img/ph2/slider/sl_offset.jpg" alt="快適スタンダードセット">
</div>

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



    <section class="product_list">
        <article class="post">

<h2 class="limited">
    <img src="/img/ph2/headline/h_limited.png" alt="期間限定キャンペーン">
</h2>

<div class="wrap_col3">

    <div class="wrap_product limited">

        <h3>すご盛りセット</h3>


        <div class="icon_bottom">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">

        </div>
    </div>

    <div class="wrap_product limited">
        
        <h3>生誕・ハピバるセット</h3>


        <div class="icon_bottom">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">

        </div>

    </div>

    <div class="wrap_product limited">
        
        <h3>すぐするセット</h3>


        <div class="icon_bottom">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">

        </div>

    </div>

</div>



<h2 class="common">同人誌基本セット</h2>

<div class="wrap_col2">

    <div class="wrap_product">
        
        <h3>オフセット印刷</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product ondemand">
        
        <h3>オンデマンド印刷</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

</div>



<h2 class="common">同人誌割引セット</h2>

<div class="wrap_col2">

    <div class="wrap_product">
        
        <h3>早割セット</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product">
        
        <h3>イベント合わせセット</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

</div>



<h2 class="common">特殊印刷</h2>

<div class="wrap_col3">

    <div class="wrap_product">
        
        <h3>多色カラー</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product">
        
        <h3>箔押し</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product">
        
        <h3>特殊紙</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

</div>



<h2 class="common">紙印刷</h2>

<div class="wrap_col3">

    <div class="wrap_product">
        
        <h3>チラシ</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product">
        
        <h3>封筒</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product">
        
        <h3>ポストカード</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product">
        
        <h3>ポスター</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product">
        
        <h3>便箋</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product">
        
        <h3>名刺</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product">
        
        <h3>色紙</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

</div>



<h2 class="common">グッズ印刷</h2>

<div class="wrap_col3">

    <div class="wrap_product">
        
        <h3>アクリルキーホルダー</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product">
        
        <h3>缶バッジ</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product">
        
        <h3>マグカップ</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product">
        
        <h3>Tシャツ</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product">
        
        <h3>バッグ</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product">
        
        <h3>ポケットティッシュ</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

</div><!-- wrap_col3 -->



<h2 class="common">季節印刷</h2>

<div class="wrap_col3">

    <div class="wrap_product">
        
        <h3>うちわ</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product">
        
        <h3>カレンダー</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

    <div class="wrap_product">
        
        <h3>年賀状</h3>


        <div class="icon_right">

            <div class="product_info">
                <p>説明説明説明説明説明説明</p>

                <p class="link">
                    <a href="/">詳細を見る＞＞</a>
                </p>
            </div>

            <div class="wrap_product_icon">
                <img class="product_icon" src="/img/ph2/icon/i_books.png" width="60">
            </div>

        </div><!-- icon_right -->
    </div><!-- wrap_product -->

</div>

        </article>
    </section>

</div><!-- main -->



    <?= $view['side'] ?>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>