<?php

include_once('php/lib.php');


include_once('order/_schedule_model.php');

const PAGE_NAME = '締切情報';

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

<style>

section h1 {font-size:2rem; font-weight:bold}

.post h2 {font-size:2rem; font-weight:normal; color:#555}

.post h3 {font-size:1.5rem;}

.post h4 {font-size:1.33rem;}

.post h5 {font-size:1.13rem;}

.post h5 {font-size:1rem;}

.buttons {text-align:center}

.buttons a {
	display: inline-block;
	font-weight: bold;
	letter-spacing: 0.05em;
	font-size: 1rem;
	color: #FFFFFF;
	border-radius: 0.33rem;
	padding: 1rem;
	text-shadow: 0px -0.05rem 0.1rem rgb(0 0 0 / 33%);
	box-shadow: 0px 0.1rem 0.33rem rgb(0 0 0 / 33%);
	-webkit-transition: all 0.15s ease;
	-moz-transition: all 0.15s ease;
	-o-transition: all 0.15s ease;
	transition: all 0.15s ease;

	background: linear-gradient(to bottom, #01b097, #017d6a);
	border: solid #01b097 0.1rem;
}

section.content .buttons a {
	text-decoration: none;
}

.now_limit {
	text-align:center;
	font-weight:bold;
	font-size:1.13em;
	padding:1em 0;
	margin-bottom:1em;

	border:0.25em solid #999;
	border-radius:0.33em;
}

</style>

			<section class="content">

				<h1 class="heading"><?= PAGE_NAME ?></h1>
				<article class="post">
<!--				
					<p class="dateLabel"><time datetime="2014-09-01">2014/09/01</time></p>
-->

<h2>入稿締切について</h2>

<p style="font-size:1.13em"><strong>毎週水曜10:00締切</strong></p>

<?php

$border_delivery_date = get_border_delivery_date();

$upload_border_date = get_upload_border_date();

$early_limit = get_early_limits();

?>

<dl>
<dt><?php $key = 'upload_border_date'; ?>
<h4>直近の入稿・入金締切</h4></dt>
<dd>
	<div class="ec-input now_limit">
		<?= $border_delivery_date.get_youbi_text($border_delivery_date) ?>以降の開催イベント合わせの場合、
		<em><?= $upload_border_date.get_youbi_text($upload_border_date) ?> 10時まで</em>
	</div>
<!--
<?= print_r($early_limit) ?>
-->
<?php if (count($early_limit)): ?>

	<p class="attention">以下日程の直接搬入イベント合わせは入稿締切が通常よりも早くなりますのでご注意ください。</p>

	<table id="early_limit_list">
		<tr>
			<th>開催日</th>
			<th>入稿締切</th>
		</tr>

		<?php foreach ($early_limit as $row): ?>

			<tr>
			<td><?= $row['event'].get_youbi_text($row['event']) ?></td>
			<td><?= $row['print_up_limit'].get_youbi_text($row['print_up_limit']) ?></td>
			</tr>

		<?php endforeach; ?>
	</table>

<?php endif; ?>

</dd>
</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->



<p>締切までに発注申込と入金が完了している場合のみ受注いたします。締切後の入金および入金連絡は受注いたしかねます。</p>

<p>例外としてコミックマーケット合わせ納期などの繁忙期は、通常より早い締切設定になる場合がございます。</p>


<h3>データ差し替えについて</h3>

<p>データの差し替えは快適印刷さんではなく、発注後に各印刷会社にお問い合わせください。</p>

<p>発注直後で各印刷会社からまだ連絡が無い場合は、連絡があるまでお待ちください。</p>


<h3>納品エリアについて</h3>

<p>
表記の締切は、東京・大阪・名古屋など首都圏とその周辺地域が対象となります。
</p>

<p>
北海道・九州・沖縄への納品は最遅締切エリア外のため、表記の締切より1日前倒しとなります。
</p>

<p>
日本国外への納品は対応しておりません。
</p>



				</article>
			</section>

<!--		</section> -->

		<?php // $view['side'] ?>

	</div><!-- wrapper -->

	<?= $view['footer'] ?>

</body>
</html>