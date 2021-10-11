<?php

const PAGE_NAME = 'お支払い';

$action_name = in_array($type, ['point','card',2]) ? '決済完了' : '決済予約完了';

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
    <meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">

    <title><?= $action_name ?> | <?= PAGE_NAME ?> | <?= $site['name'] ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/form.css">

    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>

    <script src="/js/backbutton_blocker.js"></script>
</head>

<body>

    <?= $view['header'] ?>

    <div id="wrapper">

        <section class="content">



<h2 class="heading"><?= $action_name ?> | <?= PAGE_NAME ?></h2>

<style>

#buttons_lang, #language {
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

<?php for($i=1; $i<20; $i++): ?>
<?= '.digit-'.$i ?> {width:<?= $i+1 ?>em !important;}
<?php endfor; ?>

</style>



<div id="form_area" class="text">

    <p><b><?= $action_name ?>しました。</b></p>

    <p>印刷業者様にてお支払い確認と原稿データ確認が取れ次第、印刷開始となります。</p>

    <?php if($action_name == '決済予約完了'): ?>
    <p>ただし期限までにお支払いが確認できない場合、ご登録いただいた入稿内容は無効となります。</p>
    <?php endif; ?>

    <p>入力いただいたメールアドレス宛に決済メールが自動送信されておりますので、必ずご確認ください。<br class="pc_mid_only">
    こちらが届かない場合、お手数ですが<a href="/contact">お問合せフォームからお問い合わせください。</a>


    </div><!-- form_area -->


<script type="text/javascript">

var global = global || {};
/*
var array_target = [];

array_target[0] = 'input[name="mail_address"]';
//array_target[1] = 'input[name="product_mail_address"]';

warning_freemail(array_target); // フリーメールドメインが入力されたら警告表示する
*/
</script>



</section>



</div><!-- wrapper -->

<?= $view['footer'] ?>


<!--
<script src="//yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
-->
</body>
</html>