<?php
//session_start();

const PAGE_NAME = 'お問合せフォーム';

//include_once(__DIR__.'/_config.php');

$DT = new DateTime();
$now_y = (int)$DT->format('Y');

$now_datetext = $DT->format('Y/n/j');

$select_y = range($now_y - 100, $now_y + 1);
$select_m = range(1,12);
$select_d = range(1,31);

if(empty($param['payment_date_y'])) $param['payment_day_y'] = $now_y;


$checked	= ' checked="checked"';
$selected	= ' selected="selected"';
$disabled	= ' disabled="disabled"';
$hidden		= ' style="display:none"';
$select = [];

function get_class_text($key, $necessary) {
    return in_array($key, $necessary) ? 'class="ec-label required"' : 'class="ec-label"';}

function get_must_text($key, $necessary) {
    return in_array($key, $necessary) ? '<span class="input_must">必須</span>' : '';}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
    <meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
    <title><?= PAGE_NAME ?> | <?= $site['name'] ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/form.css">

<style>

    .input_must_point {
        color:#c00;
        font-weight:bold;
    }

    .input_must_point {
        display:none;
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

    <script src="/js/warning_freemail.js"></script>
</head>

<body>

    <?= $view['header'] ?>

    <div id="wrapper">

        <section class="content">




<h2 class="heading"><?= PAGE_NAME ?></h2>



<div id="form_area" class="text">



<?php if(!empty($param['error']) && count($param['error']) > 0): ?>
<ul id="error_list" class="attention">
<?php foreach($param['error'] as $message) echo '<li>'.$message.'</li>'; ?>
</ul>
<?php endif; ?>



<form id="form1" method="post" action="/contact/check">
<input type="hidden" name="set_id" value="<?= $param['set_id'] ?? '' ?>">


<h3>基本情報</h3><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<!--<table>-->
<div class="ec-borderedDefs">

<!-- 基本情報 -->

<dl>
<dt><?php
$key = 'contact_type';

$select['contact_type'] = [
    '入稿発注について'
    ,'お支払いについて'
    ,'ポイント移動申請'
    ,'システム不具合'
    ,'その他質問'
];
?>
<h4 class="ec-label">お問合せの種類</h4></dt>
<dd>
    <div class="ec-input">

        <select id="contact_type" name="contact_type">
            <?php foreach($select[$key] as $val):
                $prop = (!empty($$key) && $val == $$key) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><?php $key = 'user_id'; ?>
<h4 class="ec-label">会員no.</h4></dt>
<dd>
    <div class="ec-input">

        <?php if (!empty($user['id'])): ?>
            <input type="hidden" name="user_id" value="<?= $user['id'] ?? '' ?>" class="digit-8">
            <?= $user['id'] ?>

        <?php else: ?>

            <input type="number" name="user_id" value="<?= $user_id ?? '' ?>" class="digit-8">
        
        <?php endif; ?>
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><?php $key = 'order_id'; ?>
<h4 class="ec-label">発注no.</h4></dt>
<dd>
    <div class="ec-input">
        <input type="number" name="order_id" value="<?= $order_id ?? '' ?>" class="digit-8">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><?php $key = 'mail_address'; ?>
<h4 class="ec-input require">メールアドレス</h4><span class="input_must">必須</span></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="mail_address" value="<?= $mail_address ?? '' ?>" class="width_full" placeholder="name@domain.jp">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><?php $key = 'real_name'; ?>
<h4 class="ec-input require">氏名</h4><span class="input_must">必須</span></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="<?= $key ?>" value="<?= $real_name ?? '' ?>" class="width_full" placeholder="印刷　刷子">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl>
<dt><?php $key = 'real_name_kana'; ?>
<h4 class="ec-input require">氏名カナ</h4><span class="input_must">必須</span></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="<?= $key ?>" value="<?= $real_name_kana ?? '' ?>" class="width_full" placeholder="インサツ　スルコ">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl class="point">
<dt><?php
$key = 'trans_point_type';

$select['trans_point_type'] = [
    '快適本屋さんから→快適印刷さんに'
    ,'快適印刷さんから→快適本屋さんに'
];
?>
<h4 class="ec-label">ポイント移動内容</h4><span class="input_must point">必須</span></dt>
<dd>
    <div class="ec-input">

        <select id="trans_point_type" name="trans_point_type">
            <option value="">（未選択）</option>
            <?php foreach($select[$key] as $val):
                $prop = (!empty($$key) && $val == $$key) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl class="point">
<dt><?php $key = 'points'; ?>
<h4 class="ec-label">移動ポイント数</h4><span class="input_must point">必須</span></dt>
<dd>
    <div class="ec-input">
        <input type="number" name="points" value="<?= $points ?? 0 ?>" class="digit-8">ポイント
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl class="order">
<dt><h4 class="ec-label">原稿URL</h4></dt>
<dd>
    <div class="ec-input">
        <input type="text" name="url" value="<?= $url ?? '' ?>" class="width_full" placeholder="https://domain.jp">
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<dl class="not_point">
<dt><?php $key = 'detail'; ?>
<h4 class="ec-input require">お問合せ内容</h4><span class="input_must not_point">必須</span></dt>
<dd>
    <div class="ec-input">
        <textarea name="detail" class="width_full" placeholder="お問い合わせ内容をご入力ください"><?= $detail ?? '' ?></textarea>
    </div>
</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->


</div><!-- ec-borderedDefs -->

</div><!-- wrap_group_other -->





<div class="text-center buttons">

    <button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="confirm">確認ページへ</button>

</div><!-- text-center -->



</form>

</div><!-- form_area -->


<script type="text/javascript">

var global = global || {};

var array_target = [];

array_target[0] = 'input[name="mail_address"]';
//array_target[1] = 'input[name="product_mail_address"]';

warning_freemail(array_target); // フリーメールドメインが入力されたら警告表示する


// 選択肢1個の場合はロックをかける

$('select').each(function(){

    if ($('option',this).length == 1) {
        $(this).attr('disabled','disabled');
    }
});

// disabled の中身も送信する

$('#go_next').on('click', function(){

    $('select:disabled').removeAttr('disabled');
    $('#form1').submit();
});

$('#contact_type').on('change', function(){

    var v = $(this).val();
    mod_must(v);
});

function mod_must(contact_type) {

    if (contact_type == 'ポイント移動申請') {
        $('.point').show();
        $('.not_point, dl.order').hide();

    } else if (contact_type == '入稿発注について') {
        $('.point').hide();
        $('.not_point, .order').show();

    } else {
        $('.point, .order').hide();
        $('.not_point').show();
    }
}

mod_must($('#contact_type').val());

</script>



</section>



</div><!-- wrapper -->



<script>

var global = global || {};

</script>



<?= $view['footer'] ?>



</body>
</html>