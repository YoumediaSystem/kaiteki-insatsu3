<?php

const PAGE_NAME = '販売数を更新中';

if ($result === false) $error[] = '販売数を更新できません';

?><!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
	<meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
	<title><?= PAGE_NAME ?> |【管理】<?= $site['name'] ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="stylesheet" type="text/css" media="all" href="/css/style.css">
	<script src="/js/jquery.js"></script>
	<script src="/js/script.js"></script>
	<script src="/js/pagetop.js"></script>
    </head>

<body>

<p style="text-align:center">※お待ちください※</p>

<form id="send_params" method="post" action="/admin/product_detail">

    <?php
    if (empty($error) || !count($error)): ?>
        <input type="hidden" name="id" value="<?= $id ?? 0 ?>">
        <input type="hidden" name="result_message" value="販売数を更新しました。">
    <?php
    
    else:
        foreach($error as $val): ?>
            <input type="hidden" name="error[]" value="<?= $val ?>">
    <?php
        endforeach;
    endif;
    ?>

</form>

<script>
    $('#send_params').submit();
</script>

</body>
</html>