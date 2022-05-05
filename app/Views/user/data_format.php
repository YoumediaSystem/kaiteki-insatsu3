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
<!--
<p class="attention">現在追加コンテンツ作成中</p>
-->

<!-- ↓大陽出版内の案内をコピーしました -->
<h2>対応ファイル形式</h2>

<div id="applications">
	<table>
	<thead>
		<tr>
		<th class="title">アプリケーションソフト</th>
		<th class="datatype">対応保存形式</th>
		<th class="note">注意事項</th>
		</tr>
	</thead>
	<tbody>
		<tr>
		<th class="title">
			<p>Adobe Photoshop</p>
			<p>Corel Painter</p>
			<p>CLIP STUDIO</p>
			<p>Comic Studio</p>
			<p>COMIC WORKS</p>
		</th>
		<td>
			<p>PSD</p>
			<p>TIFF（LZW圧縮可）</p>
			<p>Photoshop EPS</p></td>
		<td>
			<p><span class="small">１ページ＝１ファイルで保存し、ノンブルとファイル番号を統一してください。</span></p>
			<p class="small">
			<span class="small">※小説などの文字の多い原稿の場合、エンコードの圧縮によって劣化する場合がありますので、Photoshop EPS以外をおすすめいたします。</span></p>
		</td>
		</tr>
		<tr>
		<th class="title">
			<p>Adobe Illustrator</p>
			<p>～CS6、CC</p>
		</th>
		<td>
			<p>ai</p>
			<p>Illustrator EPS</p>
		</td>
		<td>
			<p>作成したバージョンで保存してください。</p>
			<p>透明効果は事前にご相談ください。</p>
			<p class="small">１ページ＝１ファイルで保存し、ノンブルとファイル名を統一してください。</p>
		</td>
		</tr>
		<tr>
		<th class="title">
			Microsoft Word<br />
			2007～2010
		</th>
		<td>
			PDF</br>
			<span class="small">推奨:ISO19005-1に準拠(PDF/A)</span>
		</td>
		<td rowspan="2">
			<p><span class="red">※.doc / .inddファイルでのご入稿は対応しておりません。</span><br />
			必ず下記の「PDF形式」でご入稿ください。<span class="red"><br /></span></p>
			<ul>
			<li><span class="red">本文データのみ</span></li>
			<li>フォントの埋め込み<br /></li>
			<li>カラーオブジェクトを含まない<span class="red"><br /></span></li>
			<li>１ファイル内に含まれる始まりと終わりのノンブルをファイル番号にして保存してください。<br />
			<span class="small">例）ノンブル：３ページ～16ページ　→　ファイル名：003-016.pdf</span><br />
			</li>
			</ul>
		</tr>
		<tr>
		<th class="title"><p>Adobe InDesign</p>
		</th>
		<td>
			<p>PDF<br />
			<span class="small">推奨:PDFX1a 2001 JPN推奨</span></p>
		</td>
		</tr>
	</tbody>
	</table>
    </div>
<p>データの送り直しの場合は、希望の納期に間に合わなくなることや、割増手数料が発生する事があります。</p>
<p class="red">データの最終チェックは確実にお願いします。</p>
<p>&nbsp;</p>
<p><strong>【MacOSXの機能 Quartzで出力したPDFデータについて】</strong></p>
<p><u>MacOSXの機能「Quartz」でPDFを出力されると、お客様の元でご確認いただいた状態では問題なくても、別環境で開いた場合、以下の不具合が確認されております。</u><br />
・あるはずのオブジェクトや文字が消える<br />
・不要なオブジェクトが出る    </p>
<p>不具合回避のため、「Quartz」を使用せず別の方式で出力されたPDFでご入稿ください。<br />
「Quartz」で出力されたPDFをご入稿いただいた場合、お客様の意図しない仕上がりになる可能性がございますので予めご了承ください。</p>
<p>&nbsp;</p>
<p><strong>【その他フリーソフトで出力したPDFデータについて】</strong></p>
<p>その他のフリーソフトで作成されたPDFは不具合が生じる可能性があります。</p>
<p>事前にサンプルデータをお送り頂くなど、ご入稿前の事前チェックをお勧めいたします。<br />
<!-- ↑大陽出版内の案内をコピーしました -->

<h2>原稿サイズ</h2>

<!--↓※とりあえずのイメージです-->
原稿は必ず仕上がり原寸+<a href="/data_format_1#nuritashi">ぬりたし(天地左右各3mm以上)</a>で作成をしてください。</br>
文字切れ，モアレなどの危険がある為、拡縮対応は出来ません。お客様でサイズの調整をお願いいたします。</br>

<h1>本文・表紙（表1・4を別々に作成時）の原稿サイズ</h1>
	<p class="text-center"><img src="/img/data_format/sizepic1.png"></p></br>
	<table>
		<tr>
			<th colspan="2"> </th>
			<th>原寸サイズ</th>
			<th>ぬりたし(各3mm)を含むサイズ</th>
		</tr>
		<tr><!--幅-->
			<th rowspan="4">幅</th>
			<th>文庫(A6)</th>
			<td width="150">105ミリ</td>
			<td width="150">111ミリ</td>
		</tr>
		<tr>
			<th>B6本</th>
			<td>128ミリ</td>
			<td>134ミリ</td>
		</tr>
		<tr>
			<th>A5本</th>
			<td>148ミリ</td>
			<td>154ミリ</td>
		</tr>
		<tr>
			<th>B5本</th>
			<td>182ミリ</td>
			<td>188ミリ</td>
		</tr>
		<tr><!--高さ-->
			<th rowspan="6">高さ</th>
			<th>文庫(A6)</th>
			<td>148ミリ</td>
			<td>154ミリ</td>
		</tr>
		<tr>
			<th>B6本</th>
			<td>182ミリ</td>
			<td>188ミリ</td>
		</tr>
		<tr>
 			<th>A5本</th>
			<td>210ミリ</td>
			<td>216ミリ</td>
		</tr>
		<tr>
			<th>B5本</th>
			<td>257ミリ</td>
			<td>263ミリ</td>
		</tr>
	</table>
	<p>&nbsp;</p>
	<p>【表1・4を別々に作成時】</p>
	背幅が6mmを超える場合、背表紙の原稿が必要になります。
</p>
<p>
<h1>表紙（表1・4を1枚で作成時）の原稿サイズ</h1>
	表紙を見開きで作成する場合、横幅は仕上がり原寸+ぬりたし<strong>+背幅</strong>になります。</br>
	背幅は使用する本文用紙の厚さによって異なりますので、各印刷会社でご確認ください。</br>
	<p>
	<ol>
		<ul><a href="https://www.taiyoushuppan.co.jp/doujin/howto/thickness.php"target="_blank">大陽出版株式会社</a></ul>
		<ul>印刷会社A</ul>
		<ul>印刷会社B</ul>
	</ol>
	</p>
	<p class="text-center"><img src="/img/data_format/sizepic2.png"></p>
</br>
	<table>
		<tr>
			<th colspan="2"> </th>
			<th>原寸サイズ</th>
			<th>ぬりたし(各3mm)を含むサイズ</th>
		</tr>
		<tr><!--幅-->
			<th rowspan="5">幅</th>
			<th>文庫(A6)</th>
			<td width="150">210ミリ+背幅</td>
			<td>216ミリ+背幅</td>
		</tr>
		<tr>
			<th>新書</th>
			<td>220ミリ+背幅</td>
			<td>226ミリ+背幅</td>
		</tr>
		<tr>
			<th>B6本</th>
			<td>256ミリ+背幅</td>
			<td>262ミリ+背幅</td>
		</tr>
		<tr>
			<th>B5本</th>
			<td>364ミリ+背幅</td>
			<td>370ミリ+背幅</td>
		</tr>
		<tr>
			<th>A5本</th>
			<td>296ミリ+背幅</td>
			<td>302ミリ+背幅</td>
		</tr>
		<tr><!--高さ-->
			<th rowspan="6">高さ</th>
			<th>文庫(A6)</th>
			<td>148ミリ</td>
			<td>154ミリ</td>
		</tr>
		<tr>
			<th>B6本</th>
			<td>182ミリ</td>
			<td>188ミリ</td>
		</tr>
		<tr>
 			<th>A5本</th>
			<td>210ミリ</td>
			<td>216ミリ</td>
		</tr>
		<tr>
			<th>B5本</th>
			<td>257ミリ</td>
			<td>263ミリ</td>
		</tr>
	</table>
</p>
<!--↑※とりあえずのイメージです-->

<!--↓これは出来たら良いな程度のイメージです
<p>
<h1>原稿サイズ 簡易確認</h1>
	<script src="scheck.js"></script>
	<form name="scheck">
		作りたい本のサイズ：
		<select id="bsize">
			<option value="1">文庫(A6)</option>
			<option value="2">B6本</option>
			<option value="3">A5本</option>
			<option value="4">B5本</option>
		</select></br>
		背幅：
		<input type="value"name="shaba"value="各印刷所でご確認ください">ミリ</br>
		<input type="button"name="tview"value="表示"onclick="stdWeight(scheck)"></br>
		</br>
		<p>
		<b>ぬりたし(天地左右3mm)を含む原稿のサイズ</b></br>
		本文・表紙（表1・4を別々に作成時）の原稿サイズ：</br>
		幅<input type="text"name="nyoko1"value="ぬりたしを含む幅">
		×
		高さ<input type="text"name="ntate1"value="ぬりたしを含む高さ">
		ミリ</br>
		表紙（表1・4を1枚で作成時）の原稿サイズ：</br>
		幅<input type="text"name="nyoko2"value="ぬりたしを含む幅">
		×
		高さ<input type="text"name="ntate2"value="ぬりたしを含む高さ">
		</p>
		<p>
		<b>本の仕上がりサイズ</b></br>
		幅<input type="text"name="yoko1"value="幅">
		×
		高さ<input type="text"name="tate1"value="高さ">
		ミリ</br>
		</p>

	</form>
	背幅がぬりたし幅(6mm)を超える場合、背表紙の原稿が必要になります。</br>
</p>
↑これは出来たら良いな程度のイメージです-->

<h2>解像度</h2>

<p><strong>dpiは「pixel / inch」を選択してください。</strong></p>
<p>Illustratorの配置画像も同様の解像度で作成してください。</p>
<p>表1・4を別々で作成した場合でも、両方の解像度を同じにして下さい。</p>

<h1>カラーの解像度</h1>
<table>
	<tbody>
	<tr>
        	<th>カラーモード</th>
        	<th>解像度</th>
	</tr>
	<tr>
	        <th>CMYKフルカラー</th>
        	<td>350 ～ 440 dpi</td>
	</tr>
	<tr>
        	<th>RGBフルカラー</th>
        	<td>350 ～ 440 dpi</td>
	</tr>
	</tbody>
</table>

<h1>モノクロの解像度</h1>
<table>
	<tbody>
	<tr>
        	<th>カラーモード</th>
        	<th>解像度</th>
	</tr>
	<tr>
        	<th>グレースケール</th>
		<td>600 dpi</td>
	</tr>
	<tr>
		<th>モノクロ2階調</th>
		<td>600 または 1200 dpi</td>
	</tr>
	</tbody>
</table>

<h2>レイヤー</h2>
<table>
	<tr>
		<th>アプリケーションソフト</th>
		<th> </th>
	</tr>
	<tr>
		<th>
			Adobe Photoshop</br>
			Corel Painter</br>
			CLIPSTUDIO</br>
			Comic Studio</br>
			COMIC WORKS</br>
		</th>
		<td>
			レイヤーは最終的に「画像統合」して下さい</br>
			（レイヤー名が自動的に「背景」になります）。
		</td>
	</tr>
	<tr>
		<th>
			Adobe Illustrator
		</th>
		<td>
			レイヤーは結合してください。</br>
			また、トラブルのもとになりますので非表示レイヤーは必ず、すべて削除してください。 
		</td>
	</tr>
</table>

<h2>モード</h2>
<h1>カラー</h2>
<p>
<strong>「CMYK」または「RGB」を選択してください。</strong>
</p>

<p>
<b>【CMYKとRGBの違い】</b></br>
CMYKは色の三原色、RGBは光の三原色になります。</br>
RGBはパソコンなどのモニターや光を発するものに使われる色で、印刷に使われるCMYKとは色の領域が全く違うため、再現できない色が出てきてしまいます。</br>
色域警告「！」マークの出る色は特に注意が必要です。</br>
<strong>CMYKの色域でカバーできない色は、実際に印刷した際きれいに再現出来ない</strong>ことをご了承ください。</br>
（蛍光色・メタル色といった色は表現できませんので沈んだ色合いになります）</br>
※印刷会社でのRGBからCMYKへの変換をご希望の場合、完全には再現出来ない色がありますので予めご了承ください。
</p>

<p>
<b>【カラーモードCMYKのオンデマンド原稿でグレースケール表現される場合】</b></br>
インクKのみでグレーになるよう設定してください。
</p>

<p>
<b>【Adobe Illustratorで作成を行う場合】</b></br>
<a href="/data_format_2#illustrator">Adobe Illustrator</a>のご案内をご確認ください。
</p>

<h1>モノクロ</h1>
<p>
<strong>「グレースケール」または「モノクロ2階調」を選択してください。</strong>
</p>

<p>
<b>【グレースケールとモノクロ2階調】</b>
<table>
	<tr>
		<th></th>
		<th width="400">グレースケール</th>
		<th width="400">モノクロ2階調</th>
	</tr>
	<tr>
		<th>サンプル</th>
		<td><img src="/img/data_format/sample_gray.png"></td>
		<td><img src="/img/data_format/sample_mono.png"></td>
	</tr>
	<tr>
		<th>特徴</th>
		<td>
		黒と白を含む灰色(256色)で表現されます。</br>
		・アミ点分解処理をして印刷します。</br>
		・アミ点(ドット)を使っていないデータ写真やグラデーションに向いています。
		</td>
		<td>
		黒と白でてきています。</br>
		・アミ点(ドット)を使ったデータに向いています。</br>
		・印刷所でのアミ点分解をしませんので、基本的にはデータ以上にモアレが発生する事はありません。</br>
		※ご入稿データの時点でモアレがあった場合は、モアレが発生する恐れがございます。
		</td>
	</tr>
	<tr>
		<th>ご注意</th>
		<td>
		<p>
		【グレーのベタ】</br>
		隣接するグレーの濃度が近すぎると同じ色に見えます。</br>
		特に<strong>黒ベタの中に濃い灰色を使う際は80%未満</strong>でないと見えなくなります。
		</p>
		<p>
		【モアレ】</br>
		次の様な原稿はモアレが発生する危険があります。</br>
		・アミ点にアンチエイリアスが付いている</br>
		・アミ点にエアブラシ消しゴムが使用されている</br>
		・アミ点がグレーになっている</br>
		・グレーのベタとトーンが重なっている</br>
		・アミ点を貼った後に拡縮をしている 他
		</p>
		</td>
		<td>
		モノクロ2階調に変換をする場合、色々な選択ができます。</br>
		変換方法によっては、印刷に適さないデータになってしまう場合もあります。</br>
		詳細につきましては、各印刷所の案内をご確認ください。
		</td>
	</tr>
</table>
</p>

<h2>各アプリケーションソフトごとのご案内</h2>
<ol>
	<ul><a href="/data_format_2#clip">CLIP STUDIO</a></ul>
	<ul><a href="/data_format_2#Photoshop">Adobe Photoshop</a></ul>
	<ul><a href="/data_format_2#illustrator">Adobe Illustrator</a></ul>
	<ul><a href="/data_format_2#Word">Microsoft Word</a></ul>
	<ul><a href="/data_format_2#indesign">Adobe InDesign</a></ul>
</ol>

<h2>ご入稿前チェックリスト</h2>
<p>
<a href="/data_format_3"><img src="/img/data_format/attention.png"></a>
</p>

<!--↓元データ とりあえず非表示
<p>Illustrator（AI, EPS）、Photoshop（PSD）、AdobePDF（PDF）、TIFF（TIF）</p>

<ul class="attention">
    <li>CLIP STUDIOご利用の場合はPSDまたはTIFF形式で保存してください。</li>
    <li>Word、InDesignご利用の場合はPDF形式で保存してください。</li>
</ul>

<h3>表紙と裏表紙を1枚絵で作成する場合（表1・4合同）</h3>

<table>

    <tbody><tr>
        <th>用紙サイズ</th>
        <th>幅 × 高さ　トンボ断ち切り</th>
        <th>幅 × 高さ　塗り足しを含む</th>
    </tr><tr>

    </tr><tr>
        <th>A6（文庫）</th>
        <td>幅（210 + 背幅） × 高さ 148</td>
        <td>幅（216 + 背幅） × 高さ 154</td>
    </tr><tr>

    </tr><tr>
        <th>B6</th>
        <td>幅（256 + 背幅） × 高さ 182</td>
        <td>幅（262 + 背幅） × 高さ 188</td>
    </tr><tr>

    </tr><tr>
        <th>A5</th>
        <td>幅（296 + 背幅） × 高さ 210</td>
        <td>幅（302 + 背幅） × 高さ 216</td>
    </tr><tr>
    
    </tr><tr>
        <th>B5</th>
        <td>幅（364 + 背幅） × 高さ 257</td>
        <td>幅（370 + 背幅） × 高さ 263</td>
    </tr><tr>

</tr></tbody></table>

<p class="attention">単位：mm（ミリメートル）、断ち切り時に空白が生じないよう各用紙サイズ（幅のみ2倍＋規定の背幅）の外周にトンボ（トリムマーク）と、上下左右3mmづつ塗り足し部分を追加してください。</p>

<p class="attention">背幅は表紙と本文の用紙選択やページ数によって異なります。具体的な数値は「<a href="https://www.taiyoushuppan.co.jp/doujin/howto/thickness.php" target="_blank">背幅の計算方法 | 大陽出版株式会社</a>」ページであらかじめご算出ください。</p>


<h3>表紙と裏表紙を分けて作成する場合（表1・4別）</h3>

<table>

    <tbody><tr>
        <th>用紙サイズ</th>
        <th>幅 × 高さ　トンボ断ち切り</th>
        <th>幅 × 高さ　塗り足しを含む</th>
    </tr><tr>

    </tr><tr>
        <th>A6（文庫）</th>
        <td>幅 105 × 高さ 148</td>
        <td>幅 111 × 高さ 154</td>
    </tr><tr>

    </tr><tr>
        <th>B6</th>
        <td>幅 128 × 高さ 182</td>
        <td>幅 134 × 高さ 188</td>
    </tr><tr>

    </tr><tr>
        <th>A5</th>
        <td>幅 148 × 高さ 210</td>
        <td>幅 154 × 高さ 216</td>
    </tr><tr>
    
    </tr><tr>
        <th>B5</th>
        <td>幅 182 × 高さ 257</td>
        <td>幅 188 × 高さ 263</td>
    </tr><tr>

</tr></tbody></table>

<p class="attention">単位：mm（ミリメートル）、断ち切り時に空白が生じないよう各用紙サイズ（幅のみ2倍＋規定の背幅）の外周にトンボ（トリムマーク）と、上下左右3mmづつ塗り足し部分を追加してください。</p>

<h2>表紙・裏表紙の解像度</h2>

<table>

    <tbody><tr>
        <th>カラーモード</th>
        <th>解像度
    </th></tr><tr>

    </tr><tr>
        <th>CMYKフルカラー</th>
        <td>350 ～ 440 dpi</td>
    </tr><tr>

    </tr><tr>
        <th>グレースケール</th>
        <td>600 dpi</td>
    </tr><tr>

    </tr><tr>
        <th>モノクロ2階調</th>
        <td>600 または 1200 dpi</td>
    </tr><tr>

</tr></tbody></table>

<p>dpiは「pixel / inch」を選択してください。</p>

<p>オンデマンド印刷でカラーモードがRGBの場合、印刷結果の色合いは画面の色合いとは異なりますのでご注意ください（特に明るい緑や水色）PhotoshopやIllustratorご利用の場合は、カラーモードをCMYKに変換してください。</p>

<p>PSDファイルは、レイヤーを統合してください。</p>

<p>AIファイルは、テキストレイヤーを含む全てのレイヤーをアウトライン化してください。</p>

<p>オフセット原稿かつIllustratorご利用の場合は、アミかけ処理を行ってください。グレースケールの場合モアレが生じるおそれがあります。</p>

<p>カラーモードCMYKのオンデマンド原稿でグレースケール表現される場合は、インクKのみでグレーになるよう設定してください。</p>

<p>黒インクはオーバープリント印刷のためK100%でも若干の透けが生じます。不透明にする場合はY1%加算やリッチブラックにてご配色ください。くわしくは「<a href="https://www.taiyoushuppan.co.jp/doujin/howto/illustrator.php" target="_blank">Illustratorでのフルカラー原稿作成 | Illustratorを使う</a>」欄をご覧ください。</p>

<h2>本文の原稿サイズ</h2>

<table>

    <tbody><tr>
        <th>用紙サイズ</th>
        <th>幅 × 高さ　トンボ断ち切り</th>
        <th>幅 × 高さ　塗り足しを含む</th>
    </tr><tr>

    </tr><tr>
        <th>A6（文庫）</th>
        <td>幅 105 × 高さ 148</td>
        <td>幅 111 × 高さ 154</td>
    </tr><tr>

    </tr><tr>
        <th>B6</th>
        <td>幅 128 × 高さ 182</td>
        <td>幅 134 × 高さ 188</td>
    </tr><tr>

    </tr><tr>
        <th>A5</th>
        <td>幅 148 × 高さ 210</td>
        <td>幅 154 × 高さ 216</td>
    </tr><tr>
    
    </tr><tr>
        <th>B5</th>
        <td>幅 182 × 高さ 257</td>
        <td>幅 188 × 高さ 263</td>
    </tr><tr>

</tr></tbody></table>

<p class="attention">単位：mm（ミリメートル）、断ち切り時に空白が生じないよう各用紙サイズ（幅のみ2倍＋規定の背幅）の外周にトンボ（トリムマーク）と、上下左右3mmづつ塗り足し部分を追加してください。</p>

<h2>本文の解像度</h2>

<table>

    <tbody><tr>
        <th>カラーモード</th>
        <th>解像度
    </th></tr><tr>

    </tr><tr>
        <th>グレースケール</th>
        <td>600 dpi</td>
    </tr><tr>

    </tr><tr>
        <th>モノクロ2階調</th>
        <td>600 または 1200 dpi</td>
    </tr><tr>

</tr></tbody></table>

<p>dpiは「pixel / inch」を選択してください。</p>

<p>PSDファイルは、レイヤーを統合してください。</p>

<p>AIファイルは、テキストレイヤーを含む全てのレイヤーをアウトライン化してください。</p>

<p>オフセット原稿かつIllustratorご利用の場合は、アミかけ処理を行ってください。グレースケールの場合モアレが生じるおそれがあります。</p>
↑元データ とりあえず非表示/解像度と関係のないモードの案内も含まれてしまっています-->


<h2 id="upload">原稿アップロード</h2>

<ul>
    <li>原稿アップロードは一般のオンラインストレージサービス（Gigafile便、Filestorageなど）にアップして行います。</li>
    <li>入稿フォームでは、オンラインストレージのダウンロードURLをご入力いただきます。</li>
    <li>オンラインストレージは<a href="https://gigafile.nu/" target="_blank">GigaFile便</a>で、アップロードする際にダウンロード期間を入金期限および入金予定日よりも1～2日ほど余裕をもった日数（最大60日）に設定してご登録ください。</li>
    <li>他のオンラインストレージ（Filestorageなど）にアップロードする場合も、ダウンロード期限に余裕があるかご確認ください。</li>

    <li class="attention">入稿後、URL誤りやダウンロード期限切れの場合は印刷会社から連絡があります。再提出が間に合わない場合、発注キャンセルし手数料を引いた差額の返金対応となる場合もございます。あらかじめご理解ご了承ください。</li>
</ul>


<h3>オンラインストレージとは？</h3>

<ul>
    <li>オンラインストレージは、印刷原稿データのような数百メガバイト～数ギガバイトのデータをアップロードしてダウンロードURLを相手に連絡するだけでファイルを共有することができます。</li>
    <li>従来の印刷入稿にあった、外部メディアへの転送やメディアの郵送、FTPソフト等の面倒な接続設定、入稿締切間際だとアップロードに時間がかかったり拒否される、などの手間や問題がなくスムーズに入稿できます。</li>
</ul>


<h3>快適印刷さんを、より快適に使うコツ</h3>

<ul>
    <li>あらかじめクレジットカードまたはデビットカードを作成しておいて、カード決済でのお支払いをおすすめします。</li>
    <li>オンラインストレージのダウンロード期限は、入金予定日より1～2日余裕ある日付設定をおすすめします。</li>
    <li>はじめてご利用される場合は、入金期限前日までのお支払いをおすすめします。</li>
</ul>


<p>
なお、お分かりにならないことや、お困りのことがありましたら、こちら快適印刷さんにご連絡ください。
</p>

<p class="buttons">
<a href="https://xsvx2010092.xsrv.jp/contact/">お問い合せフォーム</a>
</p>



				</article>
			</section>

<!--		</section> -->

		
	</div><!-- wrapper -->

	<?= $view['footer'] ?>

</body>
</html>