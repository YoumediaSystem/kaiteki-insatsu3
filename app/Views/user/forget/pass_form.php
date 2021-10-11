<?php

const PAGE_NAME = 'パスワード忘れ変更フォーム';

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

<style>

<?php for($i=1; $i<20; $i++): ?>
<?= '.digit-'.$i ?> {width:<?= $i+1 ?>em !important;}
<?php endfor; ?>

</style>


    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>
</head>

<body>

    <?= $view['header'] ?>

    <div id="wrapper">

    <section class="content">

<h3 class="heading"><?= PAGE_NAME ?></h3>



<div id="form_area" class="text">



<?php if(!empty($error) && count($error) > 0): ?>
<ul id="error_list" class="attention">
<?php foreach($error as $message) echo '<li>'.$message.'</li>'; ?>
</ul>
<?php endif; ?>



<form id="form1" method="post" action="/user/forget_pass_do">
<input type="hidden" name="hash" value="<?= $hash ?>">



<div class="ec-borderedDefs">


<dl>
<dt>
    <h4>会員ID</h4>
</dt>
<dd>
    <div class="ec-input">
        <?= $mail_address ?? '' ?>
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
    <dt>
        <h4>新しいパスワード</h4>
    </dt>
    <dd>
        <div class="ec-input">
            <input type="password" name="pass" value="" class="width_full">

        </div>
    </dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->


</div><!-- ec-borderedDefs -->



<div class="text-center buttons">

    <button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="confirm">パスワード変更する</button>

</div><!-- text-center -->



</form>

</div><!-- form_area -->


<script type="text/javascript">

var global = global || {};



// 選択肢1個の場合はロックをかける

$('select').each(function(){

    if ($('option',this).length == 1) {
        $(this).attr('disabled','disabled');
    }
});

// disabled の中身も送信する

$('#go_next').on('click', function(){

    $('select:disabled').removeAttr('disabled');
    $('#form1').submit();
});

</script>


        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

<script src="//yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>

</body>
</html>