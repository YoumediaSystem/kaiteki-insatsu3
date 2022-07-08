<?php

const PAGE_NAME = '受注内容変更フォーム';

$b_taiyou = ($product_set['client_code'] == 'taiyou');

$Price = (new \App\Models\Service\PriceInterface())
    ->getObject($product_set['client_code'],$product_set['name_en']);

$price_format = $Price->getPriceFormat([
    'client_code' => $product_set['client_code'],
    'product_code' => $product_set['name_en']
]);

$select = $Price->getFormSelector(
    $price_format['price_base_matrix'],
    $product_set['name_en']
);


$checked	= ' checked="checked"';
$selected	= ' selected="selected"';
$disabled	= ' disabled="disabled"';
$hidden		= ' style="display:none"';

$DT = new DateTime();
$now_y = (int)$DT->format('Y');

$select_y = range($now_y - 100, $now_y + 1);
$select_m = range(1,12);
$select_d = range(1,31);

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

textarea, input.width_full {
    width:calc(100% - 1.2rem);
}

button[disabled] {
    cursor:default;
    opacity: 0.5;
}

</style>

<style>

<?php for($i=1; $i<20; $i++): ?>
<?= '.digit-'.$i ?> {width:<?= $i+1 ?>em !important;}
<?php endfor; ?>

</style>


    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>
</head>

<body>

    <?= $view['header'] ?>

    <div id="wrapper">

        <section class="content">

            <h3 class="heading">受注no.<?= $id ?? '' ?>　<?= PAGE_NAME ?></h3>

<p class="link">
    <a href="/admin/order_detail?id=<?= $id ?>">受注詳細に戻る＞＞</a>
</p>


            <div id="form_area" class="text">



<?php if(!empty($error) && count($error) > 0): ?>
<ul id="error_list" class="attention">
<?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
</ul>
<?php endif; ?>







<h4>タイトルなど（料金変動なし）</h4>

<form id="form1" method="post" action="/admin/order_confirm">
<input type="hidden" name="mode" value="modify">
<input type="hidden" name="id" value="<?= $id ?>">
<input type="hidden" name="mod_id" value="<?= $mod_id ?? '' ?>">
<input type="hidden" name="name" value="<?= $product_set['name'] ?? '' ?>">
<input type="hidden" name="name_en" value="<?= $product_set['name_en'] ?? '' ?>">
<input type="hidden" name="client_code" value="<?= $product_set['client_code'] ?? '' ?>">
<input type="hidden" name="product_code" value="<?= $product_set['name_en'] ?? '' ?>">

<input type="hidden" name="number_home" value="<?= $number_home ?? 0 ?>">
<input type="hidden" name="number_kaiteki" value="<?= $number_kaiteki ?? 0 ?>">
<?php
if(!empty($delivery) && count($delivery)):
    $n = 1;
    foreach($delivery as $data):

        $key = 'number_'.$data['type'];

        if($data['type'] == 'event') $key .= '_'.$n;
?>
<input type="hidden" name="<?= $key ?>" value="<?= $data['number'] ?? 0 ?>">
<?php
    endforeach;
endif;
?>

<table>

<?php if($admin['role'] == 'master'): ?>

<tr>
    <th>入稿状況</th>
    <td>

        <?php $key = 'status'; ?>
        <select name="<?= $key ?>">
            <?php foreach($statusName as $key=>$val):
                $prop = ($key == $status) ? $selected : ''; ?>
                <option value="<?= $key ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <br>
        <small class="attention">入稿内容調整待ちは自動で未入金に遷移します（変更不要）</small>

    </td>
</tr>

<?php endif; // master ?>

<tr>
    <th>タイトル</th>
    <td><input type="text" name="print_title" value="<?= $print_title ?? '' ?>"></td>
</tr>

<tr>
    <th>対象年齢・R18</th>
    <td>

        <select id="r18" name="r18">
            <?php foreach($select['r18'] as $val):
                $prop = ($val == $r18) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr>

<tr>
    <th>ダウンロードURL・パスワード</th>
    <td><input type="text" name="print_data_url" value="<?= $print_data_url ?? '' ?>"><br>
        <input type="text" name="print_data_password" value="<?= $print_data_password ?? '' ?>">
</td>
</tr>


<?php

$DT = new \DateTime($payment_limit);
$DT->setTimezone(new DateTimeZone('Asia/Tokyo'));

$key = 'payment_limit';

$payment_limit_y = $payment_limit_y ?? (int)$DT->format('Y');
$payment_limit_m = $payment_limit_m ?? (int)$DT->format('n');
$payment_limit_d = $payment_limit_d ?? (int)$DT->format('j');
$payment_limit_h = $payment_limit_h ?? (int)$DT->format('H');

?>

<tr>
    <th>入金期限</th>
    <td>

<?php if(in_array($status, [10,12])): ?>

    <?php $kkey = $key.'_y'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-6">
            <option value="">-</option>
            <?php foreach($select_y as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_m'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_m as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_d'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_d as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

<?= $DT->format('H') ?>時まで<br>

<small>納品先が遠隔地かつ事前相談済の場合は変更してください</small>

<?php else: ?>
<input type="hidden" id="payment_limit_y" name="payment_limit_y" value="<?= $payment_limit_y ?>">
<input type="hidden" id="payment_limit_m" name="payment_limit_m" value="<?= $payment_limit_m ?>">
<input type="hidden" id="payment_limit_d" name="payment_limit_d" value="<?= $payment_limit_d ?>">
<?= $DT->format('Y/n/j H') ?>時まで

<?php endif; ?>

<input type="hidden" id="payment_limit_h" name="payment_limit_h" value="<?= $payment_limit_h ?>">

</td>
</tr>

<tr>
    <th>管理備考</th>
    <td><textarea name="note"><?= $note ?? '' ?></textarea></td>
</tr>

</table>



<h4>入稿仕様（一部料金変動あり、現在の料金　￥<?= $price ?>）</h4>



<table>

<tr>
    <th>総部数（冊数）</th>
    <td>

        <select id="print_number_all" name="print_number_all" class="digit-6 price_factor">
            <?php
                $prop = ($val == $print_number_all) ? $selected : '';
            ?>
                <option value="0冊"<?= $prop ?>>未選択</option>

            <?php foreach($select['print_number_all'] as $val):
                $prop = ($val == $print_number_all) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr>

<tr>
    <th>ページ数</th>
    <td>
        
    <select id="print_page" name="print_page" class="digit-6 price_factor">
            <?php
                $prop = ($val == $print_page) ? $selected : '';
            ?>
                <option value="0p"<?= $prop ?>>未選択</option>

            <?php foreach($select['print_page'] as $val):
                $prop = ($val == $print_page) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <span class="attention" style="display:inline-block">表紙4ページを含む</span>

    </td>
</tr>

<tr>
    <th>本文始まりページ数</th>
    <td>

        <select id="nonble_from" name="nonble_from">
            <?php foreach($select['nonble_from'] as $val):
                $prop = ($val == $nonble_from) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr>

<tr>
    <th>仕上がりサイズ</th>
    <td>

        <select id="print_size" name="print_size" class="price_factor">
            <?php foreach($select['print_size'] as $val):
                $prop = ($val == $print_size) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr>

<tr>
    <th>製本（とじ方向）</th>
    <td>

        <select id="print_direction" name="print_direction">
            <?php foreach($select['print_direction'] as $val):
                $prop = ($val == $print_direction) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr>

<tr>
    <th>表紙・用紙</th>
    <td>

        <select id="cover_paper" name="cover_paper" class="price_factor">
            <?php foreach($select['cover_paper'] as $val):
                $prop = ($val == $cover_paper) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr>

<tr>
    <th>表紙・印刷色</th>
    <td>

        <select id="cover_color" name="cover_color" class="price_factor">
            <?php foreach($select['cover_color'] as $val):
                $prop = ($val == $cover_color) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr>

<tr>
    <th>表紙・基本加工</th>
    <td>

        <select id="cover_process" name="cover_process" class="price_factor">
            <?php foreach($select['cover_process'] as $val):
                $prop = ($val == $cover_process) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr>

<tr>
    <th>本文・用紙</th>
    <td>

        <select id="main_paper" name="main_paper" class="price_factor">
            <?php foreach($select['main_paper'] as $val):
                $prop = ($val == $main_paper) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr>

<tr>
    <th>本文・印刷色</th>
    <td>

        <select id="main_color" name="main_color" class="price_factor">
            <?php foreach($select['main_color'] as $val):
                $prop = ($val == $main_color) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr>

<tr>
    <th>遊び紙</th>
    <td>

        <select id="main_buffer_paper" name="main_buffer_paper" class="price_factor">
            <?php foreach($select['main_buffer_paper'] as $val):
                $prop = ($val == $main_buffer_paper) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr>

<?php if(is_array($select['main_buffer_paper'])
&&  1 < count($select['main_buffer_paper'])): ?>
<tr>
    <th>遊び紙の種類</th>
    <td>

        <select id="main_buffer_paper_detail" name="main_buffer_paper_detail" class="price_factor">

            <?php $prop = empty($main_buffer_paper_detail) ? $selected : ''; ?>
                <option value=""<?= $prop ?>>（未選択）</option>

            <?php foreach($select['main_buffer_paper_detail'] as $val):
                $prop = ($val == $main_buffer_paper_detail) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr>
<?php else: ?>
    <input type="hidden" id="main_buffer_paper_detail" name="main_buffer_paper_detail" value="">

<?php endif; ?>

<?php if($b_taiyou && strpos($product_set['name_en'],'offset') !== false): ?>

<tr>
    <th>余部特典</th>
    <td>
        <label>
            <?php
                $prop = !empty($b_overprint_kaiteki) ? $checked : '';
            ?>
            <input type="checkbox" name="b_overprint_kaiteki" value="1"<?= $prop ?>>
            余部を快適本屋さんに納品する（オフセット限定）
        </label>
    </td>
</tr>

<?php endif; // b_overprint_kaiteki ?>

<tr>
    <th>納品先数　※快適本屋さん含む</th>
    <td>
        <select id="delivery_divide" name="delivery_divide"<?= $disabled ?>>
            <?php foreach(range(1,5) as $n):
                $prop = ($n == ($delivery_divide ?? 0)) ? $selected : '';
                ?>
                <option value="<?= $n ?>"<?= $prop ?>><?= $n ?></option>
            <?php endforeach; ?>
        </select>

<?php if($b_taiyou): ?>
    <small class="attention">快適本屋さんに余部のみ納品する場合はカウント数に含めない</small>
<?php endif; ?>

</td>
</tr>

<tr>
    <th>事前相談</th>
    <td>
        <label>
            <?php $prop = !empty($b_extra_order) ? $checked : ''; ?>
            <input type="checkbox" name="b_extra_order" value="1"<?= $prop ?>>
            事前相談済み
        </label>
    </td>
</tr>

<tr>
    <th>調整コメント（顧客向け）</th>
    <td><input type="text" name="adjust_note_front" value="<?= $adjust_note_front ?? '' ?>" class="width_full">
    <small>入力例「ページ数200pで入稿希望の為」「冊数3000冊で入稿希望の為」</small>
</td>
</tr>

<tr>
    <th>調整コメント（発注書用）</th>
    <td><input type="text" name="adjust_note_admin" value="<?= $adjust_note_admin ?? '' ?>" class="width_full">
    <small>入力例「【ページ数】200p【冊数】3000冊」</small></td>
</tr>

<tr>
    <th>調整金額</th>
    <td><input type="text" name="adjust_price" value="<?= $adjust_price ?? 0 ?>"><br>
        <small>※基本料金からの増減額を入力してください</small></td>
</tr>

</table>

<p><small>遠隔地納品に伴う入金期限の前倒し調整機能は未実装です（快適印刷システム担当にご相談ください）</small></p>



<?php

$b_kaiteki = !empty($number_kaiteki);

$divide_without_kaiteki = $b_kaiteki
? $delivery_divide - 1
: $delivery_divide;

?>

<p>総部数：<span id="print_number"><?= $print_number_all ?></span> / <?= $print_number_all ?></p>



<table>

<tr>
<th>快適本屋さんOnline</th>
    <td>
        <input type="number" name="number_kaiteki" value="<?= $number_kaiteki ?>" onchange="mod_number_all()">部　※余部除く
    </td>
</tr>

<tr>
<th>自宅</th>
    <td>
        <input class="not_kaiteki" type="number" name="number_home" value="<?= $number_home ?>" onchange="mod_number_all()">部
    </td>
</tr>

<?php
if(count($delivery)):

    foreach($delivery as $deli):

        $i = $deli['id'];

        $b_event = ($deli['type'] == 'event');

        $header_text = $b_event ? 'イベント' : 'その他';

        if ($b_event) {
            $DT = new \Datetime($deli['date']);
            $header_text .= '（'.$DT->format('n/j').'）';
        }

        $info_text = $b_event
        ? $deli['place'].'　'.$deli['name']
        : $deli['real_name'];

        $number = (isset($number_delivery[$i]))
            ? $number_delivery[$i] : $deli['number'];
?>

<tr>
<th><?= $header_text ?></th>
    <td>
        <input type="hidden" name="delivery_header[<?= $i ?>]" value="<?= $header_text ?>">
        <input type="hidden" name="delivery_info[<?= $i ?>]" value="<?= $info_text ?>">
        <input class="not_kaiteki" type="number" name="number_delivery[<?= $i ?>]" value="<?= $number ?>" onchange="mod_number_all()">部　<small><?= $info_text ?></small>
    </td>
</tr>

<?php
    endforeach;
endif;
?>

</table>






<div class="text-center buttons">

    <button type="button" id="go_prev" class="ec-blockBtn--cancel" name="mode" value="back">戻る</button>

    <button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="confirm">内容確認</button>

</div><!-- text-center -->



</form>

</div><!-- form_area -->


<script type="text/javascript">

var global = global || {};

var print_number_all = parseInt('<?= $print_number_all ?? 0 ?>');



// 選択肢1個の場合はロックをかける

$('select').each(function(){

    if ($('option',this).length == 1) {
        $(this).attr('disabled','disabled');
    }
});

// disabled の中身も送信する

$('#go_prev').on('click', function(){
    location.href = '/admin/order_detail?id=<?= $id ?>';
});

$('#go_next').on('click', function(){

    var val = $('#main_buffer_paper').val();

    if (val == 'なし') {
        $('#main_buffer_paper_detail').val('');
    }

    $('select:disabled').removeAttr('disabled');
/*
    var count_divide = 0;
    var n;
    $('input[type=number]').each(function(){
        n = parseInt($(this).val());
        count_divide += (n > 0) ? 1 : 0;
    });

    $('#delivery_divide').val(count_divide);
*/
    $('#form1').submit();
});

function mod_number_all() {

    var number_all = 0;
    var count_divide = 0;
    var count_divide_wo = 0;
    var b = true, n = 0;

    $('input[type=number]').each(function(){
        n = parseInt($(this).val());
        number_all += n;

        count_divide += (n > 0) ? 1 : 0;
    });

    $('#print_number').text(number_all);

    $('#delivery_divide').val(count_divide);

    $('input.not_kaiteki').each(function() {
        n = parseInt($(this).val());
        count_divide_wo += (n > 0) ? 1 : 0;
    });
/*
    $('#delivery_divide')
        .removeAttr('disabled')
        .val(count_divide)
        .attr('disabled','disabled');
*/
    $('#count_without_kaiteki').text(count_divide_wo);

    b = b && (number_all == print_number_all);
//    b = b && (count_divide == divide_without_kaiteki);

    if (b) {
        $('#go_next').removeAttr('disabled');

    } else {
        $('#go_next').attr('disabled','disabled');
    }
}

mod_number_all();

</script>


        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>