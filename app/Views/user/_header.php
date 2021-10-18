<?php

$Config = new \App\Models\Service\Config();
$index = ($Config->isRealOpen()) ? '/' : '/index_test';

?>
<div id="wrap_header">

<header id="header" role="banner" class="user">

    <div class="logo">
        <h1>
            <a href="<?= $index ?>">
                <img src="/img/logo.png" width="210" height="100" alt="<?= $site['name'] ?>" class="pc_only">
                <img src="/img/logo_mid.png" width="290" height="100" alt="" class="mid_only">
                <img src="/img/logo_sp.png" width="210" height="90" alt="" class="sp_only">
            </a>
        </h1>
    </div>

    <div class="info">


        <?php if(!empty($user['name'])):
            
            $header_notice = !empty($user['notice'])
                ? '⚠' : ''; ?>
            <p>
                <a class="mypage" href="/mypage">マイページ<?= $header_notice ?? '' ?></a>
                <a class="logout" href="/logout">ログアウト</a>
            </p>

        <?php elseif(empty($b_mente)): ?>
            <p>
                <a class="login" href="/login">ログイン</a>
                <a class="signup" href="/signup_auth">新規会員登録</a>
            </p>
        <?php endif; ?>


        <!-- <?= $environ ?? '' ?> -->
        <!-- <?= ENVIRONMENT ?? '' ?> -->
        <?php
            if(!empty($environ) && $environ == 'local') echo '<p class="env"><b>ローカル環境</b></p>';
            if(!empty($environ) && $environ == 'test') echo '<p class="env"><b>テスト環境</b></p>';
        ?>

<!--
        <p><small><strong>※テストサイトです。全ての入稿ならびに入金は<br>
        無効とさせていただいております。</strong></small></p>
        <p class="tel"><span>電話:</span> <?= $site['tel'] ?></p>
        <p class="open">受付時間: <?= $site['contact_time'] ?></p>
-->
<?php
if (empty($testdate)) $testdate = '';

$DT = new \Datetime($testdate);
?>
        <p style="display:none">現在時刻：<?= $DT->format('Y/m/d H:i:s') ?></p>
    </div>

</header>

</div><!-- wrap_header -->

<nav id="mainNav">
    <div class="inner">
        <a class="menu" id="menu"><span>MENU</span></a>
        <div class="panel">

            <ul>
                <li><a href="<?= $index ?>"><strong>トップ</strong><span>Top</span></a></li>
                <!--
                <li>
                    <a href="sample.html"><strong>ごあいさつ</strong><span>Greeting</span></a>
                    <ul class="sub-menu">
                        <li><a href="sample.html">代表メッセージ</a></li>
                        <li><a href="sample.html">社員の声</a></li>
                    </ul>
                </li>
                -->
                <li><a href="/guide"><strong>ご利用ガイド</strong><span>HowToUse</span></a></li>
<!--
                <li><a href="/limits"><strong>締切情報</strong><span>Limits</span></a></li>
-->
                <li><a href="/outline_offset"><strong>オフセット入稿</strong><span>Offset</span></a></li>

                <li><a href="/outline_ondemand"><strong>オンデマンド入稿</strong><span>Ondemand</span></a></li>
                <!--
                <li><a href="/order_list"><strong>入稿する</strong><span>Order</span></a></li>
                -->

                <li><a href="/contact"><strong>お問合せ</strong><span>Contact</span></a></li>

                <li><a href="https://kaitekihonya.com/" target="_blank"><strong>快適本屋さんへ</strong><span>to OnlineShop</span></a></li>

            </ul>

        </div>
    </div>
</nav>
