<?php

const PAGE_NAME = '原稿データ形式について';

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

</style>

			<section class="content">

				<h1 class="heading">すご盛セット/パック 原稿データ形式について</h1>
				<article class="post">
<!--				
					<p class="dateLabel"><time datetime="2014-09-01">2014/09/01</time></p>
-->

<!-- ↓大陽出版内の案内をコピーしました -->
<h2>ご注意頂きたいこと</h2>
お客様による原稿の不備、不足、発注内容の誤り等があっても、ご入稿後の内容変更・キャンセルは、原則として受付できません。</br>
確認のため作業がストップした場合､ご希望の納品日までにお届け出来ない場合もございます。</br>
<strong>ご入稿前にもう一度、原稿のご確認をお願い致します。</strong></br>
<h1>ページ</h1>
<p>
<input type="checkbox">原稿･ファイル数は正しい</br>
<input type="checkbox">原稿・ファイル名は重複していない
</p>
<p>
<strong>【ご注意】</strong></br>
※実際のページ数がご発注内容より多い場合、印刷料金が加算される場合があります。</br>
※奇数の場合印刷できません。偶数ページでご入稿ください。</br>
※確認のため作業が止まります。
</p>
<h1><a href="/data_format_1#non">ノンブル</a></h1>
<p>
<input type="checkbox">全ての原稿データにノンブルが入っている</br>
<input type="checkbox">通し番号になっている</br>
<input type="checkbox">ノンブルは重複していない</br>
</p>
<p>
<strong>【ご注意】</strong></br>
※ノンブルの不足,誤ったノンブルは、乱丁の原因となります。
</p>
<table width="600">
	<tr>
		<th colspan="3">多く見られる不具合の例</th>
	</tr>
	<tr>
		<th width="200">ファイル名と一致しない</th>
		<th width="200">ノンブルが小さすぎる</th>
		<th width="200">絵や文字に重なっている</th>
	</tr>
	<tr height="80">
		<td>※ノンブルとファイル名の数字を一致させてください</td>
		<td>※3mm以上で入れてください（7pt程度）</td>
		<td>※見やすい場所へ移動させるか白文字で入れてください</td>
	</tr>
	<tr>
		<th>塗り足しの中に入っている</th>
		<th>トンボの外に入っている</th>
		<th>ノンブルが読みづらい</th>
	</tr>
	<tr height="80">
		<td>※印刷範囲内に入れてください</td>
		<td>※印刷範囲内に入れてください</td>
		<td>※見やすい書体で入れてください</td>
	</tr>
</table>
<h1>原稿の内容</h1>
<p>
<input type="checkbox">発注書と原稿の内容・タイトルは一致している</br>
<input type="checkbox">奥付が入っている</br>
<input type="checkbox">【成人向けの場合】表1(オモテ表紙)に成人(R18)マークが入っている
</p>
<p>
<strong>【ご注意】</strong></br>
※原稿と発注書はタイトルや発行日を一致させてください。</br>
※奥付・成人向けの記載については、各印刷所の最新情報をご確認下さい。
</p>
<table>
	<tr>
		<th colspan="3">多く見られる不具合の例</th>
	</tr>
	<tr>
		<th width="200">文字切れ･セリフ抜け･白紙</th>
		<th width="200">トーンのアミ点が細かすぎる</th>
		<th width="200">下書きが残っている</th>
	</tr>
	<tr height="80">
		<td>※効果の場合､お知らせください（例) P.9セリフ無しデザイン</td>
		<td>※飛んだりつぶれたりします。10％～40％以下､85線以内を目安にしてください。</td>
		<td>※鉛筆線の消し残りやデータ書き出しの際､下書きレイヤーのチェックにご注意ください</td>
	</tr>
</table>
<h1>データ</h1>
<p>
<input type="checkbox">保存形式は正しい（入稿可能な形式になっている）</br>
<input type="checkbox">仕上がりサイズとデータのサイズは一致している</br>
<input type="checkbox">ファイルは正常に開くことができる</br>
</p>
<p>
<strong>【ご注意】</strong></br>
※推奨の形式で保存してください</br>
※保存後はファイルが開くか一度開いて確認してください
</p>
<table>
	<tr>
		<th colspan="3">多く見られる不具合の例</th>
	</tr>
	<tr>
		<th colspan="3">PhotoshopなどのPSD､TIFF､PhotoshopEPS</th>
	</tr>
	<tr>
		<th width="200">画像がセンターにない</th>
		<th width="200">画像統合されていない</th>
		<th width="200">解像度が低い</th>
	</tr>
	<tr height="80">
		<td>※センターに配置してください。トンボが無い場合､画像のセンター合わせでトリミングします</td>
		<td>※画像統合してください。文字や非表示レイヤーなどイメージが変わる場合があります</td>
		<td>※推奨解像度で保存してください。低すぎると粗い仕上がりになってしまいます</td>
	</tr>
	<tr>
		<th colspan="3">Illustratorなどのai､IllustratorEPS</th>
	</tr>
	<tr>
		<th width="200">トンボが無い</th>
		<th width="200">アウトライン化されていない</th>
		<th width="200">配置画像がない</th>
	</tr>
	<tr height="80">
		<td>※Iillustratorの場合トンボは必ず入れてください</td>
		<td>※文字は必ずアウトライン化してください</td>
		<td>※埋め込んでいない場合､必ず同じ階層に保存してください</td>
	</tr>
	<tr>
		<th colspan="3">Word､InDesignなどのPDF</th>
	</tr>
	<tr>
		<th width="200">フォントが埋め込まれていない</th>
		<th width="200"><a href="/data_format">Quartzで出力したPDFデータ</a></th>
		<th width="200">文字化け･段組みの崩れ</th>
	</tr>
	<tr height="80">
		<td>※必ず埋め込んでください。文字化けの原因となります</td>
		<td>「Quartz」で出力されたPDFはお客様の意図しない仕上がりになる可能性がございます</td>
		<td>※保存後は書き出したファイルを開いて確認してください</td>
	</tr>
</table>
<h1>アンソロジー･合同誌</h1>
<p>
<b>主催者様</b>
	<lo>
		<li>作家様に本ページを必ずご確認頂くようにお伝えください。</li>
		<li>原稿は全てそろった時点で上記を再度ご確認ください。</li>
		<li>表紙､目次などペンネームに間違いがないかもう一度､ご確認ください。</li>
	</lo>
</p>
<p>
<b>作家様</b>
	<lo>
		<li>印刷所の推奨形式で原稿を作成してください。</li>
		<li>出来る限り、印刷所のテンプレートをご利用ください。</li>
	</lo>
</p>

<!-- ↑大陽出版内の案内をコピーしました -->

<p>



				</article>
			</section>

<!--		</section> -->

		
	</div><!-- wrapper -->

	<?= $view['footer'] ?>

</body>
</html>