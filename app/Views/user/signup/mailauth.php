<?php

const PAGE_NAME = '認証メール送信中';

?><!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
	<meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
	<title><?= PAGE_NAME ?> | <?= $site['name'] ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="stylesheet" type="text/css" media="all" href="/css/style.css">
	<script src="/js/jquery.js"></script>
	<script src="/js/script.js"></script>
	<script src="/js/pagetop.js"></script>
</head>

<body>

<p style="text-align:center">※お待ちください※</p>


    <?php if (empty($error) || !count($error)): ?>

    <form id="send_params" method="post" action="/user/signup_send">
        <input type="hidden" name="hash" value="<?= $hash ?>">
    </form>

    <?php else: ?>

    <form id="send_params" method="post" action="/user/signup_auth">

        <?php foreach($error as $val): ?>
        <input type="hidden" name="error[]" value="<?= $val ?>">
        <?php endforeach; ?>

    </form>

    <?php endif; ?>


<script>
    $('#send_params').submit();
</script>

</body>
</html>