<?php

const PAGE_NAME = 'パスワード変更フォーム';

$disabled = ' disabled="disabled"';

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

button[disabled] {
    background-color:#999;
    cursor:default;
}

</style>

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



<form id="form1" method="post" action="/user/pass_do">
<input type="hidden" name="mode" value="pass">


<div class="ec-borderedDefs">

<dl>
<dt>
    <h4>パスワード</h4>
</dt>
<dd>
    <div class="ec-input">
        <input type="password" id="pass" name="pass" value="">
        <span style="display:inline-block">半角英数字で8～16文字</span>
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->


</div><!-- ec-borderedDefs -->



<div class="text-center buttons">

    <button id="go_next" class="ec-blockBtn--action" name="mode" value="do"<?= $disabled ?>>変更する</button>

</div><!-- text-center -->



</form>

</div><!-- form_area -->


<script type="text/javascript">

var global = global || {};

$('#pass').on('input', function(){
    modButtton();
});

function modButtton() {

    var len = $('#pass').val().length;

    if (len && 8 <= len && len <= 16) {
        $('#go_next').removeAttr('disabled');

    } else {
        $('#go_next').attr('disabled', 'disabled');
    }
}

modButtton();

</script>


        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>