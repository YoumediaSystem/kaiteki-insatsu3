<?php

const PAGE_NAME = '内容確認 | 退会フォーム';

$resign_reason_list = (new \App\Models\Service\Config())->getResignReasonList();

$count = 0;
foreach($resign_reason_list as $key=>$val)
    if (isset($$key)) $count++;

?><!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
	<meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
	<title><?= PAGE_NAME ?> | <?= $site['name'] ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="stylesheet" type="text/css" media="all" href="/css/style.css">
	<link rel="stylesheet" type="text/css" media="all" href="/css/form.css">

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



<form id="form1" method="post" action="/user/resign_form">

<div class="ec-borderedDefs">

<dl>
<dt>
    <h4 class="ec-label">退会理由</h4>
</dt>
<dd>
    <div class="ec-input">

        <?php if ($count): ?>
        <ul>
        <?php foreach($resign_reason_list as $key=>$val):
            
            if (!empty($$key)): ?>

            <li>
            <input type="hidden" name="<?= $key ?>" value="1"><?= $val ?>
            </li>
        
        <?php endif; endforeach; ?>
        </ul>
        <?php endif; ?>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<?php if(empty($comment)) $comment = '（特になし）'; ?>
<dl>
<dt><h4 class="ec-label">その他ご意見・ご要望</h4></dt>
<dd>
    <div class="ec-input">
        <input type="hidden" name="comment" value="<?=
        htmlspecialchars($comment, ENT_QUOTES) ?>"><?=
        htmlspecialchars($comment, ENT_QUOTES) ?>
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

</div><!-- ec-borderedDefs -->



<div class="text-center buttons">

    <button type="button" id="go_prev" class="ec-blockBtn--cancel" name="mode" value="form">再編集</button>

    <button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="confirm">退会する</button>

</div><!-- text-center -->



</form>

</div><!-- form_area -->



        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

    <script type="text/javascript">

var global = global || {};

$('#go_prev').on('click', function(){

    $('#form1').attr('action','/user/resign_form');
    $('#form1').submit();
});

$('#go_next').on('click', function(){

    $('#form1').attr('action','/user/resign_do');
    $('#form1').submit();
});

</script>

</body>
</html>