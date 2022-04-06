<?php

const PAGE_NAME = '入稿詳細';

$print_number_all = (int)str_replace('冊','',$order['print_number_all'] ?? 0);

$Config = new \App\Models\Service\Config();

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

.post td.number, .link {
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

            <h3 class="heading">受注no.<?= $order['id'] ?>　詳細</h3>
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
<th>受注状況</th>
<td><strong><?= $order['status_name'] ?? '' ?></strong><?php

if (!empty($order['payment_id'])): ?>

　<a href="/admin/payment_detail?id=<?= $order['payment_id'] ?>">決済詳細＞＞</a>

<?php endif; ?></td>
</tr>



<?php if(in_array($order['status'],[40,50,60,140,150,160])): ?>

<tr>
<th>ご招待コード</th>
<td><?= $Config->getInviteCode($order['id'], $order['print_title']) ?></td>
</tr>

<?php endif; ?>



<tr>
<th>発注者</th>
<td>会員no.<?= $order['user_id'] ?? '' ?>
　<a href="/admin/user_detail?id=<?= $order['user_id'] ?? '' ?>" target="_blank">会員詳細＞＞</a></td>
</tr>

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
<th>受注料金</th>
<td><?php if(!empty($org_price_text) && $org_price_text != $price_text): ?>

    <s><?= $org_price_text ?></s> →

    <?php endif; ?><b><?= $price_text ?></b>
</td>
</tr>

<tr>
<th>入金期限</th>
<td><?= $date_text ?></td>
</tr>

<tr>
<th>作業依頼書</th>
<td><?php

if (in_array($order['status'],[40,41,50,51,60,140,141,150,151,160])): ?>

    <?php if($b_order_sheet): ?>
　<a href="/pdf/order_sheet/<?= $client_dir ?>/<?= $order_sheet_file ?>?d=<?= $DT_now->format('U') ?>" target="_blank">作業依頼書PDF</a>
    <?php endif; ?>


　<button id="export_button" type="button" onclick="export_pdf()">　<?= ($b_order_sheet) ? '再出力' : '作業依頼書出力' ?>　</button>
    <span id="waiting" style="display:none">お待ちください...</span>
    <span id="export_failed" style="display:none"><strong>依頼書出力エラー</strong></span>

<script>

var global = global || {};

global.b_lock = false;

function export_pdf() {

    if (global.b_lock) return;

    global.b_lock = true;

    $('#export_button').hide();
    $('#waiting').show();
    
    $.ajax({
        url : '/admin/export_sheet',
        data: {'id': <?= $order['id'] ?? 0 ?>},
        type: 'POST',
        success:function(res) {

            if (!res) {
                $('#waiting').hide();
                $('#export_failed').show();
                return;
            }

            setTimeout(reload, 10000);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {

            console.log('エラーが発生しました。'
                + XMLHttpRequest.status + ' / '
                + textStatus + ' / '
                + errorThrown // errorThrown.message
            );
        }
    });
}

function reload() {
    location.href = '/admin/order_detail?id=<?= $order['id'] ?>';
}

</script>

<?php endif; ?>

</td>
</tr>


<tr>
<th>管理備考</th>
<td><?= $order['note'] ?? '' ?></td>
</tr>


</table>


<?php if($environ != 'real'): ?>

    <p class="text-right" style="display:none;">
        <a href="/admin/order_sheet?id=<?= $order['id'] ?>" target="_blank">作業依頼書PDFを直接表示する（重い）＞＞</a>
    </p>

<?php endif; ?>


<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<h4>受注仕様</h4>

<p class="link">
<a href="/admin/order_edit?id=<?= $order['id'] ?>">タイトル・備考・受注内容編集＞＞</a>
</p>



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



<?php if(!empty($order['b_extra_order'])): ?>

<tr>
    <th>事前相談</th>
    <td><?= $order['extra_order_note'] ?? '' ?></td>
</tr>

<tr>
    <th>調整コメント（顧客向け）</th>
    <td><?= $order['adjust_note_front'] ?? '' ?></td>
</tr>

<tr>
    <th>調整コメント（発注書用）</th>
    <td><?= $order['adjust_note_admin'] ?? '' ?></td>
</tr>

<?php endif; ?>
<?php if(!empty($order['b_extra_order']) || !empty($order['adjust_price'])): ?>

<tr>
    <th>調整金額</th>
    <td><?= $order['adjust_price'] ?? 0 ?></td>
</tr>

<?php endif; ?>



<tr>
    <th>その他備考</th>
    <td><?= $order['r18'] ?? '' ?>　<?= $order['print_note'] ?? '' ?></td>
</tr>

</table>



<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<?php

$b_kaiteki = !empty($order['number_kaiteki']);

$divide_without_kaiteki = $b_kaiteki
? $order['delivery_divide'] - 1
: $order['delivery_divide'];

$delivery_divide = $order['delivery_divide'];

?>

<h4>発送先：<?= $divide_without_kaiteki ?>箇所（快適本屋さん除く）<?=
empty($order['number_kaiteki']) && empty($order['b_overprint_kaiteki'])
? '　※もっと特典適用あり' : '' ?></h4>
<!--
<p class="link">
<a href="/admin/divide_edit?id=<?= $order['id'] ?>">分納部数編集＞＞</a>
</p>
-->

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
            <small>（<?= $delivery['circle_name_kana'] ?? '' ?>）</small>
        </p>
        
        <p><b><?= $delivery['number'] ?? 0 ?>部搬入</b></p>

        <p class="link">
            <a href="/admin/delivery_edit?id=<?= $delivery['id'] ?>">納品先編集＞＞</a>
        </p>
    </td>
</tr>

<?php else: // other ?>
<!--
<?= print_r($delivery, true) ?>
-->
    <tr>
    <th>その他</th>
    <td>
        <p>
            <?= $delivery['zipcode'] ?? '' ?>
            <?= $delivery['real_address_1'] ?? '' ?>
            <?= $delivery['real_address_2'] ?? '' ?>
        </p>
        
        <p>
            <?= $delivery['real_name'] ?? '' ?>
            <small>（<?= $delivery['real_name_kana'] ?? '' ?>）</small>
        </p>
        
        <p><b><?= $delivery['number'] ?? 0 ?>部納品</b></p>

        <p class="link">
            <a href="/admin/delivery_edit?id=<?= $delivery['id'] ?>">納品先編集＞＞</a>
        </p>
    </td>
</tr>

<?php endif; // delivery type ?>

<?php endforeach; endif; ?>

</table>


<?php if(in_array($order['status'],[40,41,50,51,60,61,70])):
    
    $b_ok = ($order['status'] == 70); ?>

<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<h4><?= $b_ok ? '不備戻し入力' : '精査結果入力' ?>

<div class="text-center">
    <form method="post" action="/admin/order_detail_judge">
    <input type="hidden" name="id" value="<?= $order['id'] ?>">

    <p>
        <select name="ng_reason">

            <?php
            $order_ok_text = '仮受付する';

            if (in_array($order['status'], [50,51]))
                $order_ok_text = '表紙を印刷開始する';

            if (in_array($order['status'], [60,61]))
                $order_ok_text = '本文を印刷開始する・精査完了';

            if($order['status'] != 70): ?>
            <option value="">精査OK（<?= $order_ok_text ?>）</option>
            <?php endif; ?>

            <?php if(in_array($order['status'], [40,41,50,51,60,61,70])): ?>
            <option value="理由1：ダウンロード不可">一次不備（ダウンロード不可）</option>
            <option value="理由1：ファイル不備">一次不備（ファイル不備）</option>
            <option value="理由1：納品先">一次不備（納品先）</option>
            <option value="理由1：その他">一次不備（その他）</option>
            <?php endif; ?>

            <?php if(in_array($order['status'], [50,51,60,61,70])): ?>
            <option value="理由2：表紙内容">二次不備（表紙内容）</option>
            <option value="理由2：その他">二次不備（その他）</option>
            <?php endif; ?>

            <?php if(in_array($order['status'], [60,61,70])): ?>
            <option value="理由3：本文内容">三次不備（本文内容）</option>
            <option value="理由3：台割ノンブル相違">三次不備（台割ノンブル相違）</option>
            <option value="理由3：その他">三次不備（その他）</option>
            <?php endif; ?>

        </select>
    </p>

    <p>
        <textarea name="ng_reason_other" placeholder="不備（その他）の場合は、詳細を入力"></textarea>
    </p>

    <p>
        <button>精査結果を登録する</button>
    </p>

    </form>
</div>
<?php endif; ?>



<?php /*
<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<p>debug:</p>

<pre>
<?= print_r($order, true) ?>
</pre>

*/ ?>

            </article>
        </section>


    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>