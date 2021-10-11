<?php

const PAGE_NAME = '決済状況照会中';

$debug = false;
//$debug = true;

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

<form id="send_params" method="post" action="/admin/payment_detail">
<input type="hidden" name="id" value="<?= $id ?? 0 ?>">

    <?php
    if (empty($error) || !count($error)): ?>
        <input type="hidden" name="result_message" value="決済状況照会しました。">
    <?php
    
    else:
        foreach($error as $val): ?>
            <input type="hidden" name="error[]" value="<?= $val ?>">
    <?php
        endforeach;
    endif;
    ?>

<?php if($debug): ?>

    <p><button>　決済詳細ページに移動する　</button></p>

<?php endif; ?>

</form>

<?php if(!$debug): ?>
<script>
    $('#send_params').submit();
</script>
<?php endif; ?>

</body>
</html>