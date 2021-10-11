<?php

const PAGE_NAME = 'メール雛形編集';

$checked	= ' checked="checked"';
$selected	= ' selected="selected"';
$disabled	= ' disabled="disabled"';
$hidden		= ' style="display:none"';

?><!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
    <meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
    <title><?= PAGE_NAME ?> |【管理】<?= $site['name'] ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" type="text/css" media="all" href="/css/style.css">
    <link rel="stylesheet" type="text/css" media="all" href="/css/form.css">
    <link rel="stylesheet" type="text/css" media="all" href="/css/form_admin.css">

<style>

.text-right {
    text-align:right;
}

h4 {
    margin: 1em 0;
    text-align:center;
}

td input.width_full,
td textarea.width_full {
    width:calc(100% - 1.2em);
}

table {
    width:calc(100% - 2em);
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
            <article class="post">

<p>
<a href="/admin/mail_template">テンプレート検索</a>
／<a href="/admin/mail_sendlist">送信予約管理</a>
</p>


<?php if(!empty($result_message)): ?>
    <p><em><?= $result_message ?></em></p>
<?php endif; ?>

<?php if(!empty($error) && count($error) > 0): ?>
    <ul id="error_list" class="attention">
    <?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
    </ul>
<?php endif; ?>



<form id="form1" method="post" action="/admin/mail_template_confirm">
<input type="hidden" name="mode" value="<?= $mode ?? '' ?>">
<input type="hidden" name="id" value="<?= $id ?? '' ?>">

<table>

<tr>
    <th>メール件名</th>
    <td><input type="text" name="subject" value="<?= $subject ?? '' ?>" placeholder="メール件名" class="width_full"></td>
</tr>

<tr>
    <th>本文</th>
    <td><textarea name="body" class="width_full"><?= $body ?? '' ?></textarea></td>
</tr>

</table>


<div class="text-center buttons">

    <button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="confirm">内容確認</button>

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

</body>
</html>