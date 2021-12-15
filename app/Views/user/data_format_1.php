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

				<h1 class="heading">快適印刷さん 原稿データ形式について</h1>
				<article class="post">
<!--				
					<p class="dateLabel"><time datetime="2014-09-01">2014/09/01</time></p>
-->

<!-- ↓大陽出版内の案内をコピーしました -->
<a name="tonbo"></a>
<h2>トンボ</h2>
	原稿の中心と大きさを示す線の事をトンボといいます。</br>
	トンボは必ず全ページに入れてください。（一部例外があります）</br>
	入ってない場合は一度お客様に返却していれていただくことがあります。この場合、納品希望日に間に合わない事があります。</br>
	<h1>コーナートンボとセンタートンボ</h1>
	<table>
		<tr>
			<th rowspan="2">原稿用紙</br><img src="/img/data_format/tonbo1.png"></th>
			<th><span style="font-color:red">コーナートンボ</span></th>
			<td>原稿の右上・左上・右下・左下の4つの角にあるトンボです。</br>コーナートンボで絵柄の4か所の角が決まります。</td>
		</tr>
		<tr>
			<th><span style="font-color:blue">センタートンボ</span></th>
			<td>原稿の上中央・下中央・右中央・左中央にあるトンボです。</br>センタートンボで左右の中心位置・上下の中心位置が決まります</br>
		</tr>
	</table>
	</br>
	<h1>トンボがない原稿</h1>
	トンボがない場合、どこを基準に印刷をするか分からなくなってしまいます。
	<table>
		<tr>
			<th>トンボがない原稿</th>
			<th colspan="2">結果</th>
		<tr>
			<th rowspan="2"><img src="/img/data_format/tonbo2.png"></th>
			<td><img src="/img/data_format/tonbo3.png"></td>
			<td>位置を示す基準がないので、上下左右の位置がずれてしまう。</td>
		</tr>
		<tr>
			<td><img src="/img/data_format/tonbo4.png"></td>
			<td>水平垂直を示す基準がないので、絵が傾いてしまう。</td>
		</tr>
	</table>
	</br>
	<h1>トンボが不要な原稿</h1>
		画像の中心合わせで良い場合、トンボは必要ありません。</br>
		※illustratorで作成されたデータは、必ずトンボを入れて下さい。
</p>
<!--↑トンボ-->
<!--↓ぬりたしと仕上がり線-->
<p class="kankaku">
<a name="nuritashi"></a>
<h2>塗り足し・仕上がり線(断ち切り線)</h2>
	印刷をする時、原稿は<a href="/data_format_1#tonbo">トンボ</a>が付いた状態で印刷されます。</br>
	印刷物はトンボで断裁されますが、僅かなずれが生じてしまう場合がございます。</br>
	塗り足しを付けることで僅かなずれが発生した際に、余白が出てしまうことを防ぐことができます。</br>
	<table>
		<tr>
			<th rowspan="2"><img src="/img/data_format/nuritashi1.png" width="300"></th>
			<th>塗り足し</th>
			<td>
			実際の本の大きさより一回り大きく絵を描く範囲を、「塗り足し」と言います。</br>
			塗り足しを付けることで、断裁時にズレが発生した時に余白が出てしまう事を防ぎます。</br>
			</td>

		</tr>
		<tr>
			<th>仕上がり線(断ち切り線)</th>
			<td>
			実際の本の大きさに断裁する位置を、「仕上がり線」と言います。</br>
			仕上がり線近くに書かれた文字や絵は、断裁時にズレが発生した時切れてしまう恐れがあります。</br>
			大切な文字や絵は、仕上がり線より３ミリ以上内側に納めて下さい。</br>
			</td>
		</tr>
	</td>
	</tr>
	</table>
	<h1>塗り足し</h1>
	絵柄などをページ端いっぱいに入れる（裁ち落とし）には、原稿に裁ちしろが必要です。</br>
	この裁ちしろのことを「塗り足し」といい、製本時に生じるズレをカバーするために必要な部分になります。</br>
	弊社では3～5ミリの塗り足しを付ける事を推奨しております。</br>
	<table>
		<tr>
			<th>原稿</th>
			<th colspan="2">結果</th>
		</tr>
		<tr>
			<th>塗り足しなし</br><img src="/img/data_format/nuritashi2.png"></th>
			<td><img src="/img/data_format/nuritashi4.png"></td>
			<td>本の端に余白が出てしまう可能性があります</td>
		<tr>
		<tr>
			<th>塗り足しあり</br><img src="/img/data_format/nuritashi3.png"></th>
			<td><img src="/img/data_format/nuritashi5.png"></td>
			<td>本の端まで絵があるように仕上がります</td>
		</tr>
	</table>
	<h1>ぬりたしが不要な原稿</h1>
	天地左右のいずれの端も白の時は、塗り足しを付けない原寸サイズで大丈夫です。
	<h1>仕上がり線(断ち切り線)</h1>
	多少の誤差が生じるため、仕上り線のギリギリに描かれた絵や文字は欠ける場合があります。</br>
	大切な絵や文字は断ち切り線から３ミリ以上離してください。
	<table>
		<tr>
			<th>原稿</th>
			<th colspan="2">結果</th>
		</tr>
		<tr>
			<th>仕上がり線の外側に文字がある</br><img src="/img/data_format/tachi1.png"></th>
			<td><img src="/img/data_format/tachi4.png"></td>
			<td>仕上がり線より外側の文字は切れてしまいます</td>
		<tr>
		<tr>
			<th>仕上がり線から</br>3mm未満の範囲に文字がある</br><img src="/img/data_format/tachi2.png"></th>
			<td><img src="/img/data_format/tachi5.png"></td>
			<td>仕上がり線に近いため、文字の一部が欠けてしまいます</td>
		</tr>
		<tr>
			<th>仕上がり線から</br>3mm以上内側に文字がある</br><img src="/img/data_format/tachi3.png"></th>
			<td><img src="/img/data_format/tachi6.png"></td>
			<td>切れてしまうことなく、文字はしっかりと収まります</td>
		</tr>
	</table>

</p>
<!--↑ぬりたし・仕上がり線-->
<!--↓ノンブル-->
<p class="kankaku">
<a name="non"></a>
<h2>ノンブル</h2>
ノンブル（ページ番号）は、本文全てのページに1つずつ付ける連続した番号です。</br>
本文開始ページは、何ページ始まりになってもかまいません。</br>
	<h1>注意点</h1>
	<lo>
		<li>ノンブルは、必ず全てのページに入れて下さい。</li>
		<li>必ず印刷される範囲内（仕上がり線より内側）に入れて下さい。</li>
		<li>途中で飛ばすことなく、必ず連続した番号で入れて下さい。</li>
	</lo>
	<h1>ノンブルを入れる位置</h1>
	ノンブルは印刷される範囲内（仕上がり線より内側）に入れなければなりません。</br>
	出来上がった本にノンブルを見せたくない場合、隠しノンブルを入れてください。</br>
	<table>
		<tr>
			<th colspan="3"><strong>悪い例</strong></th>
			<th><b>良い例</b></th>
		</tr>
		<tr>
			<td>余白にある</br><img src="/img/data_format/non1.png"></td>
			<td>ぬりたしにある</br><img src="/img/data_format/non2.png"></td>
			<td>文字切れの位置にある</br><img src="/img/data_format/non3.png"></td>
			<td>仕上がり線より内側にある</br><img src="/img/data_format/non4.png"></td>
		</tr>
	</table>
	<h1>隠しノンブル</h1>
	<p>
	出来上がった本にノンブルを見せたくない場合、隠しノンブルを使用します。</br>
	隠しノンブルは、本の綴じ方向の仕上がり線に沿う様に入れる事で、のどの内側にノンブルを隠す方法です。</br>
	<img src="/img/data_format/caution03_book.gif">
	</p>
	<p>
	<b>隠しノンブルの一例</b>
	<table>
		<tr>
			<th>右綴じの場合</th>
		</tr>
		<tr>
			<td>
			<img src="/img/data_format/nonmigi.png"></br>
			右綴じの場合、1番初めのページの背は「右」側になります。</br>
			そのため、1番初めのノンブルは「右」、次のページは「左」、以降交互にノンブルを入れます。
			</td>
		</tr>
		<tr>
			<th>左綴じの場合</th>
		</tr>
		<tr>
			<td>
			<img src="/img/data_format/nonhidari.png"></br>
			左綴じの場合、1番初めのページの背は「左」側になります。</br>
			そのため、1番初めのページは「左」、次のページは「右」、以降交互にノンブルを入れます。
			</td>
		</tr>
	</table>
<!--↑ノンブル-->



				</article>
			</section>

<!--		</section> -->

		
	</div><!-- wrapper -->

	<?= $view['footer'] ?>

</body>
</html>