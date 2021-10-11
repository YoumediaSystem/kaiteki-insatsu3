<?php

const PAGE_NAME = '会員情報';

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
            <article class="post">

<style>

button[disabled] {
    background-color:#999;
}

table {
    width:calc(100% - 2em);
}

td span {
    display:inline-block;
}

td span + span {
    margin-left:1em;
}

</style>



<?php if(!empty($result_message)): ?>
    <p><em><?= $result_message ?></em></p>
<?php endif; ?>

<?php if(!empty($error) && count($error) > 0): ?>
    <ul id="error_list" class="attention">
    <?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
    </ul>
<?php endif; ?>



<h4>会員no.<?= $id ?? '' ?></h4>

<div id="form_area" class="text">

<form id="form1" method="post" action="/admin/user_form">
<input type="hidden" name="id" value="<?= $id ?>">

<!-- 会員情報 -->

<table>

<tr>
    <th>ステータス</th>
    <td><?= $status_name ?? '' ?></td>
</tr>

<tr>
    <th>快適印刷ポイント</th>
    <td><?= number_format($point ?? 0) ?>pts
    　<a href="/admin/point_detail?id=<?= $id ?>">ポイント履歴管理＞＞</a></td>
</tr>



<?php if(!empty($honya_id) || !empty($youclub_id)): ?>

<tr>
    <th>関連ID</th>
    <td>
        <?php if(!empty($honya_id)): ?>
            <span>快適本屋さんID：<?= $honya_id ?></span>
        <?php endif; ?>

        <?php if(!empty($youclub_id)): ?>
            <span>YouClubサークルID：<?= $youclub_id ?></span>
        <?php endif; ?>
    </td>
</tr>

<?php endif; ?>

<tr>
    <th>メールアドレス</th>
    <td><?= $mail_address ?? '' ?></td>
</tr>

<tr>
    <th>氏名（カナ）性別</th>
    <td><?= $name ?? '' ?>（<?= $name_kana ?? '' ?>）
    <?= in_array($sextype, ['男','女']) ? $sextype.'性' : $sextype ?? '' ?></td>
</tr>

<?php

$DT_birthday = new \Datetime($birthday ?? '');

$birthday_text = $DT_birthday->format('Y年n月j日');

?>
<tr>
    <th>生年月日</th>
    <td><?= $birthday ? str_replace('-', '/', $birthday) : '' ?>　<?= $r18 ? '' : 'R18作品発注不可' ?></td>
</tr>

<tr>
    <th>住所</th>
    <td><?= $zipcode ?? '' ?>　<?= $address1 ?? '' ?>　<?= $address2 ?? '' ?></td>
</tr>

<tr>
    <th>電話番号（連絡可能時間帯）</th>
    <td><?= $tel ?? '' ?><?= !empty($tel_range) ? '（'.$tel_range.'）' : '' ?></td>
</tr>

</table>

<!-- 管理情報 -->


<table>

<tr>
    <th>管理備考</th>
    <td><?= $note ?? '' ?></td>
</tr>

<tr>
    <th>登録日時</th>
    <td><?= $create_date ?? '' ?></td>
</tr>

<tr>
    <th>更新日時</th>
    <td><?= $update_date ?? '' ?></td>
</tr>

<tr>
    <th>更新者</th>
    <td><?= !empty($admin_username) ? $admin_username : '（顧客側）' ?></td>
</tr>

</table>



<p class="buttons text-center">
<button> 編集 </button>
</p>

</form>

</div><!-- form_area -->



<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－ -->

<form id="form2" method="post" action="/admin/user_sendauth">

<p style="text-align:center">
<input type="text" name="mail_address" value="<?= $change_mail_address ?? '' ?>" class="digit-10" placeholder="name@domain.jp">
</p>

<p class="buttons text-center">
<button disabled="disabled"> メールアドレス変更認証を送信 </button>
</p>

</form>



<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－ -->

<form id="form3" method="post" action="/admin/user_mail_edit">
<input type="hidden" name="user_id" value="<?= $id ?>">

<p style="text-align:center">

<!--
<?= print_r($template_list, true) ?>
-->

<select name="template_id">
<?php
if (!empty($template_list) && count($template_list)):

foreach ($template_list as $list):
    if (!empty($list['id'])):
?>
    <option value="<?= $list['id'] ?? '' ?>"><?= $list['subject'] ?? '' ?></option>

<?php endif; endforeach; endif; ?>
</select>

</p>

<p class="buttons text-center">
<button> メール作成 </button>
</p>

</form>



            </article>
        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>