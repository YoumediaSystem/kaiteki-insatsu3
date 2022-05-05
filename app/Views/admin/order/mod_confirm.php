<?php

const PAGE_NAME = '内容確認 | 受注内容変更';

$checked	= ' checked="checked"';
$selected	= ' selected="selected"';
$disabled	= ' disabled="disabled"';
$hidden		= ' style="display:none"';

if(empty($b_overprint_kaiteki)) $b_overprint_kaiteki = 0;

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

        <h3 class="heading">受注no.<?= $id ?? '' ?>　<?= PAGE_NAME ?></h3>

<style>

button[disabled] {
    background-color:#999;
}

table {
    width:calc(100% - 2em);
}

</style>



<h4>タイトルなど（料金変動なし）</h4>

<form id="form1" method="post" action="/admin/order_form">
<input type="hidden" name="mode" value="modify">
<input type="hidden" name="id" value="<?= $id ?>">
<input type="hidden" name="mod_id" value="<?= $mod_id ?? '' ?>">



<table>

<?php if($admin['role'] == 'master'): ?>

<tr>
    <th>入稿状況</th>
    <td>
        <input type="hidden" name="status" value="<?= $status ?? '' ?>">

        <?php if($status != $org['status']): ?>
            <?= $statusName[$org['status']] ?? '' ?>
            → <strong><?= $statusName[$status] ?? '' ?></strong>

        <?php else: ?>
            <?= $statusName[$org['status']] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<?php endif; // master ?>

<tr>
    <th>タイトル</th>
    <td>
        <input type="hidden" name="print_title" value="<?= $print_title ?? '' ?>">

        <?php if($print_title != $org['print_title']): ?>
            <?= $org['print_title'] ?? '' ?>
            → <strong><?= $print_title ?? '' ?></strong>

        <?php else: ?>
            <?= $org['print_title'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
    <th>対象年齢・R18</th>
    <td>
        <input type="hidden" name="r18" value="<?= $r18 ?? '' ?>">

        <?php if($r18 != $org['r18']): ?>
            <?= $org['r18'] ?? '' ?>
            → <strong><?= $r18 ?? '' ?></strong>

        <?php else: ?>
            <?= $org['r18'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
    <th>ダウンロードURL</th>
    <td>
        <input type="hidden" name="print_data_url" value="<?= $print_data_url ?? '' ?>">

        <?php if($print_data_url != $org['print_data_url']): ?>
            <?= $org['print_data_url'] ?? '' ?>
            → <strong><?= $print_data_url ?? '' ?></strong>

        <?php else: ?>
            <?= $org['print_data_url'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
    <th>ダウンロードパスワード</th>
    <td>
        <input type="hidden" name="print_data_password" value="<?= $print_data_password ?? '' ?>">

        <?php
        $b1 = !empty($print_data_password);
        $b2 = !empty($org['print_data_password']);
        $b3 = ($b1 && $b2 && $print_data_password != $org['print_data_password']);

        if($b1 != $b2 || $b3): ?>
            <?= $org['print_data_password'] ?? '' ?>
            → <strong><?= $print_data_password ?? '' ?></strong>

        <?php else: ?>
            <?= $org['print_data_password'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
    <th>入金期限</th>
    <td>
<?php
$date_text  = $payment_limit_y.'-';
$date_text .= $payment_limit_m.'-';
$date_text .= $payment_limit_d.' ';
$date_text .= $payment_limit_h.':00:00';
$DT = new \DateTime($date_text);
$DT->setTimezone(new DateTimeZone('Asia/Tokyo'));

$DT_org = new \DateTime($org['payment_limit']);
$DT_org->setTimezone(new DateTimeZone('Asia/Tokyo'));
?>
<input type="hidden" id="payment_limit_y" name="payment_limit_y" value="<?= $payment_limit_y ?>">
<input type="hidden" id="payment_limit_m" name="payment_limit_m" value="<?= $payment_limit_m ?>">
<input type="hidden" id="payment_limit_d" name="payment_limit_d" value="<?= $payment_limit_d ?>">
<input type="hidden" id="payment_limit_h" name="payment_limit_h" value="<?= $payment_limit_h ?>">
<input type="hidden" id="payment_limit" name="payment_limit" value="<?= $date_text ?>">

<?php if($DT != $DT_org): ?>

    <?= $DT_org->format('Y/n/j H') ?>時まで
    → <strong><?= $DT->format('Y/n/j H') ?>時まで</strong>

<?php else: ?>
    <?= $DT->format('Y/n/j H') ?>時まで

<?php endif; ?>

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



<h4>入稿仕様（一部料金変動あり）</h4>

<table>

<tr>
    <th>料金</th>
    <td>
        <input type="hidden" name="price" value="<?= $price['total'] ?? '' ?>">
        <input type="hidden" name="basic_price" value="<?= $price['basic_price'] ?? 0 ?>">

        <?php if($price['total'] != $org['price']): ?>
            <?= $org['price'] ?? '' ?>
            → <strong><?= $price['total'] ?? '' ?></strong>

        <?php else: ?>
            <?= $org['price'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>料金明細</th>
    <td>
        <input type="hidden" name="price_text" value="<?= $price['price_text'] ?? '' ?>">

        <?php if(
            preg_replace("/\s/","",$price['price_text'])        
        !=  preg_replace("/\s/","",$org['price_text'])
        ): ?>
            <pre><?= $org['price_text'] ?? '' ?></pre>
            ↓<br>
            <pre><strong><?= $price['price_text'] ?? '' ?></strong></pre>

        <?php else: ?>
            <pre><?= $org['price_text'] ?? '' ?></pre>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>総部数（冊数）</th>
    <td>
        <input type="hidden" name="print_number_all" value="<?= $print_number_all ?? '' ?>">

        <?php if($print_number_all != $org['print_number_all']): ?>
            <?= $org['print_number_all'] ?? '' ?>
            → <strong><?= $print_number_all ?? '' ?></strong>

        <?php else: ?>
            <?= $org['print_number_all'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>ページ数</th>
    <td>
        <input type="hidden" name="print_page" value="<?= $print_page ?? '' ?>">

        <?php if($print_page != $org['print_page']): ?>
            <?= $org['print_page'] ?? '' ?>
            → <strong><?= $print_page ?? '' ?></strong>

        <?php else: ?>
            <?= $org['print_page'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>本文始まりページ数</th>
    <td>
        <input type="hidden" name="nonble_from" value="<?= $nonble_from ?? '' ?>">

        <?php if($nonble_from != $org['nonble_from']): ?>
            <?= $org['nonble_from'] ?? '' ?>
            → <strong><?= $nonble_from ?? '' ?></strong>

        <?php else: ?>
            <?= $org['nonble_from'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>仕上がりサイズ</th>
    <td>
        <input type="hidden" name="print_size" value="<?= $print_size ?? '' ?>">

        <?php if($print_size != $org['print_size']): ?>
            <?= $org['print_size'] ?? '' ?>
            → <strong><?= $print_size ?? '' ?></strong>

        <?php else: ?>
            <?= $org['print_size'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>製本（とじ方向）</th>
    <td>
        <input type="hidden" name="print_direction" value="<?= $print_direction ?? '' ?>">

        <?php if($print_direction != $org['print_direction']): ?>
            <?= $org['print_direction'] ?? '' ?>
            → <strong><?= $print_direction ?? '' ?></strong>

        <?php else: ?>
            <?= $org['print_direction'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>表紙・用紙</th>
    <td>
        <input type="hidden" name="cover_paper" value="<?= $cover_paper ?? '' ?>">

        <?php if($cover_paper != $org['cover_paper']): ?>
            <?= $org['cover_paper'] ?? '' ?>
            → <strong><?= $cover_paper ?? '' ?></strong>

        <?php else: ?>
            <?= $org['cover_paper'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>表紙・印刷色</th>
    <td>
        <input type="hidden" name="cover_color" value="<?= $cover_color ?? '' ?>">

        <?php if($cover_color != $org['cover_color']): ?>
            <?= $org['cover_color'] ?? '' ?>
            → <strong><?= $cover_color ?? '' ?></strong>

        <?php else: ?>
            <?= $org['cover_color'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>表紙・基本加工</th>
    <td>
        <input type="hidden" name="cover_process" value="<?= $cover_process ?? '' ?>">

        <?php if($cover_process != $org['cover_process']): ?>
            <?= $org['cover_process'] ?? '' ?>
            → <strong><?= $cover_process ?? '' ?></strong>

        <?php else: ?>
            <?= $org['cover_process'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>本文・用紙</th>
    <td>
        <input type="hidden" name="main_paper" value="<?= $main_paper ?? '' ?>">

        <?php if($main_paper != $org['main_paper']): ?>
            <?= $org['main_paper'] ?? '' ?>
            → <strong><?= $main_paper ?? '' ?></strong>

        <?php else: ?>
            <?= $org['main_paper'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>本文・印刷色</th>
    <td>
        <input type="hidden" name="main_color" value="<?= $main_color ?? '' ?>">

        <?php if($main_color != $org['main_color']): ?>
            <?= $org['main_color'] ?? '' ?>
            → <strong><?= $main_color ?? '' ?></strong>

        <?php else: ?>
            <?= $org['main_color'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>遊び紙</th>
    <td>
        <input type="hidden" name="main_buffer_paper" value="<?= $main_buffer_paper ?? '' ?>">

        <?php if($main_buffer_paper != $org['main_buffer_paper']): ?>
            <?= $org['main_buffer_paper'] ?? '' ?>
            → <strong><?= $main_buffer_paper ?? '' ?></strong>

        <?php else: ?>
            <?= $org['main_buffer_paper'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>余部特典</th>
    <td>
        <input type="hidden" name="b_overprint_kaiteki" value="<?= $b_overprint_kaiteki ?? '' ?>">

        <?php
        $text_org  = !empty($org['b_overprint_kaiteki'])
        ? '余部特典あり' : '余部特典なし';

        $text_dest = !empty($b_overprint_kaiteki)
        ? '余部特典あり' : '余部特典なし';
        
        if($b_overprint_kaiteki != $org['b_overprint_kaiteki']): ?>
            <?= $text_org ?>
            → <strong><?= $text_dest ?></strong>

        <?php else: ?>
            <?= $text_org ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>納品先※快適本屋さん含む</th>
    <td>
        <input type="hidden" name="delivery_divide" value="<?= $delivery_divide ?? '' ?>">

        <?php if($delivery_divide != $org['delivery_divide']): ?>
            <?= $org['delivery_divide'] ?? '' ?>
            → <strong><?= $delivery_divide ?? '' ?></strong>

        <?php else: ?>
            <?= $org['delivery_divide'] ?? '' ?>

        <?php endif; ?>
    </td>
</tr>


<tr>
<th>事前相談</th>
    <td>
        <input type="hidden" name="b_extra_order" value="<?= $b_extra_order ?? '' ?>">

        <?php
        $text_org  = !empty($org['b_extra_order'])
        ? '事前相談済み' : 'なし';

        $text_dest = !empty($b_extra_order)
        ? '事前相談済み' : 'なし';
        
        if($text_org != $text_dest): ?>
            <?= $text_org ?>
            → <strong><?= $text_dest ?></strong>

        <?php else: ?>
            <?= $text_org ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>調整コメント（顧客向け）</th>
    <td>
        <input type="hidden" name="adjust_note_front" value="<?= $adjust_note_front ?? '' ?>">

        <?php
        $text_org  = ($org['adjust_note_front'] ?? '');
        $text_dest = ($adjust_note_front ?? '');

        if($text_org != $text_dest): ?>
            <?= $text_org ?>
            → <strong><?= $text_dest ?></strong>

        <?php else: ?>
            <?= $text_org ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>調整コメント（発注書用）</th>
    <td>
        <input type="hidden" name="adjust_note_admin" value="<?= $adjust_note_admin ?? '' ?>">

        <?php
        $text_org  = ($org['adjust_note_admin'] ?? '');
        $text_dest = ($adjust_note_admin ?? '');

        if($text_org != $text_dest): ?>
            <?= $text_org ?>
            → <strong><?= $text_dest ?></strong>

        <?php else: ?>
            <?= $text_org ?>

        <?php endif; ?>
    </td>
</tr>

<tr>
<th>調整金額</th>
    <td>
        <input type="hidden" name="adjust_price" value="<?= $adjust_price ?? 0 ?>">

        <?php
        $text_org  = ($org['adjust_price'] ?? 0);
        $text_dest = ($adjust_price ?? 0);

        if($text_org != $text_dest): ?>
            <?= $text_org ?>
            → <strong><?= $text_dest ?></strong>

        <?php else: ?>
            <?= $text_org ?>

        <?php endif; ?>
    </td>
</tr>










<tr>
<th>納品・快適本屋さんOnline（余部除く）</th>
    <td>
        <input type="hidden" name="number_kaiteki" value="<?= $number_kaiteki ?>">
        <?= $number_kaiteki ?>部
    </td>
</tr>

<tr>
<th>納品・自宅</th>
    <td>
    <input type="hidden" name="number_home" value="<?= $number_home ?>">
    <?= $number_home ?>部
    </td>
</tr>


<?php
if(!empty($number_delivery) && count($number_delivery)):

    foreach($number_delivery as $i=>$val):
?>
<tr>
<th><?= $delivery_header[$i] ?></th>
    <td>
        <input type="hidden" name="number_delivery[<?= $i ?>]" value="<?= $val ?>"><?= $val ?>部　<small><?= $delivery_info[$i] ?></small>
    </td>
</tr>

<?php
    endforeach;
endif;
?>


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

    $('#form1').attr('action','/admin/order_edit');
    $('#form1').submit();
});

$('#go_next').on('click', function(){

    $('#form1').attr('action','/admin/order_do');
    $('#form1').submit();
});

</script>

</body>
</html>