<?php

const PAGE_NAME = 'ログイン中';

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


<p style="text-align:center">お待ちください...</p>

<?php

$action = (!empty($param['redirect_to']))
    ? $param['redirect_to']
    : '/user/mypage';

?>

<form id="form" method="post" action="<?= $action ?>">
    <input type="hidden" name="message" value="ログインしました。">

</form>

<script>

    $('#form').submit();

</script>


</body>
</html>