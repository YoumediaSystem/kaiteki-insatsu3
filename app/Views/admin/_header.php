
<header id="header" role="banner" class="admin">

<style>

#header h1 {
    font-size:1.5rem;
    font-weight:bold;
}

#header {
    background-image:none;
    background-color:transparent;
}

#footer, #copyright {
    background-color:transparent;
}

</style>

    <div class="logo">
        <h1>
            <a href="/admin/index">快適印刷さん 管理サイト</a>
        </h1>
    </div>

    <div class="info">

        <!-- <?= $environ ?> -->
        <!-- <?= ENVIRONMENT ?> -->
        <?php
            if($environ == 'local') echo '<p class="env"><b>ローカル環境</b></p>';
            if($environ == 'test') echo '<p class="env"><b>テスト環境</b></p>';
        ?>

        <?php if(!empty($admin['name']) && empty($b_mente)): ?>
            <p>
                <a href="/admin/admin"><?= $admin['name'] ?></a>様
                ｜<a href="/admin/logout">ログアウト</a>

                <?php if(!empty($admin['notice'])): ?>
                    <br>
                    <strong><?= $admin['notice'] ?></strong>
                <?php endif; ?>
            </p>
        <?php elseif(empty($b_mente)): ?>
            <p>
                <a href="/admin/login">ログイン</a>
            </p>
        <?php endif; ?>

<?php
if (empty($testdate)) $testdate = '';

$DT = new \Datetime($testdate);
?>
        <p>現在時刻：<?= $DT->format('Y/m/d H:i:s') ?></p>
    </div>

</header>

<nav id="mainNav">
    <div class="inner">
        <a class="menu" id="menu"><span>MENU</span></a>
        <div class="panel">

            <ul>
                <li><a href="/admin/user"><strong>会員管理</strong><span>User</span></a></li>

                <li><a href="/admin/order"><strong>受注管理</strong><span>Order</span></a></li>

                <li><a href="/admin/product"><strong>商品管理</strong><span>Product</span></a></li>

                <li><a href="/admin/limit"><strong>締切管理</strong><span>Limit</span></a></li>

<?php if(!empty($admin['role']) && $admin['role'] == 'master'): ?>
                <li><a href="/admin/mail_template"><strong>メール管理</strong><span>Mail</span></a></li>

                <li><a href="/admin/admin"><strong>システム管理</strong><span>Admin</span></a></li>
<?php endif; ?>

<!--
                <li><a href="/guide"><strong>ご利用ガイド</strong><span>HowToUse</span></a></li>
                
                <li><a href="/limits"><strong>締切情報</strong><span>Limits</span></a></li>

                <li><a href="/outline_offset"><strong>オフセット入稿</strong><span>Offset</span></a></li>

                <li><a href="/outline_ondemand"><strong>オンデマンド入稿</strong><span>Ondemand</span></a></li>

                <li><a href="/contact"><strong>お問合せ</strong><span>Contact</span></a></li>
-->
            </ul>

        </div>
    </div>
</nav>
