<?php

const PAGE_NAME = '内容確認 | ポイント情報変更';

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

    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>
</head>

<body>

	<?= $view['header'] ?>

	<div id="wrapper">

        <section class="content">

        <h3 class="heading"><?= PAGE_NAME ?><?= $b_new ? '　新規追加' : '' ?></h3>

<style>

button[disabled] {
    background-color:#999;
}

table {
    width:calc(100% - 2em);
}

</style>



<?php if(!$b_new): ?>
    <h4>ポイントno.<?= $id ?? '' ?></h4>
<?php endif; ?>



<form id="form1" method="post" action="/admin/product_form">
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
        <input type="hidden" name="status" value="<?= $status ?? '' ?>">
        <?= $statusName[$status] ?? '' ?>
    </td>
</tr>

<tr>
    <th>増減ポイント数</th>
    <td>
        <input type="hidden" name="point" value="<?= $point ?? 0 ?>">
        <?= number_format($point) ?? 0 ?>pt
    </td>
</tr>

<tr>
    <th>ポイント内容</th>
    <td>
        <input type="hidden" name="detail" value="<?= $detail ?? '' ?>">
        <?= $detail ?? '' ?>
    </td>
</tr>

<tr>
    <th>決済ID</th>
    <td>
        <input type="hidden" name="payment_id" value="<?= $payment_id ?? '' ?>">
        <?= !empty($payment_id) ? $payment_id : '(なし)' ?>
    </td>
</tr>

<tr>
    <th>管理備考</th>
    <td>
        <input type="hidden" name="note" value="<?= $note ?? '' ?>">
        <?= $note ?? '' ?>
    </td>
</tr>

</table>



<div class="text-center buttons">

    <button type="button" id="go_prev" class="ec-blockBtn--cancel" name="mode" value="form">再編集</button>

    <button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="do">編集確定</button>

</div><!-- text-center -->

                </form>


            
            </div><!-- form_area -->

        </section>

	</div><!-- wrapper -->

	<?= $view['footer'] ?>

<script type="text/javascript">

var global = global || {};

$('#go_prev').on('click', function(){

    $('#form1').attr('action','/admin/point_edit');
    $('#form1').submit();
});

$('#go_next').on('click', function(){

    $('#form1').attr('action','/admin/point_do');
    $('#form1').submit();
});

</script>

</body>
</html>