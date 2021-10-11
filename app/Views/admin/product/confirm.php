<?php

const PAGE_NAME = '内容確認 | 商品情報変更';

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

            <h3 class="heading"><?= PAGE_NAME ?></h3>

<style>

button[disabled] {
    background-color:#999;
}

table {
    width:calc(100% - 2em);
}

</style>



            <h4>商品no.<?= $id ?? '' ?></h4>

            <div id="form_area" class="text">

                <form id="form1" method="post" action="/admin/product_do">
                <input type="hidden" name="mode" value="modify">
                <input type="hidden" name="from" value="confirm">
                <input type="hidden" name="id" value="<?= $id ?? '' ?>">

                <table>

<tr>
    <th>ステータス</th>
    <td>
        <input type="hidden" name="status" value="<?= $status ?? '' ?>">
        <?= $statusName[$status] ?? '' ?>
    </td>
</tr>

<tr>
    <th>商品名</th>
    <td>
        <input type="hidden" name="name" value="<?= $name ?? '' ?>">
        <?= $name ?? '' ?>
    </td>
</tr>

<tr>
    <th>商品識別名</th>
    <td>
        <input type="hidden" name="name_en" value="<?= $name_en ?? '' ?>">
        <?= $name_en ?? '' ?>
    </td>
</tr>

<tr>
    <th>販売上限</th>
    <td>
        <input type="hidden" name="max_order" value="<?= $max_order ?? '' ?>">
        <?= $max_order ?? '' ?>
    </td>
</tr>

<tr>
    <th>管理備考</th>
    <td>
        <input type="hidden" name="note" value="<?= $note ?? '' ?>">
        <?= $note ?? '' ?>
    </td>
</tr>

<?php

$DT1 = new \Datetime($open_date ?? '');

$open_date_text = $DT1->format('Y/n/j');

$DT2 = new \Datetime($close_date ?? '');

$close_date_text = $DT2->format('Y/n/j');
?>
<tr>
    <th>販売期間</th>
    <td>
        <input type="hidden" name="open_date" value="<?= $open_date ?? '' ?>">
        <input type="hidden" name="open_date_y" value="<?= $open_date_y ?? '' ?>">
        <input type="hidden" name="open_date_m" value="<?= $open_date_m ?? '' ?>">
        <input type="hidden" name="open_date_d" value="<?= $open_date_d ?? '' ?>">
        <input type="hidden" name="close_date" value="<?= $close_date ?? '' ?>">
        <input type="hidden" name="close_date_y" value="<?= $close_date_y ?? '' ?>">
        <input type="hidden" name="close_date_m" value="<?= $close_date_m ?? '' ?>">
        <input type="hidden" name="close_date_d" value="<?= $close_date_d ?? '' ?>">
        <?= $open_date_text ?> ～ <?= $close_date_text ?>
    </td>
</tr>

</table>



<div class="text-center buttons">

    <button type="button" id="go_prev" class="ec-blockBtn--cancel" name="mode" value="form">再編集</button>

    <button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="confirm">編集確定</button>

</div><!-- text-center -->

                </form>


            
            </div><!-- form_area -->

        </section>

	</div><!-- wrapper -->

	<?= $view['footer'] ?>

<script type="text/javascript">

var global = global || {};

$('#go_prev').on('click', function(){

    $('#form1').attr('action','/admin/product_form');
    $('#form1').submit();
});

$('#go_next').on('click', function(){

    $('#form1').attr('action','/admin/product_do');
    $('#form1').submit();
});

</script>

</body>
</html>