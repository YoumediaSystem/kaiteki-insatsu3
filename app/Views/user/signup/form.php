<?php

const PAGE_NAME = '新規登録フォーム';

$checked	= ' checked="checked"';
$selected	= ' selected="selected"';
$disabled	= ' disabled="disabled"';
$hidden		= ' style="display:none"';

$DT = new DateTime();
$now_y = (int)$DT->format('Y');

$select_y = range($now_y - 100, $now_y + 1);
$select_m = range(1,12);
$select_d = range(1,31);

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

input[name="address1"],
input[name="address2"] {
    margin-top:1em;
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



<form id="form1" method="post" action="/user/signup_confirm">
<input type="hidden" name="mode" value="signup">
<input type="hidden" name="hash" value="<?= $hash ?? '' ?>">



<div class="ec-borderedDefs">

<dl>
<dt>
    <h4>メールアドレス</h4>
</dt>
<dd>
    <div class="ec-input">
        <input type="hidden" name="mail_address" value="<?= $mail_address ?? '' ?>">
        <?= $mail_address ?>
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt>
    <h4>パスワード</h4>
</dt>
<dd>
    <div class="ec-input">
        <input type="password" name="pass" value="<?= $pass ?? '' ?>">
        <span style="display:inline-block">半角英数字で8～16文字</span>
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt>
    <h4>氏名</h4>
    <span class="input_must">必須</span>
</dt>
<dd>
    <div class="ec-input">
        <input type="text" name="sei" value="<?= htmlspecialchars($sei ?? '', ENT_QUOTES) ?>" class="digit-6" placeholder="姓">
        <input type="text" name="mei" value="<?= htmlspecialchars($mei ?? '', ENT_QUOTES) ?>" class="digit-6" placeholder="名">
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
        <input type="text" name="sei_kana" value="<?= htmlspecialchars($sei_kana ?? '', ENT_QUOTES) ?>" class="digit-6" placeholder="姓カナ">
        <input type="text" name="mei_kana" value="<?= htmlspecialchars($mei_kana ?? '', ENT_QUOTES) ?>" class="digit-6" placeholder="名カナ">
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


<?php $sextype = $sextype ?? '未選択'; ?>

<dl>
<dt>
    <h4 class="ec-label">性別</h4>
    <span class="input_must">選択必須</span>
</dt>
<dd>
	<div class="ec-radio">
	
		<label>
        <?php $prop = ($sextype == '女') ? $checked : ''; ?>
        <input type="radio" name="sextype" value="女"<?= $prop ?>> 女
        </label>
		
		<label>
        <?php $prop = ($sextype == '男') ? $checked : ''; ?>
        <input type="radio" name="sextype" value="男"<?= $prop ?>> 男
        </label>

		<label>
        <?php $prop = ($sextype == '未選択') ? $checked : ''; ?>
        <input type="radio" name="sextype" value="未選択"<?= $prop ?>> 未選択
        </label>
		
	</div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->


<dl>
<dt>
	<h4 class="ec-label required">郵便番号・住所</h4>
	<span class="input_must">必須</span>
</dt>
<dd>
	<div class="ec-input">

		<div class="h-adr">
		<span class="p-country-name" style="display:none;">Japan</span>

		<input name="zipcode" type="text" class="p-postal-code digit-8" size="8" maxlength="8" value="<?= $zipcode ?? '' ?>" placeholder="1234567" style="display:inline-block"> 郵便番号をご入力ください<br>

		<input name="address1" type="text" class="p-region p-locality p-street-address p-extended-address width_full" value="<?= htmlspecialchars($address1 ?? '', ENT_QUOTES) ?>" placeholder="都道府県・市区町村（自動入力）">

		<input name="address2" type="text" class="width_full" value="<?= htmlspecialchars($address2 ?? '', ENT_QUOTES) ?>" placeholder="番地・建物名・部屋番号">
		
		</div>
	</div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4 class="ec-label required">電話番号</h4><span class="input_must">必須</span></dt>
<dd>
	<div class="ec-input">
		<input type="text" name="tel" value="<?= htmlspecialchars($tel ?? '', ENT_QUOTES) ?>" class="width_full" placeholder="0123456789">

		<span class="attention" style="display:inline-block"> ハイフン不要、連絡のつく番号をご入力ください</span>
	</div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4 class="ec-label">連絡可能時間帯</h4></dt>
<dd>
	<div class="ec-input">
		<input type="text" name="tel_range" value="<?= htmlspecialchars($tel_range ?? '', ENT_QUOTES) ?>" class="width_full">

		<span class="attention" style="display:inline-block">〇時～〇時、〇時以降、等ご記入ください</span>
	</div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

</div><!-- ec-borderedDefs -->



<div class="text-center buttons">

    <button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="confirm">確認ページへ</button>

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