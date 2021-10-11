<?php

const PAGE_NAME = '内容確認 | メルマガ一括設定';

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

#request_date {
    font-size:1.13rem;
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

            <h2 class="heading"><?= PAGE_NAME ?></h2>
            <article class="post">


<?php if(!empty($result_message)): ?>
    <p><em><?= $result_message ?></em></p>
<?php endif; ?>

<?php if(!empty($error) && count($error) > 0): ?>
    <ul id="error_list" class="attention">
    <?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
    </ul>
<?php endif; ?>



<form id="form1" method="post" action="/admin/mail_magazine_do">
<input type="hidden" name="mode" value="<?= $mode ?? '' ?>">

<?php foreach($user_id as $key=>$val): ?>
    <input type="hidden" name="user_id[]" value="<?= $val ?? '' ?>">
<?php endforeach; ?>

<input type="hidden" name="template_id" value="<?= $template_id ?? '' ?>">

<input type="hidden" name="request_date" value="<?= $request_date ?? '' ?>">



<p>以下内容でメルマガ登録します。</p>



<h3>送信開始日時</h3>

<p id="request_date" class="text-center"><b><?= str_replace('-','/',$request_date ?? '') ?></b></p>



<h3>件名・本文</h3>

<table>

<tr>
    <th>メール件名サンプル</th>
    <td><?= $subject_sample ?? '' ?></td>
</tr>

<tr>
    <th>本文サンプル</th>
    <td><pre><?= $body_sample ?? '' ?></pre></td>
</tr>

</table>



<h3>送信対象者</h3>

<table>

<tr>
    <th>ID</th>
    <th>氏名</th>
    <th>メールアドレス</th>
</tr>

<?php
if (isset($user_list) && is_array($user_list)):

    foreach($user_list as $key=>$user): ?>
<tr>
    <th><?= $user['id'] ?? '' ?></th>
    <td><?= $user['name'] ?? '' ?></td>
    <td><?= $user['mail_address'] ?? '' ?></td>
</tr>
<?php endforeach; endif; ?>

</table>



<div class="text-center buttons">

    <button type="button" id="go_prev" class="ec-blockBtn--cancel" name="mode" value="form">再編集</button>

    <button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="confirm">内容確定</button>

</div><!-- text-center -->

                </form>


            
            </div><!-- form_area -->

        </section>

	</div><!-- wrapper -->

	<?= $view['footer'] ?>

<script type="text/javascript">

var global = global || {};

$('#go_prev').on('click', function(){

    $('#form1').attr('action','/admin/mail_magazine');
    $('#form1').submit();
});

$('#go_next').on('click', function(){

    $('#form1').attr('action','/admin/mail_magazine_do');
    $('#form1').submit();
});

</script>

</body>
</html>