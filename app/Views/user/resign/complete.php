<?php



const PAGE_NAME = '退会しました';

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
この度は快適印刷さんをご利用いただき、
誠にありがとうございました。
</p>

<p>
アンケート内容は今後の貴重なご意見として受けとめ、
品質・サービス改善に務めてまいります。
</p>

<p>
またのご利用をお待ち申し上げます。
</p>



				</article>
			</section>

<!--		</section> -->

		<?php // $view['side'] ?>

	</div><!-- wrapper -->

	<?= $view['footer'] ?>

</body>
</html>