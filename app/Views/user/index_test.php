<?php



?><!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
	<meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
	<title><?= $site['name'] ?></title>
	<meta name="description" content="<?= $site['name'] ?>のサイトです。簡単見積もり簡単入稿！イベント間際の極道入稿も大歓迎です！">
	<meta name="keywords" content="<?= $site['name'] ?>,オンライン入稿支援">

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="stylesheet" type="text/css" media="all" href="/css/style.css">
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>

	<script src="/js/jquery.js"></script>
	<script src="/js/script.js"></script>
	<script src="/js/pagetop.js"></script>

	<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

</head>

<body>

	<?= $view['header'] ?>

	<div id="wrapper">

		<style>
			.slick-slider {
				margin-bottom: 30px;
			}

			.slick-dots {
				position: absolute;
				bottom: -45px;
				display: block;
				width: 100%;
				padding: 0;
				list-style: none;
				text-align: center;
			}

			.slick-dots li {
				position: relative;
				display: inline-block;
				width: 20px;
				height: 20px;
				margin: 0 5px;
				padding: 0;

				cursor: pointer;
			}

			.slick-dots li button {
				font-size: 0;
				line-height: 0;
				display: block;
				width: 20px;
				height: 20px;
				padding: 5px;
				cursor: pointer;
				color: transparent;
				border: 0;
				outline: none;
				background: transparent;
			}

			.slick-dots li button:hover,
			.slick-dots li button:focus {
				outline: none;
			}

			.slick-dots li button:hover:before,
			.slick-dots li button:focus:before {
				opacity: 1;
			}

			.slick-dots li button:before {
				content: " ";
				line-height: 20px;
				position: absolute;
				top: 0;
				left: 0;
				width: 12px;
				height: 12px;
				text-align: center;
				opacity: .25;
				background-color: black;
				border-radius: 50%;

			}

			.slick-dots li.slick-active button:before {
				opacity: .75;
				background-color: black;
			}

			.slick-dots li button.thumbnail img {
				width: 0;
				height: 0;
			}

			#mainBanner {
				position:relative;
				box-sizing:border-box;
/*				border: 1px solid #000;*/
				width:100%;

				display: grid;
				grid-template-columns: calc(50% - 5px) calc(50% - 5px);
				gap: 10px;

				text-align:left;
			}

			#wrap_offset {
				grid-row: 1; grid-column: 1;
			}

			#wrap_ondemand {
				grid-row: 1; grid-column: 2;
			}

			#mainBanner dt {
				font-weight: bold;
				color: #fff;
				border-radius: 0.5em;
				width: 5.5em;
				padding: 0.25em 0.5em;
			}

			#mainBanner dd + dt {
				margin-top:1em;
			}

			dt.specialty {
				background-color:#00836e;
			}

			dt.merit {
				background-color:#0044cc;
			}

			dt.demerit {
				background-color:#cc00aa;
			}

		</style>

		<div id="mainBanner">

			<div id="wrap_offset"><a href="/order/?set_id=standard">
				<img src="img/button/top_offset.png" alt="快適スタンダードセット"></a>
				
				<dl>
					<dt class="specialty">特徴</dt>
					<dd>商業誌印刷と同じ方法で印刷します。</dd>

					<dt class="merit">メリット</dt>
					<dd>仕上がりが高精細で、大部数でも1冊あたりの金額が安くなります！</dd>

					<dt class="demerit">デメリット</dt>
					<dd>30冊未満では注文できません。また冊数が少ないと割高になります。</dd>
				</dl>
				
			</div><!-- wrap_offset -->

			<div id="wrap_ondemand"><a href="/order/?set_id=ondemand">
				<img src="img/button/top_ondemand.png" alt="快適オンデマンドセット"></a>
				
				<dl>
					<dt class="specialty">特徴</dt>
					<dd>高機能コピー機で印刷します。</dd>

					<dt class="merit">メリット</dt>
					<dd>10冊から注文可能で、基本料金はオフセットよりも安くなります！</dd>

					<dt class="demerit">デメリット</dt>
					<dd>印刷の仕上がりはコピー機と同程度になります。</dd>
				</dl>
				
			</div><!-- wrap_offset -->

<!--
			<div class="item slick-slide"><a href="/order/?set_id=standard">
				<img src="img/slider/standard.jpg" alt="快適スタンダードセット"></a></div>
			<div class="item slick-slide"><a href="/order/?set_id=ondemand">
				<img src="img/slider/ondemand.jpg" alt="快適オンデマンドセット"></a></div>
-->
		</div>

		<script>
		/*
		$(function() {
			$('#mainBanner').slick({
				dots: true,
				arrows: false,
				autoplay: true,
				speed: 500
			});
		});
		*/
		</script>

	<section class="content">
		<h3 class="heading">快適印刷さんとは？</h3>
		<article class="post">

		<div class="ec-borderedDefs">

			<p>オンラインで印刷入稿できるサービスを提供しております。</p>

			<p>
			入稿フォームではリアルタイムに合計金額をチェックできて、
			お支払いはPaypal、銀行振込に対応しております。
			</p>

			<p>入稿には一般的なオンラインストレージサービスをお使いいただくことで、
			水曜10時締切での受付も可能となっております。</p>

		</div>

		</article>
	</section>

	<section class="content">
		<h3 class="heading">お知らせ</h3>
		<article class="post">

		<div class="ec-borderedDefs">

			<dl>
			<dt>
			<h4>2021.3.3</h4></dt>
			<dd>
				快適印刷さんサイトオープン！2つのセットから選んでオンライン入稿できます。
			</dd>
			</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

		</div>

		</article>
	</section>

<!--
	<section class="gridWrapper">
		<article class="grid">
			<div class="box">
				<img width="320" height="320" src="images/banners/eyecatch1.jpg" alt="" />
				<h3>サンプル株式会社の取り組み</h3>
				<p>ホームページサンプル株式会社の取り組み ホームページサンプル株式会社では最新技術と自然との調和を目指します。革 &#8230;</p>
				<p class="readmore"><a href="sample.html">詳細を確認する</a></p>
			</div>
		</article>
		<article class="grid">
			<div class="box">
				<img width="320" height="320" src="images/banners/eyecatch2.jpg" alt="" />
				<h3>自然との調和を目指す企業</h3>
				<p>ホームページサンプル株式会社の取り組み ホームページサンプル株式会社では最新技術と自然との調和を目指します。革 &#8230;</p>
				<p class="readmore"><a href="sample.html">詳細を確認する</a></p>
			</div>
		</article>
		<article class="grid">
			<div class="box">
				<img width="320" height="320" src="images/banners/eyecatch3.jpg" alt="" />
				<h3>ホームページ株式会社の取り組み</h3>
				<p>ホームページサンプル株式会社の取り組み ホームページサンプル株式会社では最新技術と自然との調和を目指します。革 &#8230;</p>
				<p class="readmore"><a href="sample.html">詳細を確認する</a></p>
			</div>
		</article>
	</section>
-->

	</div><!-- wrapper -->

	<?= $view['footer'] ?>

</body>
</html>