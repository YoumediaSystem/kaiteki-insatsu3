<?php

const PAGE_NAME = '入稿詳細';

$Config = new \App\Models\Service\Config();

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

            <h3 class="heading">発注no.<?= $order['id'] ?>　詳細</h3>
            <article class="post">



<?php if(!empty($result_message)): ?>
    <p><em><?= $result_message ?></em></p>
<?php endif; ?>

<?php if(!empty($error) && count($error) > 0): ?>
    <ul id="error_list" class="attention">
    <?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
    </ul>
<?php endif; ?>



<?php

$DT_now   = new \Datetime();
$DT_limit = new \Datetime($order['payment_limit']);

$date_text  = $DT_limit->format('Y/n/j');
$date_text .= $youbi[$DT_limit->format('w')];
$date_text .= $DT_limit->format(' H時まで');

?>

<table>

<tr>
<th>本のタイトル</th>
<td><?= $order['print_title'] ?? '' ?></td>
</tr>

<tr>
<th>印刷セット名</th>
<td><?= $order['product_set']['name'] ?? '' ?></td>
</tr>

<?php
$price_text = '￥'.number_format($order['price']);
$org_price_text = !empty($order['org_price'])
? '￥'.number_format($order['org_price'])
: '';
?>
<tr>
<th>発注料金</th>
<td><?php if(!empty($org_price_text) && $org_price_text != $price_text): ?>

<s><?= $org_price_text ?></s> →

<?php endif; ?><b><?= $price_text ?></b>
</td>
</tr>


<?php if(!empty($order['adjust_note_front'])): ?>

<tr>
    <th>調整希望あり</th>
    <td><?= $order['status'] == 12 ?
    ($order['extra_order_note'] ?? '')
:   ($order['adjust_note_front'] ?? '')
?></td>
</tr>

<?php endif; ?>


<tr>
<th>入金期限</th>
<td><?= $date_text ?></td>
</tr>


<?php
if(!empty($order['payment']['type_name'])
&&  $order['payment']['type_name'] != '不明'
): ?>
<tr>
<th>決済方法</th>
<td><?= $order['payment']['type_name'] ?? '' ?></td>
</tr>
<?php endif; ?>


<tr>
<th>発注状況</th>
<td><?= $order['status_name'] ?? '' ?><?php

if ($order['status_name'] == '未入金'
&&  $DT_now < $DT_limit
): ?>

　<a href="/payment/form?order_id=<?= $order['id'] ?>">お支払い手続きする＞＞</a>

<?php endif; 

if ($order['status_name'] == '入金待ち'
&&  $DT_now < $DT_limit
&&  !empty($order['payment']['ex']['url'])
): ?>

　<a href="<?= $order['payment']['ex']['url'] ?>">決済手続再開（ペイジェント）＞＞</a>

<?php endif; 

if ($order['status_name'] == '入金待ち'
&&  $DT_now < $DT_limit
&&  $order['payment']['type'] == 'bank'
&&  !empty($order['payment']['ex']['paygent_id'])
): ?>

<form method="post" action="/payment/reset_bank" style="display:inline-block">
    <input type="hidden" name="payment_id" value="<?= $order['payment']['id'] ?>">
    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
    <button>　決済手続きをやり直す　</button>
</form>

<?php endif; ?>

</td>
</tr>


<?php if(in_array($order['status'],[40,50,60,140,150,160])): ?>

    <tr>
    <th>ご招待コード</th>
    <td><?= $Config->getInviteCode($order['id'], $order['print_title']) ?></td>
    </tr>

<?php endif; ?>

</table>


<?php
if (in_array($order['status_name'], ['未入金','入金待ち','期限切れ'])):
?>
<p class="text-right">
    <a href="/contact/index?order_id=<?= $order['id'] ?>&contact_type=<?= urlencode('お支払いについて') ?>">お支払いに関するお問合せ＞＞</a>
</p>
<?php endif; ?>


<?php
if (!in_array($order['status_name'], ['印刷開始済','返金済'])):
?>
<p class="text-right">
    <a href="/contact/index?order_id=<?= $order['id'] ?>&contact_type=<?= urlencode('入稿発注について') ?>">入稿発注に関するお問合せ＞＞</a>
</p>
<?php endif; ?>


<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<h4>発注仕様</h4>

<table>

<tr>
    <th>原稿データURL</th>
    <td><a href="<?= $order['print_data_url'] ?? '' ?>" target="_blank"><?= $order['print_data_url'] ?? '' ?></a><?=
    !empty($order['print_data_password'])
    ? '（パスワード：'.$order['print_data_password'].'）'
    : ''
    ?></td>
</tr>

<tr>
    <th>総部数（冊数）</th>
    <td><?= $order['print_number_all'] ?? '' ?></td>
</tr>

<tr>
    <th>ページ数</th>
    <td><?= $order['print_page'] ?? '' ?></td>
</tr>

<tr>
    <th>本文始まりページ数</th>
    <td><?= $order['nonble_from'] ?? '3p始まり' ?></td>
</tr>

<tr>
    <th>仕上がりサイズ</th>
    <td><?= $order['print_size'] ?? '' ?></td>
</tr>

<tr>
    <th>製本（とじ方向）</th>
    <td><?= $order['binding'] ?? '' ?>（<?= $order['print_direction'] ?? '' ?>）</td>
</tr>

<tr>
    <th>表紙</th>
    <td>

        <dl>
            <dt>用紙</dt>
            <dd><?= $order['cover_paper'] ?? '' ?></dd>

            <dt>印刷色</dt>
            <dd><?= $order['cover_color'] ?? '' ?></dd>
            
            <dt>基本加工</dt>
            <dd><?= $order['cover_process'] ?? '' ?></dd>
        </dl>

    </td>
</tr>

<tr>
    <th>本文</th>
    <td>

        <dl>
            <dt>用紙</dt>
            <dd><?= $order['main_paper'] ?? '' ?></dd>

            <dt>印刷色</dt>
            <dd><?= $order['main_color'] ?? '' ?></dd>
<!--
            <dt>スクリーンタイプ</dt>
            <dd><?= $order['main_print_type'] ?? '' ?></dd>
-->
        </dl>

    </td>
</tr>

<tr>
    <th>遊び紙</th>
    <td><?= $order['main_buffer_paper'] == 'なし' ? 'なし' :
    $order['main_buffer_paper'].'（'.$order['main_buffer_paper_detail'].'）'
    ?></td>
</tr>

<?php if(!empty($order['b_overprint_kaiteki'])): ?>

<tr>
    <th>余部特典</th>
    <td>余部を快適本屋さんに委託する</td>
</tr>

<?php endif; ?>

<tr>
    <th>その他備考</th>
    <td><?= $order['r18'] ?? '' ?>　<?= $order['print_note'] ?? '' ?></td>
</tr>

</table>



<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<?php

$print_number_all = (int)str_replace('冊','',$order['print_number_all'] ?? 0);

$b_kaiteki = !empty($order['number_kaiteki']);

$divide_without_kaiteki = $b_kaiteki
? $order['delivery_divide'] - 1
: $order['delivery_divide'];

$delivery_divide = $order['delivery_divide'];

?>

<h4>発送先<?= 2 <= $order['delivery_divide']
? '（'.$order['delivery_divide'].'箇所に分納）' : ''
?></h4>



<table>


<?php if ($order['number_kaiteki']): ?>
    <tr>
    <th>快適本屋さんOnline</th>
    <td><b><?= $order['number_kaiteki'] ?? 0 ?><?= !empty($order['b_overprint_kaiteki']) ? '＋'.intval($print_number_all * 0.1) : '' ?>部納品</b></td>
</tr>
<?php endif;?>


<?php if ($order['number_home']): ?>
<tr>
    <th>自宅</th>
    <td><b><?= $order['number_home'] ?? 0 ?>部納品</b></td>
</tr>
<?php endif;?>


<?php if(count($order['delivery'])):

    foreach($order['delivery'] as $delivery):

        if($delivery['type'] == 'event'):
            
            $DT_event = new \Datetime($delivery['date']);

            $date_text  = $DT_event->format('Y/n/j');
            $date_text .= $youbi[$DT_event->format('w')];
?>
<tr>
    <th>イベント</th>
    <td>
        <p>
            <?= $date_text ?>
            <?= $delivery['place'] ?? '' ?>
            <?= $delivery['hall_name'] ?? '' ?>
            <?= $delivery['name'] ?? '' ?>
        </p>
        
        <p>
            <?= $delivery['space_code'] ?? '' ?>
            <?= $delivery['circle_name'] ?? '' ?>
        </p>
        
        <p><b><?= $delivery['number'] ?? 0 ?>部搬入</b></p>
    </td>
</tr>

<?php else: // other ?>
    <tr>
    <th>その他</th>
    <td>
        <p>
            <?= $delivery['zipcode'] ?? '' ?>
            <?= $delivery['address1'] ?? '' ?>
            <?= $delivery['address2'] ?? '' ?>
        </p>
        
        <p>
            <?= $delivery['real_name'] ?? '' ?>
        </p>
        
        <p><b><?= $delivery['number'] ?? 0 ?>部納品</b></p>
    </td>
</tr>

<?php endif; // delivery type ?>

<?php endforeach; endif; ?>

</table>


<?php
if (!in_array($order['status_name'], ['印刷開始済','返金済'])):
?>
<p class="text-right">
    <a href="/contact/index?order_id=<?= $order['id'] ?>&contact_type=<?= urlencode('入稿発注について') ?>">入稿発注に関するお問合せ＞＞</a>
</p>
<?php endif; ?>


<?php /*
<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<p>debug:</p>

<pre>
<?= print_r($order, true) ?>
</pre>

*/ ?>

<p class="text-right">
    <a href="/user/mypage">マイページ＞＞</a>
</p>



            </article>
        </section>


    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>