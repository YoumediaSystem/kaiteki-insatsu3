<?php

const PAGE_NAME = '入稿一覧';

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



<?php if(!empty($result_message)): ?>
    <p><em><?= $result_message ?></em></p>
<?php endif; ?>

<?php if(!empty($error) && count($error) > 0): ?>
    <ul id="error_list" class="attention">
    <?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
    </ul>
<?php endif; ?>


<?php if(isset($order_list) && count($order_list)): ?>

<table>

<tr>
<th>no.</th>
<th>本のタイトル</th>
<th>入金期限</th>
<th>発注状況</th>
</tr>

<?php

$DT_now   = new \Datetime();

foreach($order_list as $order):
    
    $DT_limit = new \Datetime($order['payment_limit']); ?>

<tr>
<td class="number"><?= $order['id'] ?></th>
<td><a href="/order/detail?id=<?= $order['id'] ?>"><?= $order['print_title'] ?></a></td>
<td><?= $DT_limit->format('Y/n/j H時まで'); ?></td>
<td><?= $order['status_name'] ?? '' ?><?php

if ($order['status_name'] == '未入金'
&&  $DT_now < $DT_limit
): ?>

　<a href="/payment/form?order_id=<?= $order['id'] ?>">お支払い手続きする＞＞</a>

<?php endif; ?></td>
</tr>

<?php endforeach; ?>

</table>

<?php else: ?>

<p>入稿は現在ありません。</p>

<?php endif; // $order_list ?>




<p class="text-right">
    <a href="/user/mypage">マイページ＞＞</a>
</p>



            </article>
        </section>


    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>