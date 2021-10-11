<?php

const PAGE_NAME = 'メールアドレス変更フォーム';

$disabled	= ' disabled="disabled"';

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



<form id="form1" method="post" action="/user/mail_auth_send">
<input type="hidden" name="mode" value="mail">



<div class="ec-borderedDefs">

<dl>
<dt>
    <h4>現在のメールアドレス</h4>
</dt>
<dd>
    <div class="ec-input">
        <input type="hidden" name="old_mail_address" value="<?= $old_mail_address ?? '' ?>">
        <?= $old_mail_address ?? '' ?>
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt>
    <h4>変更希望するメールアドレス</h4>
</dt>
<dd>
<div class="ec-input">
        <input type="email" name="new_mail_address" value="<?= htmlspecialchars($new_mail_address ?? '', ENT_QUOTES) ?>" placeholder="name@domain.jp" required>
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

</div><!-- ec-borderedDefs -->



<div class="text-center buttons">

    <button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="confirm">認証メールを送信する</button>

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