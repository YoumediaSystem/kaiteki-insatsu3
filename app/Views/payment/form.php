<?php

const PAGE_NAME = 'お支払いする発注を選択';

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

.buttons {
    text-align:center;
}

button[disabled] {
    cursor:default;
    opacity:0.66;
}

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


<?php if(isset($order_list) && count($order_list)):

$b_first = true;

$before_client = '';
    
$DT_now   = new \Datetime();

    foreach($order_list as $order):
        
        if ($before_client != $order['client_code']):

            if (!$b_first): ?>

            </table>

            <?php endif; // b_first ?>



            <h4><?= $order['client_name'] ?></h4>

            <form id="form_<?= $order['client_code'] ?>" method="post" action="/payment/confirm">

            <table>

            <tr>
            <th>&nbsp;</th>
            <th>発注no. タイトル</th>
            <th>入金期限</th>
            <th>金額</th>
            </tr>

        <?php endif; // !client_code 
        
        $DT_limit = new \Datetime($order['payment_limit']); ?>

            <tr>
            <th>
                <label>
                <input type="checkbox" name="payment_order[]" value="<?= $order['id'] ?>" data-client="<?= $order['client_code'] ?>">
                </label>
            </th>
            <td>no.<?= $order['id'] ?>　<?= $order['print_title'] ?></td>
            <td><?= $DT_limit->format('Y/n/j H時まで'); ?></td>
            <td>￥<?= number_format($order['price']) ?? 0 ?></td>
            </tr>

        <?php
            $before_client = $order['client_code'];

        endforeach; ?>

        </table>



        <p class="buttons">
            <button class="ec-blockBtn--cancel" type="submit" name="mode" value="confirm" disabled="disabled" data-client="<?= $order['client_code'] ?>">内容確認・ポイント利用</button>
        </p>

    </form>

<?php else: ?>

<p>未入金の入稿発注は現在ありません。</p>

<?php endif; // $order_list ?>



            </article>
        </section>


    </div><!-- wrapper -->

    <?= $view['footer'] ?>

<script>

$('input[type=checkbox]').on('input', function(){

    var client = $(this).data('client');
    var b = false;

    $('#form_'+ client +' input').each(function(){
        b = b || $(this).prop('checked');
    });

    if (b) {
        $('#form_'+ client +' button')
        .removeAttr('disabled')
        .attr('class', 'ec-blockBtn--action');

    } else {
        $('#form_'+ client +' button')
        .attr('disabled', 'disabled')
        .attr('class', 'ec-blockBtn--cancel');
    }
});

</script>

</body>
</html>