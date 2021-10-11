<?php

const PAGE_NAME = 'ポイント履歴管理';

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

            <h3 class="heading">会員no.<?= $id ?? '' ?>　<?= PAGE_NAME ?></h3>
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

.text-right {
    text-align:right;
}

h4 {
    margin: 1em 0;
    text-align:center;
}

#point_history {
    width:100%;
}

.post table td.number {
    text-align:right;
}

.minus {
    color:#c00;
}

tr.not_activate td {
    background-color:#eee;
}

.date {width:6em;}
.point {width:2.3em;}
.status, .ui {width:2.3em; text-align:center !important}
</style>



<?php if(!empty($result_message)): ?>
    <p><em><?= $result_message ?></em></p>
<?php endif; ?>

<?php if(!empty($error) && count($error) > 0): ?>
    <ul id="error_list" class="attention">
    <?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
    </ul>
<?php endif; ?>



<p>
<?= $name ?? '' ?>（<?= $name_kana ?? '' ?>）

<?php if(!empty($honya_id)): ?>
    <span>　快適本屋さんID：<?= $honya_id ?></span>
<?php endif; ?>

<?php if(!empty($youclub_id)): ?>
    <span>　YouClubサークルID：<?= $youclub_id ?></span>
<?php endif; ?>
</p>



<p class="text-right">
    <a href="/admin/user_detail?id=<?= $id ?>">顧客詳細に戻る＞＞</a>
</p>



<?php
foreach($history as $data) {
    if (!empty($data['expire_date'])) {
        $expire_text = $data['expire_date'];
        break;
    }
}
?>
<form method="post" action="/admin/point_edit">

    <p>
        現在のポイント　<?= number_format($point) ?>pt　有効期限：<?= str_replace('-','/',($expire_text ?? '')) ?>
        
        <input type="hidden" name="user_id" value="<?= $data['user_id'] ?>">
        <button>新規ポイント履歴追加</button>
    </p>

</form>




<div id="form_area" class="text">



<table id="point_history">

<tr>
    <th class="date">日付</th>
    <th class="point">pt</th>
    <th>内容</th>
    <th class="status">状況</th>
    <th class="ui">&nbsp;</th>
</tr>

<?php foreach($history as $data):
    
    $b_activate = ($data['status'] == 0);

    $class_text = !$b_activate ? ' class="not_activate"' : '';

    $date_text = str_replace('-','/',$data['create_date']);
?>

<tr<?= $class_text ?>>
    <td class="date"><?= $date_text ?? '' ?></td>
    <td class="number point"><?= number_format($data['point']) ?? '' ?></td>
    <td><?= $data['detail'] ?? '' ?></td>
    <td class="status"><?= $b_activate ? '有効' : '無効' ?></td>
    <td class="ui">
        <form method="post" action="/admin/point_edit">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">
            <input type="hidden" name="user_id" value="<?= $data['user_id'] ?>">
            <button>編集</button>
        </form>
    </td>
</tr>

<?php endforeach; ?>

</table>



</div><!-- form_area -->

<script>

$('.number:contains("-")').addClass('minus');

</script>



            </article>
        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>