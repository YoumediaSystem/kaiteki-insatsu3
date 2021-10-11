<?php

const PAGE_NAME = '分納数編集';

$lib = new \App\Models\CommonLibrary();
$a = $lib->getSelectItemsYMD();
foreach($a as $key=>$val) $$key = $val;

$youbi = $lib->getYoubiArray();

$print_number_all = (int)str_replace('冊','',$order['print_number_all'] ?? 0);

$checked	= ' checked="checked"';
$selected	= ' selected="selected"';
$disabled	= ' disabled="disabled"';
$hidden		= ' style="display:none"';

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

input [type="number"] {
    width:3em;
}

input.width_full, textarea {
    width:calc(100% - 1rem);
}

button[disabled] {
    cursor:default;
    opacity: 0.5;
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

            <h3 class="heading">受注no.<?= $order['id'] ?>　分納先と部数の編集</h3>
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

$b_kaiteki = !empty($order['number_kaiteki']);

$divide_without_kaiteki = $b_kaiteki
? $order['delivery_divide'] - 1
: $order['delivery_divide'];

$delivery_divide = $order['delivery_divide'];

?>

<p>総部数：<span id="print_number"><?= $print_number_all ?></span> / <?= $print_number_all ?>　分納数：<span id="count_without_kaiteki"><?= $divide_without_kaiteki ?></span> / <?= $divide_without_kaiteki ?>箇所（快適本屋さんを除く）
　<strong style="font-weight:normal">※総部数や分納数が変わる変更はできません</strong>
</p>



<form id="form1" method="post" action="/admin/divide_do">
<input type="hidden" name="id" value="<?= $order['id'] ?? '' ?>">
<input type="hidden" id="delivery_divide" name="delivery_divide" value="<?= $delivery_divide ?>">


<table style="width:100%">

<tr>
<th>快適本屋さんOnline</th>
    <td>
        <input type="number" name="number_kaiteki" value="<?= $order['number_kaiteki'] ?>" onchange="mod_number_all()">部　※余部除く
    </td>
</tr>

<tr>
<th>自宅</th>
    <td>
        <input class="not_kaiteki" type="number" name="number_home" value="<?= $order['number_home'] ?>" onchange="mod_number_all()">部
    </td>
</tr>

<?php if(count($order['delivery'])):

foreach($order['delivery'] as $delivery):

    $i = $delivery['id'];

    $b_event = ($delivery['type'] == 'event');

    $header_text = $b_event ? 'イベント' : 'その他';

    if ($b_event) {
        $DT = new \Datetime($delivery['date']);
        $header_text .= '（'.$DT->format('n/j').'）';
    }

    $info_text = $b_event
    ? $delivery['place'].'　'.$delivery['name']
    : $delivery['real_name'];
?>

<tr>
<th><?= $header_text ?></th>
    <td>
        <input class="not_kaiteki" type="number" name="number_delivery[<?= $i ?>]" value="<?= $delivery['number'] ?>" onchange="mod_number_all()">部　<small><?= $info_text ?></small>
    </td>
</tr>

<?php
    endforeach;
endif;
?>

<tr>
    <th>管理用備考</th>
    <td><textarea name="note"><?= $order['note'] ?></textarea></td>
</tr>

</table>



<div class="text-center buttons">

    <button type="button" id="go_prev" class="ec-blockBtn--cancel" name="mode" value="back">戻る</button>

    <button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="modify">更新</button>

</div><!-- text-center -->



<script>

var print_number_all = <?= $print_number_all ?? 0 ?>;

var divide_without_kaiteki = <?= $divide_without_kaiteki ?>;

$('#go_prev').on('click', function(){
    location.href = '/admin/order_detail?id=<?= $order['id'] ?>';
});

$('#go_next').on('click', function(){

    var count_divide = 0;
    var n;
    $('input[type=number]').each(function(){
        n = parseInt($(this).val());
        count_divide += (n > 0) ? 1 : 0;
    });

    $('#delivery_divide').val(count_divide);

    $('#form1').submit();
});

function mod_number_all() {

    var number_all = 0;
    var count_divide = 0;
    var b = true, n = 0;

    $('input[type=number]').each(function(){
        number_all += parseInt($(this).val());
    });

    $('#print_number').text(number_all);

    $('input.not_kaiteki').each(function() {
        n = parseInt($(this).val());
        count_divide += (n > 0) ? 1 : 0;
    });

    $('#count_without_kaiteki').text(count_divide);

    b = b && (number_all == print_number_all);
    b = b && (count_divide == divide_without_kaiteki);

    if (b) {
        $('#go_next').removeAttr('disabled');

    } else {
        $('#go_next').attr('disabled','disabled');
    }
}

mod_number_all();

</script>



            </article>
        </section>


    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>