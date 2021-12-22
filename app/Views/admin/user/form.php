<?php

const PAGE_NAME = '会員情報変更フォーム';

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

input[name="address1"],
input[name="address2"] {
    margin-top:1em;
}

td input.width_full
{
    width:calc(100% - 1.2em);
}

table {
    width:calc(100% - 2em);
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

            <h3 class="heading"><?= PAGE_NAME ?></h3>



            <div id="form_area" class="text">



<?php if(!empty($error) && count($error) > 0): ?>
<ul id="error_list" class="attention">
<?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
</ul>
<?php endif; ?>



<h4>会員no.<?= $id ?? '' ?></h4>

<form id="form1" method="post" action="/admin/user_confirm">
<input type="hidden" name="mode" value="modify">
<input type="hidden" name="id" value="<?= $id ?>">



<table>

<tr>
    <th>ステータス</th>
    <td>

        <?php $key = 'status'; ?>
        <select name="<?= $key ?>">
            <?php foreach($statusName as $key=>$val):
                $prop = ($val == $status) ? $selected : ''; ?>
                <option value="<?= $key ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr>

<tr>
    <th>快適本屋さん会員no.</th>
    <td><input type="text" name="honya_id" value="<?= $honya_id ?? '' ?>"></td>
</tr>

<tr>
    <th>YouClubサークルno.</th>
    <td><input type="text" name="youclub_id" value="<?= $youclub_id ?? '' ?>"></td>
</tr>

<tr>
    <th>メールアドレス</th>
    <td><input type="text" name="mail_address" value="<?= $mail_address ?? '' ?>" placeholder="name@domain.jp"></td>
</tr>

<tr>
    <th>氏名</th>
    <td>
        <input type="text" name="sei" value="<?= $sei ?? '' ?>" class="digit-6" placeholder="姓">
        <input type="text" name="mei" value="<?= $mei ?? '' ?>" class="digit-6" placeholder="名">
    </td>
</tr><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<tr>
    <th>氏名カナ</th>
    <td>
        <input type="text" name="sei_kana" value="<?= $sei_kana ?? '' ?>" class="digit-6" placeholder="姓カナ">
        <input type="text" name="mei_kana" value="<?= $mei_kana ?? '' ?>" class="digit-6" placeholder="名カナ">
    </td>
</tr><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<?php $key = 'birthday';

$birthday_y = $birthday_y ?? $now_y;
$birthday_m = $birthday_m ?? 1;
$birthday_d = $birthday_d ?? 1;

?>

<tr>
    <th>生年月日</th>
    <td>

        <?php $kkey = $key.'_y'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-6">
            <option value="">-</option>
            <?php foreach($select_y as $val):
                $prop = ($val == $birthday_y) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_m'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_m as $val):
                $prop = ($val == $birthday_m) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_d'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_d as $val):
                $prop = ($val == $birthday_d) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr><!-- －－－－－－－－－－－－－－－－－－－－－ -->


<tr>
    <th>性別</th>
    <td>
    
        <label>
        <?php $prop = ($sextype == '女') ? $checked : ''; ?>
        <input type="radio" name="sextype" value="女"<?= $prop ?>> 女
        </label>
        
        <label>
        <?php $prop = ($sextype == '男') ? $checked : ''; ?>
        <input type="radio" name="sextype" value="男"<?= $prop ?>> 男
        </label>

        <label>
        <?php $prop = ($sextype == '未選択') ? $checked : ''; ?>
        <input type="radio" name="sextype" value="未選択"<?= $prop ?>> 未選択
        </label>
        
    </td>
</tr><!-- －－－－－－－－－－－－－－－－－－－－－ -->


<tr>
    <th>郵便番号・住所</th>
    <td>

        <div class="h-adr">
        <span class="p-country-name" style="display:none;">Japan</span>

        <input name="zipcode" type="text" class="p-postal-code digit-8" size="8" maxlength="8" value="<?= $zipcode ?? '' ?>" placeholder="1234567" style="display:inline-block"><br>

        <input name="address1" type="text" class="p-region p-locality p-street-address p-extended-address width_full" value="<?= $address1 ?? '' ?>" placeholder="都道府県・市区町村（自動入力）">

        <input name="address2" type="text" class="width_full" value="<?= $address2 ?? '' ?>" placeholder="番地・建物名・部屋番号">
        
        </div>
    </td>
</tr><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<tr>
    <th>電話番号</th>
    <td>
        <input type="text" name="tel" value="<?= $tel ?? '' ?>" class="width_full" placeholder="0123456789">
    </td>
</tr><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<tr>
    <th>連絡可能時間帯</th>
    <td>
        <input type="text" name="tel_range" value="<?= $tel_range ?? '' ?>" class="width_full">

    </td>
</tr><!-- －－－－－－－－－－－－－－－－－－－－－ -->

<tr>
    <th>管理備考</th>
    <td>
        <textarea name="note" class="width_full"><?= $note ?? '' ?></textarea>
    </td>
</tr><!-- －－－－－－－－－－－－－－－－－－－－－ -->

</table>


<div class="text-center buttons">

    <button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="confirm">内容確認</button>

</div><!-- text-center -->



</form>

</div><!-- form_area -->


<script type="text/javascript">

var global = global || {};



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

</script>


        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

<script src="//yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>

</body>
</html>