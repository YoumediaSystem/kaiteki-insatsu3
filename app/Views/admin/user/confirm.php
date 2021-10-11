<?php

const PAGE_NAME = '内容確認 | 会員情報変更';

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

            <h3 class="heading"><?= PAGE_NAME ?></h3>

<style>

button[disabled] {
    background-color:#999;
}

table {
    width:calc(100% - 2em);
}

</style>



            <div id="form_area" class="text">

                <form id="form1" method="post" action="/admin/user_do">
                <input type="hidden" name="mode" value="modify">
                <input type="hidden" name="from" value="confirm">
                <input type="hidden" name="id" value="<?= $id ?? '' ?>">

                <table>

<tr>
    <th>ステータス</th>
    <td>
        <input type="hidden" name="status" value="<?= $status ?? '' ?>">
        <?= $statusName[$status] ?? '' ?>
    </td>
</tr>

<tr>
    <th>快適本屋さんID</th>
    <td>
        <input type="hidden" name="honya_id" value="<?= $honya_id ?? '' ?>">
        <?= $honya_id ?? '' ?>
    </td>
</tr>

<tr>
    <th>YouClubサークルID</th>
    <td>
        <input type="hidden" name="youclub_id" value="<?= $youclub_id ?? '' ?>">
        <?= $youclub_id ?? '' ?>
    </td>
</tr>

<tr>
    <th>メールアドレス</th>
    <td>
        <input type="hidden" name="mail_address" value="<?= $mail_address ?? '' ?>">
        <?= $mail_address ?? '' ?>
    </td>
</tr>

<tr>
    <th>氏名（カナ）</th>
    <td>
        <input type="hidden" name="sei" value="<?= $sei ?? '' ?>">
        <input type="hidden" name="mei" value="<?= $mei ?? '' ?>">
        <input type="hidden" name="sei_kana" value="<?= $sei_kana ?? '' ?>">
        <input type="hidden" name="mei_kana" value="<?= $mei_kana ?? '' ?>">
        
        <?= $sei ?? '' ?> <?= $mei ?? '' ?>
        （<?= $sei_kana ?? '' ?> <?= $mei_kana ?? '' ?>）
    </td>
</tr>

<tr>
    <th>性別</th>
    <td>
        <input type="hidden" name="sextype" value="<?= $sextype ?? '' ?>">
        <?= in_array($sextype, ['男','女']) ? $sextype.'性' : $sextype ?? '' ?>
    </td>
</tr>

<?php

$DT_birthday = new \Datetime($birthday ?? '');

$birthday_text = $DT_birthday->format('Y年n月j日');

?>
<tr>
    <th>生年月日</th>
    <td>
        <input type="hidden" name="birthday" value="<?= $birthday ?? '' ?>">
        <input type="hidden" name="birthday_y" value="<?= $birthday_y ?? '' ?>">
        <input type="hidden" name="birthday_m" value="<?= $birthday_m ?? '' ?>">
        <input type="hidden" name="birthday_d" value="<?= $birthday_d ?? '' ?>">
        <?= $birthday ? str_replace('-', '/', $birthday) : '' ?>
</td>
</tr>

<tr>
    <th>住所</th>
    <td>
        <input type="hidden" name="zipcode" value="<?= $zipcode ?? '' ?>">
        <input type="hidden" name="address1" value="<?= $address1 ?? '' ?>">
        <input type="hidden" name="address2" value="<?= $address2 ?? '' ?>">

        <?= $zipcode ?? '' ?>　<?= $address1 ?? '' ?>　<?= $address2 ?? '' ?>
    </td>
</tr>

<tr>
    <th>電話番号（連絡可能時間帯）</th>
    <td>
        <input type="hidden" name="tel" value="<?= $tel ?? '' ?>">
        <input type="hidden" name="tel_range" value="<?= $tel_range ?? '' ?>">
        <?= $tel ?? '' ?><?= !empty($tel_range) ? '（'.$tel_range.'）' : '' ?>
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

    $('#form1').attr('action','/admin/user_form');
    $('#form1').submit();
});

$('#go_next').on('click', function(){

    $('#form1').attr('action','/admin/user_do');
    $('#form1').submit();
});

</script>

</body>
</html>