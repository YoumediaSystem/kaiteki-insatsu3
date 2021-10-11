<?php

const PAGE_NAME = '商品詳細';

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

.post td.number {
    text-align:right;
}

input[name="ng_reason"],
textarea[name="ng_reason_other"] {
    width:100%;
}

</style>

    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>
</head>

<body>

    <?= $view['header'] ?>

    <div id="wrapper">

        <section class="content">

            <h3 class="heading">商品no.<?= $product['id'] ?>　詳細</h3>
            <article class="post">



<?php if(!empty($result_message)): ?>
    <p><em><?= $result_message ?></em></p>
<?php endif; ?>

<?php if(isset($error)): ?>
    <ul id="error_list" class="attention">
    <?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
    </ul>
<?php endif; ?>



<form id="form1" method="post" action="/admin/product_form">
<input type="hidden" name="id" value="<?= $product['id'] ?? '' ?>">



<table>

<tr>
<th>商品状況</th>
<td><strong><?= $product['status_name'] ?? '' ?></strong></td>
</tr>

<tr>
<th>提供会社</th>
<td><?= $product['client_name'] ?? '' ?></td>
</tr>

<tr>
<th>商品名（商品識別名）</th>
<td><?= $product['name'] ?? '' ?>（<?= $product['name_en'] ?? '' ?>）</td>
</tr>

<tr>
<th>販売数 ／ 販売上限</th>
<td><?= $product['ordered'] ?? 0 ?> ／ <?= $product['max_order'] ?? 0 ?></td>
</tr>

<?php

$DT = new \Datetime($product['open_date']);
$open_date  = $DT->format('Y/n/j');

$DT = new \Datetime($product['close_date']);
$close_date = $DT->format('Y/n/j');

?>
<tr>
<th>販売期間</th>
<td><?= $open_date ?? '' ?> ～ <?= $close_date ?? '' ?></td>
</tr>

<tr>
<th>管理備考</th>
<td><?= $product['note'] ?? '' ?></td>
</tr>

</table>

<table>

<tr>
<th>登録日時</th>
<td><?= $product['create_date'] ?? '' ?></td>
</tr>

<tr>
<th>更新日時</th>
<td><?= $product['update_date'] ?? '' ?></td>
</tr>

<tr>
<th>更新者</th>
<td><?= $product['admin_name'] ?? '' ?></td>
</tr>

</table>


    <p class="buttons text-center">
        <button>編集</button>
    </p>


    <p class="attention">
        編集できる項目は上記項目となります。（商品細目の編集機能は順次開発予定）
    </p>

    </form>



<form id="form1" method="post" action="/admin/product_update_ordered">
<input type="hidden" name="id" value="<?= $product['id'] ?? '' ?>">

<p class="buttons text-center">
    <button>販売数を更新</button>
</p>

</form>



            </article>
        </section>


    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>