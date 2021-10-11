<?php

include_once(__DIR__.'/_config.php');

const PAGE_NAME = '入稿フォーム';

?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
	<meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">

	<title>正常送信完了 | <?= PAGE_NAME ?> | <?= $site['name'] ?></title>

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



<h2 class="heading">正常送信完了 | <?= PAGE_NAME ?></h2>

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

	<p>お問合せは正常に完了しました。</p>

	<p>入力いただいたメールアドレス宛に、お問合せ内容が自動送信されておりますので、必ずご確認ください。<br class="pc_mid_only">
	こちらが届かない場合、お手数ですが再度<a href="/contact/index">お問合せフォームからお問い合わせください。</a>


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