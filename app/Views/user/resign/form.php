<?php

const PAGE_NAME = '退会フォーム';

$checked	= ' checked="checked"';
$selected	= ' selected="selected"';
$disabled	= ' disabled="disabled"';
$hidden		= ' style="display:none"';

$resign_reason_list = (new \App\Models\Service\Config())->getResignReasonList();

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

label {
    display: inline-block;
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



<form id="form1" method="post" action="/user/resign_confirm">

<div class="ec-borderedDefs">

<dl>
<dt>
    <h4 class="ec-label">退会理由</h4>
</dt>
<dd>
    <div class="ec-radio">

        <?php foreach($resign_reason_list as $key=>$val): ?>

            <label>
            <?php $prop = !empty($$key) ? $checked : ''; ?>
            <input type="checkbox" name="<?= $key ?>" value="1"<?= $prop ?>> <?= $val ?>
            </label>
        
        <?php endforeach; ?>
        
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><h4 class="ec-label">その他ご意見・ご要望</h4></dt>
<dd>
    <div class="ec-input">
        <textarea name="comment" class="width_full"><?= htmlspecialchars($comment ?? '', ENT_QUOTES) ?></textarea>
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

</div><!-- ec-borderedDefs -->



<div class="text-center buttons">

    <button id="go_next" class="ec-blockBtn--action" name="mode" value="confirm">確認ページへ</button>

</div><!-- text-center -->



</form>

</div><!-- form_area -->



        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>