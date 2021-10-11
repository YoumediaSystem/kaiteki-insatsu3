<?php

const PAGE_NAME = 'メールアドレス忘れ申請フォーム';

$checked	= ' checked="checked"';
$selected	= ' selected="selected"';
$disabled	= ' disabled="disabled"';
$hidden		= ' style="display:none"';

$DT = new DateTime();
$now_y = (int)$DT->format('Y');

$select_y = range($now_y - 100, $now_y + 1);
$select_m = range(1,12);
$select_d = range(1,31);

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

	<script src="/js/warning_freemail.js"></script>
</head>

<body>

	<?= $view['header'] ?>

	<div id="wrapper">

		<section class="content">




<h2 class="heading"><?= PAGE_NAME ?></h2>

<style>

<?php for($i=1; $i<20; $i++): ?>
<?= '.digit-'.$i ?> {width:<?= $i+1 ?>em !important;}
<?php endfor; ?>

</style>



<div id="form_area" class="text">



<?php if(!empty($error) && count($error) > 0): ?>
<ul id="error_list" class="attention">
<?php foreach($error as $message) echo '<li>'.$message.'</li>'; ?>
</ul>
<?php endif; ?>



<form id="form1" method="post" action="/user/forget_mail_mailsend">
<input type="hidden" name="mode" value="forget_mail">


<p class="attention">氏名カナ・電話番号・生年月日は、登録時の情報をご入力ください。</p>

<div class="ec-borderedDefs">

<dl>
<dt>
    <h4>変更希望するメールアドレス</h4>
    <span class="input_must">必須</span>
</dt>
<dd>
    <div class="ec-input">
        <input type="text" name="mail_address" value="<?= $mail_address ?? '' ?>" placeholder="name@domain.jp">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt>
    <h4>氏名カナ</h4>
    <span class="input_must">必須</span>
</dt>
<dd>
    <div class="ec-input">
        <input type="text" name="sei_kana" value="<?= $sei_kana ?? '' ?>" class="digit-6" placeholder="姓カナ">
        <input type="text" name="mei_kana" value="<?= $mei_kana ?? '' ?>" class="digit-6" placeholder="名カナ">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4 class="ec-label required">電話番号</h4><span class="input_must">必須</span></dt>
<dd>
	<div class="ec-input">
		<input type="text" name="tel" value="<?= $tel ?? '' ?>" class="digit-10" placeholder="0123456789">

		<span class="attention" style="display:inline-block"> ハイフン不要</span>
	</div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<?php $key = 'birthday';

$birthday_y = $birthday_y ?? $now_y;
$birthday_m = $birthday_m ?? 1;
$birthday_d = $birthday_d ?? 1;

?>

<dl>
<dt>
    <h4>生年月日</h4>
    <span class="input_must">必須</span>
</dt>
<dd>
<div class="ec-select">

<?php $kkey = $key.'_y'; ?>
<select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-6">
    <option value="">-</option>
    <?php foreach($select_y as $val):
        $prop = ($val == $birthday_y) ? $selected : ''; ?>
        <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
    <?php endforeach; ?>
</select>

<?php $kkey = $key.'_m'; ?>
<select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
    <option value="">-</option>
    <?php foreach($select_m as $val):
        $prop = ($val == $birthday_m) ? $selected : ''; ?>
        <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
    <?php endforeach; ?>
</select>

<?php $kkey = $key.'_d'; ?>
<select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
    <option value="">-</option>
    <?php foreach($select_d as $val):
        $prop = ($val == $birthday_d) ? $selected : ''; ?>
        <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
    <?php endforeach; ?>
</select>

</div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->


</div><!-- ec-borderedDefs -->



<div class="text-center buttons">

	<button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="confirm">申請する</button>

</div><!-- text-center -->



</form>

</div><!-- form_area -->


<script type="text/javascript">

var global = global || {};

var array_target = [];

array_target[0] = 'input[name="mail_address"]';
//array_target[1] = 'input[name="product_mail_address"]';

warning_freemail(array_target); // フリーメールドメインが入力されたら警告表示する


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



<script>

var global = global || {};

</script>



<?= $view['footer'] ?>



</body>
</html>