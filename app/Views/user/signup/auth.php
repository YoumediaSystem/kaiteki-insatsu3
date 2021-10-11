<?php



const PAGE_NAME = 'メール認証 | 新規登録';

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

	<script src="/js/warning_freemail.js"></script>
</head>

<body>

<?= $view['header'] ?>

<div id="wrapper">

<!--		<section id="main"> -->

        <section class="content">

            <h3 class="heading"><?= PAGE_NAME ?></h3>
            <article>



            <div id="form_area" class="text">


<?php if(!empty($error) && count($error) > 0): ?>
<ul id="error_list" class="attention">
<?php foreach($error as $message) echo '<li>'.$message.'</li>'; ?>
</ul>
<?php endif; ?>
<!-- hash:<?= !empty($hash) ? $hash : '' ?> -->


<form id="form1" method="post" action="/user/signup_mailauth">

<div class="ec-borderedDefs">

<dl>
<dt>
    <h4>メールアドレス</h4>
</dt>
<dd>
    <div class="ec-input">
        <input type="email" name="mail" value="<?= htmlspecialchars($mail ?? '', ENT_QUOTES) ?>" class="width_full" require="require">

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->



</div><!-- ec-borderedDefs -->



<div class="text-center buttons">

<button id="go_next" class="ec-blockBtn--action">認証メール送信</button>

</div><!-- text-center -->



</form>



</div><!-- form_area -->



            </article>
        </section>

    <?php // $view['side'] ?>

</div><!-- wrapper -->

<?= $view['footer'] ?>

<script>


var global = global || {};

var array_target = [];

array_target[0] = 'input[name="mail"]';

warning_freemail(array_target); // フリーメールドメインが入力されたら警告表示する

/*
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
*/



</script>

</body>
</html>