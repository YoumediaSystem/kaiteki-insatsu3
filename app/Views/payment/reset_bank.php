<?php

const PAGE_NAME = 'ネットバンク決済キャンセル';

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


    <form id="send_params" method="post" action="/order/detail">
        <input type="hidden" name="id" value="<?= $order_id ?>">
        <input type="hidden" name="result_message" value="手続中のネットバンク決済をキャンセルしました。">
    </form>


<script>
    $('#send_params').submit();
</script>

</body>
</html>