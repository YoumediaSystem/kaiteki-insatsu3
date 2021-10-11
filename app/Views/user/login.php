<?php



const PAGE_NAME = 'ログイン';

?><!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
    <meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
    <title><?= PAGE_NAME ?> | <?= $site['name'] ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" type="text/css" media="all" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/form.css">

    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>
</head>

<body>

    <?= $view['header'] ?>

    <div id="wrapper">

        <section class="content">

            <h3 class="heading"><?= PAGE_NAME ?></h3>
            <article>



            <div id="form_area" class="text">


<?php if(!empty($error) && count($error) > 0): ?>
<ul id="error_list" class="attention">
<?php foreach($error as $message) echo '<li>'.$message.'</li>'; ?>
</ul>
<?php endif; ?>



<form id="form1" method="post" action="/user/login_do">

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
            <input type="text" name="mail_address" value="<?= $mail_address ?? '' ?>" class="width_full" placeholder="name@domain.jp" require>

        </div>
    </dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
    <dt>
        <h4>パスワード</h4>
    </dt>
    <dd>
        <div class="ec-input">
            <input type="password" name="pass" value="<?= $pass ?? '' ?>" class="width_full" require>

        </div>
    </dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->


</div><!-- ec-borderedDefs -->



<div class="text-center buttons">

    <button id="go_next" class="ec-blockBtn--action">ログイン</button>

</div><!-- text-center -->



</form>



<p class="text-right">
    <a href="/user/forget_pass">パスワードを忘れた方はこちら</a>
</p>

<p class="text-right">
    <a href="/user/forget_mail">メールアドレスを忘れた方はこちら</a>
</p>



</div><!-- form_area -->



            </article>
        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>