<?php

const PAGE_NAME = '決済詳細';

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

            <h3 class="heading">決済no.<?= $payment['id'] ?>　詳細</h3>
            <article class="post">


<?php
$a = [];
$result_order_links = '';
if(isset($order_ids) && count($order_ids)) {

    foreach($order_ids as $ids)
        $a[] =
            '<a href="/admin/order_detail?id='.$ids
            .'" target="_blank">受注no.'.$ids
            .'＞＞</a>';
    
    $result_order_links = '　'.implode('　', $a);
}
?>


<?php if(!empty($result_message)): ?>
    <p><em><?= $result_message ?></em><?= $result_order_links ?></p>
<?php endif; ?>


<?php if(!empty($error) && count($error) > 0): ?>
    <ul id="error_list" class="attention">
    <?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
    </ul>
<?php endif; ?>



<?php
/*
$DT_now   = new \Datetime();
$DT_limit = new \Datetime($payment['limit_date']);

$date_text  = $DT_limit->format('Y/n/j');
$date_text .= $youbi[$DT_limit->format('w')];
$date_text .= $DT_limit->format(' H時まで');
*/
?>

<table>

<tr>
<th>決済状況</th>
<td><strong><?= $payment['status_name'] ?? '' ?></strong>
<?php if($payment['status_name'] == '入金確認待ち'): ?>
    <a href="/admin/payment_check_result?id=<?= $payment['id'] ?? '' ?>">最新の入金状況を照会する＞＞</a>

<?php endif; ?>
</td>
</tr>

<tr>
<th>発注者</th>
<td>会員no.<?= $payment['user_id'] ?? '' ?>
　<a href="/admin/user_detail?id=<?= $payment['user_id'] ?? '' ?>" target="_blank">会員詳細＞＞</a></td>
</tr>

<tr>
<th>決済料金</th>
<td>￥<?= number_format($payment['amount']) ?? '' ?></td>
</tr>

<tr>
<th>決済方法</th>
<td><?= $payment['type_name'] ?></td>
</tr>

<tr>
<th>入金期限</th>
<td><?= $payment['limit_date'] ?></td>
</tr>

<tr>
<th>付与ポイント</th>
<td><?= number_format($payment['point_get']) ?? '' ?></td>
</tr>

<tr>
<th>利用ポイント</th>
<td><?= number_format($payment['point_use']) ?? '' ?></td>
</tr>

<?php if(!empty($payment['paygent_id'])): ?>
<tr>
<th>ペイジェント決済ID</th>
<td><?= $payment['paygent_id'] ?></td>
</tr>
<?php endif; ?>

<tr>
<th>管理備考</th>
<td><?= $payment['note'] ?? '' ?></td>
</tr>

</table>


<form id="form1" method="post" action="/admin/payment_form">
<input type="hidden" name="id" value="<?= $payment['id'] ?? '' ?>">

<p class="buttons text-center">
    <button>編集</button>
</p>

</form>


<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<?php

$order_ids_list = [];

if (isset($payment['order']) && count($payment['order'])):

?>

<h4>決済対象状況</h4>

<table style="width:100%">

<tr>
    <th style="width:5em">受注no.</th>
    <th style="width:auto">本のタイトル</th>
    <th style="width:13em">入稿(入金)期限</th>
    <th style="width:6em">ステータス</th>
    <th style="width:auto">備考</th>
</tr>

<?php foreach($payment['order'] as $order): ?>

<tr>
    <th style="width:5em"><?= $order['id'] ?? '' ?></th>
    <td><a href="/admin/order_detail?id=<?= $order['id'] ?>"><?= $order['print_title'] ?? '' ?></a></td>
    <td><?= $order['payment_limit'] ?? '' ?></td>
    <td><?= $order['status_name'] ?? '' ?></td>
    <td><?= $order['note'] ?? '' ?></td>
</tr>

<?php endforeach; ?>

</table>



<?php if(in_array($payment['status'], [10])): ?>

    <form method="post" action="/admin/payment_cancel">
    <input type="hidden" name="id" value="<?= $payment['id'] ?>">

    <?php foreach($order_ids_list as $ids): ?>
        <input type="hidden" name="order_ids[]" value="<?= $ids ?>">
    <?php endforeach; ?>

    <p><button>決済を手動キャンセルする</button></p>

    </form>

<?php endif; // status = 10 ?>

<?php else: ?>
<p class="attention">（対象決済なし）</p>

<?php endif; ?>




            </article>
        </section>


    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>