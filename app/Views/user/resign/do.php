<?php

const PAGE_NAME = '退会中';

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

<form id="send_params" method="post" action="/user/resign_complete">

    <?php
    if (empty($error) || !count($error)): ?>
        <input type="hidden" name="id" value="<?= $id ?? '' ?>">
    <?php
    
    else:
        foreach($param as $key):
            if ($key != 'error'): ?>
                <input type="hidden" name="<?= $key ?>" value="<?= $param[$key] ?>">
    <?php
            endif;
        endforeach;
        
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