<?php

const PAGE_NAME = '会員管理';

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
            <article class="post">



<?php if(!empty($result_message)): ?>
    <p><em><?= $result_message ?></em></p>
<?php endif; ?>

<?php if(!empty($error) && count($error) > 0): ?>
    <ul id="error_list" class="attention">
    <?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
    </ul>
<?php endif; ?>



<form id="form_search" method="post" action="/admin/user">
<input type="hidden" id="mode" name="mode" value="search">

<table>

<tr>
    <th>メールアドレス</th>
    <td><input type="text" name="mail_address" value="<?= $mail_address ?? '' ?>" placeholder="name@domain.jp"></td>
</tr>

<tr>
    <th>氏名カナ</th>
    <td>
        <input type="text" name="sei_kana" value="<?= $sei_kana ?? '' ?>" placeholder="セイ">
        <input type="text" name="mei_kana" value="<?= $mei_kana ?? '' ?>" placeholder="メイ">
    </td>
</tr>

<tr>
    <th>電話番号</th>
    <td><input type="text" name="tel" value="<?= $tel ?? '' ?>" placeholder="0123456789"></td>
</tr>

<?php /*
<tr>
<th>ステータス</th>
<td>
<?php
    $b_status_input = (isset($param['status']) && is_numeric($param['status']));

    $status_list = $printReserve->getListStatus($admin['role']);
?>
    <select name="status">
    <option value="">（全て）</option>

    <?php foreach($status_list as $val):

    if ($val == 0) continue;

    $prop = ($b_status_input && $param['status'] == $val) ? $selected : '';
    ?>
    <option value="<?= $val ?>"<?= $prop ?>><?= $printReserve->getStatusName($val) ?></option>
    <?php endforeach; ?>

</select>

</td>
</tr>
*/ ?>

</table>

<input type="hidden" id="page" name="page" value="0">

<p class="buttons text-center">
<button> 検索 </button>
</p>

</form>



<?php if (isset($pager['count_all'])): ?>

    <p>絞込結果 <?= $pager['count_all'] ?>件　<?php

    for ($i=0; $i<=$pager['max_page']; $i++):

        $page_number =  $i+1;

        if ($i != 0) echo ' '; // delimiter

        if ($i == $page): echo $page_number;
        
        else: ?>
        <button type="button" onclick="changePage(<?= $i ?>)"><?= $page_number ?></button>
    <?php endif;

    endfor; ?>
    </p>

<table>

    <tr>
    <th style="width:2em">ID</th>
    <th style="width:16em">氏名（カナ）</th>
    <th>メールアドレス</th>
    <th style="width:5em">ステータス</th>
    </tr>

    <?php

    foreach ($user_list as $data): ?>

    <tr>
    <th style="width:2em"><?= $data['id'] ?></th>
    <td><a href="/admin/user_detail?id=<?= $data['id'] ?>"><?= $data['name'] ?></a><small>（<?= $data['name_kana'] ?>）</small></td>
    <td><?= $data['mail_address'] ?></td>
    <td><?= $statusName[$data['status']] ?></td>
    </tr>

    <?php endforeach; ?>

</table>

<?php endif; // user_list ?>



            </article>
        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>