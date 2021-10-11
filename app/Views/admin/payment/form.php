<?php

const PAGE_NAME = '決済情報変更フォーム';

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
    <title><?= PAGE_NAME ?> |【管理】<?= $site['name'] ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" type="text/css" media="all" href="/css/style.css">
    <link rel="stylesheet" type="text/css" media="all" href="/css/form.css">
    <link rel="stylesheet" type="text/css" media="all" href="/css/form_admin.css">

<style>

input[name="address1"],
input[name="address2"] {
    margin-top:1em;
}

td input.width_full {
    width:calc(100% - 1.2em);
}

table {
    width:calc(100% - 2em);
}

textarea {
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



            <div id="form_area" class="text">



<?php if(!empty($error) && count($error) > 0): ?>
<ul id="error_list" class="attention">
<?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
</ul>
<?php endif; ?>



<h4>決済no.<?= $id ?? '' ?> 編集</h4>

<form id="form1" method="post" action="/admin/payment_confirm">
<input type="hidden" name="mode" value="modify">
<input type="hidden" name="id" value="<?= $id ?? '' ?>">



<table>

<tr>
    <th>決済状況</th>
    <td>

        <?php $key = 'status'; ?>
        <select name="<?= $key ?>">
            <?php foreach($statusName as $key=>$val):
                $prop = ($key == $status) ? $selected : ''; ?>
                <option value="<?= $key ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr>

<tr>
<th>ペイジェント決済URL</th>
<td><input type="text" name="url" value="<?= htmlspecialchars($url ?? '', ENT_QUOTES) ?>"></td>
</tr>

<tr>
<th>ペイジェント決済ID</th>
<td><input type="text" name="paygent_id" value="<?= $paygent_id ?? '' ?>">

<span class="attention">決済ID登録後は、決済URLは自動削除します</span>
</td>
</tr>

<tr>
<th>管理備考</th>
<td><textarea name="note"><?= htmlspecialchars($note ?? '', ENT_QUOTES) ?></textarea></td>
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