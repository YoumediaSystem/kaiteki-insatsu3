<?php

const PAGE_NAME = 'メンテナンス中';

?><!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
	<meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
	<title><?= PAGE_NAME ?> | <?= $site['name'] ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="stylesheet" type="text/css" media="all" href="/css/style.css">

<style>

@media screen and (max-width:729px) {

.post table th, .post table td {
    display:block;
}

.post table th {
    font-size:0.774rem;
    text-align:left;
}

.post table td {
    padding-bottom:1.5em;
}
}

</style>

	<script src="/js/jquery.js"></script>
	<script src="/js/script.js"></script>
	<script src="/js/pagetop.js"></script>
</head>

<body>

	<?= $view['header'] ?>

	<div id="wrapper">

<!--		<section id="main"> -->

			<section class="content">

				<h3 class="heading">システムメンテナンス中</h3>
				<article class="post">

                <p>ただいまシステムメンテナンス中の為、入稿手続きやお問合せをご利用いただけません。</p>

<table>
    <tr>
        <th>メンテナンス期間</th>
        <td>2021年12月22日　1:00～6:00</td>
    </tr>

    <tr>
        <th>メンテナンス対象</th>
        <td>全機能</td>
    </tr>

    <tr>
        <th>影響範囲</th>
        <td>メンテナンス期間中は、入稿手続き・お支払い・マイページなど全ての機能をご利用いただけません。</td>
    </tr>
</table>

<p>メンテナンス終了後に再度御アクセスください。</p>

<p>お支払い後の入稿に関するお問合せは、各印刷会社様にお問合せください。</p>



				</article>
			</section>

<!--		</section> -->

		<?php // $view['side'] ?>

	</div><!-- wrapper -->

	<?= $view['footer'] ?>

</body>
</html>