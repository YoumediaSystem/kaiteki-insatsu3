<?php

const PAGE_NAME = '認証メール送信しました | パスワード忘れ変更';

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

	<?= $view['header'] ?>

	<div id="wrapper">

<!--		<section id="main"> -->

			<section class="content">

				<h3 class="heading"><?= PAGE_NAME ?></h3>
				<article class="post">

                <p>24時間以内にメール内のURLにアクセスし、会員登録を完了してください。</p>

<p>メールが届かない場合は、迷惑メールフォルダもご確認ください。</p>

<?php if($environ != 'real' && !empty($auth_url)): ?>
<p>
    <a href="<?= $auth_url ?>">認証をスキップする＞</a>
</p>
<?php endif; ?>



				</article>
			</section>

<!--		</section> -->

		<?php // $view['side'] ?>

	</div><!-- wrapper -->

	<?= $view['footer'] ?>

</body>
</html>