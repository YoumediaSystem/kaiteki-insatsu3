<?php



const PAGE_NAME = '入稿する';

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

<!--	<section id="main"> -->

			<section class="content">

				<p>下記印刷所で製造および納品・搬入を納品・搬入を承ります。<br>
				データの作成方法や搬入については、各印刷所の公式WEBサイトをご参照ください。</p>


				<h3 class="heading">大陽出版株式会社
				<span><a href="https://taiyoushuppan.co.jp/">公式サイト</a></span>
				</h3>

				<article class="post">
<!--
					<p class="dateLabel"><time datetime="2021-03-03">2021/3/3</time></p>
-->
					<table>

						<tr>
						<th>セット名</th>
						<th>表紙</th>
						<th>本文</th>
						</tr>

						<tr>
						<td><a href="order/?set_id=standard">快適スタンダードセット</a></td>
						<td>
							<img src="img/icon/color_full.png" width="32" style="width:2rem" alt="">
							<span>オフセット印刷フルカラー</span>
						</td>
						<td>
							<img src="img/icon/color_mono.png" width="32" style="width:2rem" alt="">
							<span>オフセット印刷スミ一色</span>
						</td>
						</tr>

						<tr>
						<td><a href="order/?set_id=ondemand">快適オンデマンドセット</a></td>
						<td>
							<img src="img/icon/color_full.png" width="32" style="width:2rem" alt="">
							<span>オンデマンド印刷フルカラー</span>
						</td>
						<td>
							<img src="img/icon/color_mono.png" width="32" style="width:2rem" alt="">
							<span>オンデマンド印刷スミ一色</span>
						</td>
						</tr>

					</table>

				</article>



			</section>

<!--	</section> -->

		<?php //$view['side'] ?>

	</div><!-- wrapper -->

	<?= $view['footer'] ?>

</body>
</html>