<?php

const PAGE_NAME = '発送先編集';

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

input.width_full {
    width:calc(100% - 1rem);
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

            <h3 class="heading">発送先no.<?= $delivery['id'] ?>　詳細</h3>
            <article class="post">



<?php if(!empty($result_message)): ?>
    <p><em><?= $result_message ?></em></p>
<?php endif; ?>

<?php if(!empty($error) && count($error) > 0): ?>
    <ul id="error_list" class="attention">
    <?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
    </ul>
<?php endif; ?>



<form id="form1" method="post" action="/admin/delivery_do">
<input type="hidden" name="id" value="<?= $delivery['id'] ?? '' ?>">
<input type="hidden" name="order_id" value="<?= $delivery['order_id'] ?? '' ?>">

<?php

$DT_now   = new \Datetime();
$DT_limit = new \Datetime($order['payment_limit']);

$date_text  = $DT_limit->format('Y/n/j');
$date_text .= $youbi[$DT_limit->format('w')];
$date_text .= $DT_limit->format(' H時まで');

?>


<table style="width:100%">

<tr>
    <th>発送先種別</th>
    <td>

        <select name="type" onchange="mod_form()">

            <?php $prop = ($delivery['type'] == 'event') ? $selected : ''; ?>
            <option value="event"<?= $prop ?>>イベント</option>

            <?php $prop = ($delivery['type'] == 'other') ? $selected : ''; ?>
            <option value="other"<?= $prop ?>>その他</option>

        </select>

    </td>
</tr>

<?php

    $DT_event = new \Datetime($delivery['date']);

    $date_text  = $DT_event->format('Y/n/j');
    $date_text .= $youbi[$DT_event->format('w')];

    $key = 'date'; $k = '';
?>
<tr class="type_event">
    <th>イベント・開催日</th>
    <td>

    <?php $kkey = $key.'_y'; $fullkey2 = $k.$kkey; ?>
        <select id="<?= $fullkey2 ?>" name="<?= $fullkey2 ?>" class="digit-6">
            <option value="">-</option>
            <?php foreach($select_y as $val):
                $prop = ($val == $delivery[$fullkey2]) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_m'; $fullkey2 = $k.$kkey; ?>
        <select id="<?= $fullkey2 ?>" name="<?= $fullkey2 ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_m as $val):
                $prop = ($val == $delivery[$fullkey2]) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_d'; $fullkey2 = $k.$kkey; ?>
        <select id="<?= $fullkey2 ?>" name="<?= $fullkey2 ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_d as $val):
                $prop = ($val == $delivery[$fullkey2]) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>
        
    </td>
</tr>

<tr class="type_event">
    <th>イベント・名称</th>
    <td>
        <input type="text" name="name" value="<?= $delivery['name'] ?? '' ?>" class="width_full">
    </td>
</tr>

<tr class="type_event">
    <th>イベント・会場名</th>
    <td>
        <input type="text" name="place" value="<?= $delivery['place'] ?? '' ?>" class="width_full">
    </td>
</tr>

<tr class="type_event">
    <th>イベント・ホール名</th>
    <td>
        <input type="text" name="hall_name" value="<?= $delivery['hall_name'] ?? '' ?>" class="width_full">
    </td>
</tr>

<tr class="type_event">
    <th>イベント・スペースno.</th>
    <td>
        <input type="text" name="space_code" value="<?= $delivery['space_code'] ?? '' ?>" class="width_full">
    </td>
</tr>

<tr class="type_event">
    <th>イベント・サークル名</th>
    <td>
        <input type="text" name="circle_name" value="<?= $delivery['circle_name'] ?? '' ?>" class="width_full">
    </td>
</tr>

<tr class="type_event">
    <th>イベント・サークル名カナ</th>
    <td>
        <input type="text" name="circle_name_kana" value="<?= $delivery['circle_name_kana'] ?? '' ?>" class="width_full">
    </td>
</tr>

<tr class="type_other">
    <th>その他・郵便番号/住所</th>
    <td>
        <div class="ec-input">

            <div class="h-adr">
            <span class="p-country-name" style="display:none;">Japan</span>
            
            <input name="zipcode" type="text" class="p-postal-code digit-8" size="8" maxlength="8" value="<?=
            $delivery['zipcode'] ?? '' ?>" placeholder="123-4567" style="display:inline-block"> 郵便番号をご入力ください<br>

            <input name="real_address_1" type="text" class="p-region p-locality p-street-address p-extended-address width_full" value="<?=
            $delivery['real_address_1'] ?? '' ?>" placeholder="都道府県・市区町村（自動入力）">

            <input name="real_address_2" type="text" class="width_full" value="<?=
            $delivery['real_address_2'] ?? '' ?>" placeholder="番地・建物名・部屋番号">
            
            </div>
        </div>
    </td>
</tr>

<tr class="type_other">
    <th>その他・受取人氏名</th>
    <td>
        <input type="text" name="real_name" value="<?= $delivery['real_name'] ?? '' ?>" class="width_full">
    </td>
</tr>

<tr class="type_other">
    <th>その他・受取人氏名カナ</th>
    <td>
        <input type="text" name="real_name_kana" value="<?= $delivery['real_name_kana'] ?? '' ?>" class="width_full">
    </td>
</tr>

<tr class="type_other">
    <th>その他・受取人電話番号</th>
    <td>
        <input type="text" name="tel" value="<?= $delivery['tel'] ?? '' ?>" class="digit-12">
    </td>
</tr>

</table>



<div class="text-center buttons">

    <button type="button" id="go_prev" class="ec-blockBtn--cancel" name="mode" value="back">戻る</button>

    <button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="modify">更新</button>

</div><!-- text-center -->



<script>

$('#go_prev').on('click', function(){
    location.href = '/admin/order_detail?id=<?= $order['id'] ?>';
});

$('#go_next').on('click', function(){
    $('#form1').submit();
});

function mod_form() {

    var v = $('select[name="type"]').val();

    if (v == 'event') {
        $('.type_event').show();
        $('.type_other').hide();

    } else {
        $('.type_other').show();
        $('.type_event').hide();
    }
}

mod_form();

</script>



            </article>
        </section>


    </div><!-- wrapper -->

    <?= $view['footer'] ?>

<script src="//yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>

</body>
</html>