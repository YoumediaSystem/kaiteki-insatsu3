<?php

const PAGE_NAME = 'メールアドレス変更申請しました';

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
    <meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">

    <title><?= PAGE_NAME ?> | <?= $site['name'] ?></title>

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



<h2 class="heading"><?= PAGE_NAME ?></h2>

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

        <p>弊社にて内容確認後、相違なければ認証メールを送信いたします。着信後は24時間以内に、メール内のURLをクリックしてください。</p>

        <p>着信後24時間以上経過した場合はお手数ですが、再度ご申請ください。</p>

        <p>5営業日後も認証メールが届かない場合は、迷惑メールボックスをご確認の上、お問合せください。</p>

    </div><!-- form_area -->


</div><!-- wrapper -->

<?= $view['footer'] ?>



</body>
</html>