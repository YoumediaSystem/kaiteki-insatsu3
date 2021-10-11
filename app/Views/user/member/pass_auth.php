<?php



const PAGE_NAME = 'メール認証 | パスワード変更';

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

                    <p>
                        <?= $user['name'] ?>様
                    </p>

                    <p>※現在のお知らせはありません。</p>


				</article>
			</section>

<!--		</section> -->

		<?php // $view['side'] ?>

	</div><!-- wrapper -->

	<?= $view['footer'] ?>

</body>
</html>