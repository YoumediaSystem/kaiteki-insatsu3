<?php

const PAGE_NAME = 'ポイント情報変更フォーム';

$checked	= ' checked="checked"';
$selected	= ' selected="selected"';
$disabled	= ' disabled="disabled"';
$hidden		= ' style="display:none"';

$DT = new DateTime();
$now_y = (int)$DT->format('Y');

$b_new = empty($id);



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

            <h3 class="heading"><?= PAGE_NAME ?><?= $b_new ? '　新規追加' : '' ?></h3>



            <div id="form_area" class="text">



<?php if(!empty($error) && count($error) > 0): ?>
<ul id="error_list" class="attention">
<?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
</ul>
<?php endif; ?>


<?php if(!$b_new): ?>
    <h4>ポイントno.<?= $id ?? '' ?></h4>
<?php endif; ?>



<form id="form1" method="post" action="/admin/product_confirm">
<input type="hidden" name="mode" value="modify">
<input type="hidden" id="id" name="id" value="<?= $id ?? '' ?>">
<input type="hidden" id="user_id" name="user_id" value="<?= $user_id ?? '' ?>">



<table>

<tr>
    <th>顧客no.<?= $user_id ?? '' ?></th>
    <td><?= $user['name'] ?? '' ?><small>（<?= $user['name_kana'] ?? '' ?>）</small>　合計<?= number_format($user['point']) ?>pt</td>
</tr>

<tr>
    <th>ステータス</th>
    <td>

        <?php $key = 'status'; ?>
        <select name="<?= $key ?>">
            <?php foreach($statusName as $key=>$val):
                $prop = ($key == ($status ?? 0)) ? $selected : ''; ?>
                <option value="<?= $key ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr>

<tr>
    <th>増減ポイント数</th>
    <td><input type="number" name="point" value="<?= $point ?? 0 ?>"></td>
</tr>

<tr>
    <th>ポイント内容</th>
    <td><input type="text" name="detail" value="<?= $detail ?? '' ?>"></td>
</tr>

<tr>
    <th>決済ID</th>
    <td><?php if ($admin['role'] == 'master'): ?>
        <input type="text" name="payment_id" value="<?= $payment_id ?? '' ?>">

    <?php else: ?>
        <input type="hidden" name="payment_id" value="<?= $payment_id ?? '' ?>"><?= $payment_id ?? '' ?>

    <?php endif; ?>
</td>
</tr>

<tr>
    <th>管理備考</th>
    <td><textarea name="note"><?= $note ?? '' ?></textarea></td>
</tr>

</table>


<div class="text-center buttons">

    <button type="button" id="go_prev" class="ec-blockBtn--cancel" name="mode" value="quit">戻る</button>

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

$('#go_prev').on('click', function(){

$('select:disabled').removeAttr('disabled');

    $('#form1').attr('action','/admin/point_detail');
    $('#id').val( $('#user_id').val() );
    $('#form1').submit();
});

$('#go_next').on('click', function(){

    $('select:disabled').removeAttr('disabled');
    $('#form1').attr('action','/admin/point_confirm');
    $('#form1').submit();
});

</script>


        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>