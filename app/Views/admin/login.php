<?php

const PAGE_NAME = 'ログイン';

?><!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
    <meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
    <title><?= PAGE_NAME ?> |【管理】<?= $site['name'] ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" type="text/css" media="all" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/form.css">
    <link rel="stylesheet" type="text/css" media="all" href="/css/form_admin.css">

    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>
</head>

<body>

    <?= $view['header'] ?>

    <div id="wrapper">

        <section class="content">

            <h2 class="heading"><?= PAGE_NAME ?></h2>
            <article>


<style>

.text-center {
    text-align:center;
}

.text_2_copy {
    display:inline-block;
    background-color:#ffe;
    border:1px solid #996;
}

@media screen and (max-width:729px) {

    .post table th, .post table td {
        display:block;
    }

    .post table th {
        font-size:0.774rem;
        text-align:left;
    }

    .post table td {
        padding-bottom:1.5em;
    }
}

</style>



            <div id="form_area" class="text">


<?php if(!empty($error) && count($error) > 0): ?>
<ul id="error_list" class="attention">
<?php foreach($error as $message) echo '<li>'.$message.'</li>'; ?>
</ul>
<?php endif; ?>



<?php if(empty($b_mente)): ?>

    <form id="form1" method="post" action="/admin/login_do">

    <?php if(!empty($redirect_to)): ?>
    <input type="hidden" name="redirect_to" value="<?= $redirect_to ?>">
    <?php endif; ?>



    <div class="ec-borderedDefs">

    <dl>
        <dt>
            <h4>ID</h4>
        </dt>
        <dd>
            <div class="ec-input">
                <input type="text" name="mail_address" value="<?= $mail_address ?? '' ?>" class="width_full" placeholder="name@domain.jp">

            </div>
        </dd>
    </dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

    <dl>
        <dt>
            <h4>パスワード</h4>
        </dt>
        <dd>
            <div class="ec-input">
                <input type="password" name="pass" value="<?= $pass ?? '' ?>" class="width_full">

            </div>
        </dd>
    </dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

    </div><!-- ec-borderedDefs -->


    <div class="text-center buttons">

        <button id="go_next" class="ec-blockBtn--action">ログイン</button>

    </div><!-- text-center -->

    <hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->

    </form>

<?php endif;// !$b_mente ?>



<h3>お知らせ</h3>

<p>現在お知らせはありません。</p>

<!--
<h4 class="text-center">システムメンテナンスのお知らせ</h4>

<table>
    <tr>
        <th>メンテナンス期間</th>
        <td>2021年12月22日　1:00～6:00</td>
    </tr>

    <tr>
        <th>メンテナンス対象</th>
        <td>全機能</td>
    </tr>

    <tr>
        <th>影響範囲</th>
        <td>メンテナンス期間中は、管理サイト全ての機能をご利用いただけません。</td>
    </tr>
</table>
-->


</div><!-- form_area -->



            </article>
        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>