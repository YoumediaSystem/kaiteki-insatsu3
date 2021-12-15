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

<!-- クリップスタジオ -->
<a name="clip"></a>
<h2>CLIP STUDIO</h2>
<ul>
	<li>必ず「psd(Photoshopドキュメント)」または「tif(TIFF)」形式でご入稿ください。</li>
	<li>.lipファイルでのご入稿は対応しておりません。 </li>
	<li>書き出しを行う前に必ず、最新バージョンにアップデートしてください。 </li>
	<li>Ver.1.8.4　2018年11月現在</li>
</ul>

<h1>書き出し</h1>
<p>
【1】メニューバーのファイル</br>
　→「画像を統合して書き出し」を選択</br>
　→「.tif(TIFF)」または「.psd(Photoshopドキュメント)」をクリック</br>
<img src="/img/data_format/clipstudio1.gif">
</p>
<p>
【2】ファイル名を決めるウィンドウが表示される</br>
　→「ファイル名」を付ける</br>
　→「保存」ボタンをクリック</br>
<img src="/img/data_format/clipstudio2.gif">
</p>
<p>
【3】「psd書き出し設定」のウィンドウが表示される</br>
　下記の内容に設定する</br>
　→プレビュー：「出力時にレンダリング～」にチェック</br>
　→Photoshopファイル設定：「[背景]として出力する」にチェック</br>
　→出力イメージ：「トンボ」「テキスト」「ノンブル」にチェック</br>
　→出力範囲：「ページ全体」を選択</br>
　→表現色：「モノクロ２階調」または</br>
　　　　　　　　 「グレースケール」を選択</br>
<strong>【ご注意】</strong></br>
下描き、基本枠、セーフラインにはチェックを入れないでください。</br>
「モノクロ２階調」を選択するとK100%以外の塗りや線は網点化され</br>
トーンに変換されます。保存前にプレビューで必ず確認してください。</br>
<img src="/img/data_format/clipstudio3.gif">
</p>
<p>
【4】「色の詳細設定」を選択</br>
 　下記の内容に設定する</br>
　→トンボ・基本枠：「表示色で出力」を選択</br>
　→トーン線数：「レイヤー設定に従う」にチェック</br>
　→「レイヤーに付与されたトーン効果を有効にする」にチェック</br>
<img src="/img/data_format/clipstudio4.gif">
<p>
<p>
【5】書き出しプレビューを確認後「OK」を選択</br>
　※グレーなどの塗りを使用している場合には十分確認してください。</br>
<img src="/img/data_format/clipstudio5.png">
</p>
<p>
●一括書き出し</br>
メニューバーのファイル</br>
　→「複数ページ書き出し」を選択し、一括書き出しをクリック</br>
　→書き出し先のフォルダー：タイトルなどを入力</br>
　　※ここで入力した名前が全ページのファイル名の頭につきます。</br>
</br>
　　※画像を統合して書き出すにチェックを入れてください。</br>
　　※見開きページを分けて書き出すにチェックを入れてください。</br>
psd書き出し設定</br>
　→ファイル形式・出力イメージ・表現色などは【3】をご覧ください。</br>
<img src="/img/data_format/clipstudio5.png"></br>
<strong>【ご注意】</strong></br>
必ず「見開きページを分けて書き出す」にチェックを入れてください。
</p>

<p>&nbsp;</p>

<!-- Photoshop -->
<a name="Photoshop"></a>
<h2>Adobe Photoshop</h2>
画面や操作方法の説明は、Photoshop5.5 を基にしています。</br>
Adobe Photoshop®は、アメリカ合衆国もしくは他の国におけるAdobe Systems Incorporatedの登録商標または商標です。</br>

<h1>トンボを描く</h1>
<p>
【1】ガイドを使ってトンボを入れる位置を決める。</br>
メニューバーの 「ビュー」を選択</br>
　→ 定規 で定規を表示させ、定規の上から、下（左）に向けてドラッグ。</br>
　(ガイドは印刷に出ません）</br>
<img src="/img/data_format/ps_menu.gif"></br>
原点</br>
　→下図のように測り始めたい箇所に原点をドラッグする。</br>
<img src="/img/data_format/ps_guides.gif">
</p>
<p>
【2】ガイドに沿ってラインツールでトンボを入れる</br>
ツールボックスでラインツールを選び、shiftキー＋ドラッグで水平、垂直のラインを引く。</br>
<img src="/img/data_format/ps_toolbox.gif">
</p>
<p>
【3】ラインの太さ、タイプを設定</br>
ウインドウ</br>
　→オプションを表示</br>
　→（ツールボックス）ラインツールをクリックする</br>
　→ラインツールオプションが表示</br>
　→アンチエイリアスのチェックを外す。</br>
<img src="/img/data_format/ps_linetooloption.gif"></br>
※アンチエイリアスのチェックを外さないとボヤけたラインになってしまいます。</br>
</p>
<p>
<b>【Photoshop6.0ご利用のアンチエイリアスの外し方</b></br>
レイヤーパレットで通常のレイヤーを選択した状態でラインツールを選択</br>
　→オプションバーの「塗りつぶした領域を作成」ボタンをクリック</br>
　→　アンチエイリアスのチェックを外す。
</p>
<p>
【4】ライン色を設定</br>
カラーパレット CMYK、RGBとも下図を参考にライン色設定する。</br>
<table>
	<tr>
	<th>CMYK：各100%</th>
	<th>RGB：各0%</th>
	</tr>
	<tr>
	<td><img src="/img/data_format/ps_cmyk.gif"></td>
	<td><img src="/img/data_format/ps_rgb.gif"></td>
	</tr>
</table></br>
</p>
<h1>モノクロデータのモアレ確認</h1>
<p>
【1】モードを変更する</br>
メニューバーの「イメージ」を選択</br>
　→「モード」を選択</br>
　→「モノクロ2階調」を選択</br>
　→解像度1200dpi 線数120line/inch 角度45度 アミ点形状「円」で変換
</p>
<p>
【2】モアレが発生していないか確認する</br>
モアレが発生してしまう場合、各印刷会社にご相談ください。
</p>
<h1>RGBデータの色域外確認</h1>
<p>
■全体を確認する方法</br>
メニューバー「表示」を選択</br>
　→「色域外警告」を選択</br>
<b>※色域外の色が使用されている箇所は、グレーで表示されます。</b>
</p>
<p>
■情報ウィンドウで確認する方法</br>
メニューバー「ウィンドウ」を選択</br>
　→「情報」を選択</br>
　→情報ウィンドウのメニューから「情報パネルオプション」を選択</br>
　→「第2色情報」の項目で「CMYKカラー」を選択</br>
　→イラスト上にカーソルを移動する</br>
<b>現在のマウスポインタの位置に色域外の色が使用されている場合、情報ウィンドウに「！」が表示されます。</b>
</p>

<p>&nbsp;</p>

<!-- illustrator -->
<a name="illustrator"></a>
<h2>Adobe Illustrator</h2>
画面や操作方法の説明は、Illustrator8.0 を基にしています。</br>
Adobe Illustrator®は、アメリカ合衆国もしくは他の国におけるAdobe Systems Incorporatedの登録商標または商標です。</br>
<h1>ご入稿時の注意</h1>
<lo>
	<li><b>入稿時、<strong>レイヤーは結合</strong>してください。また、トラブルのもとになりますので<strong>非表示レイヤーは必ず、すべて削除</strong>してください。</b></li>
	<li>aiまたはIllustrator EPS形式で保存してください。</li>
</lo>
<h1>トンボを描く</h1>
<p>
原稿の仕上がり位置やセンターがわかるようにトンボは必ず入れてください。</br>
</p>
<p>
■トリムマークフィルタで描く</br>
【1】新規でレイアウトを開く
</p>
<p>
【2】書類設定のサイズを作りたいサイズより大きいサイズを選ぶ（入力）</br>
（ファイル　→　書類設定）
</p>
<p>
【3】ツールボックスの長方形ツールで仕上がりサイズ（横幅は仕上がりサイズ＋背幅）を入力し四角を描く</br>
（ツールボックス　長方形ツール　→　画面上で1クリック　→　長方形オプションに数値を入力）</br>
<img src="/img/data_format/il_tonbo_shikaku.gif"></br>
<strong>【ご注意】</strong></br>
この時オブジェクトの線の色をoffにしてください。</br>
色が付いたままトリムマークでトンボを書かれますと仕上がり範囲内に線が引かれ、出来上がり本に線が出てしまいます。</br>
描いた長方形を用紙の内側に移動させます。
</p>
<p>
【4】長方形を選択し、フィルタ　→　クリエイト　→　トリムマーク を選ぶと長方形に合わせてがトンボが作成される</br>
<img src="/img/data_format/il_tonbo_trim.gif"></br>
このトンボはペンで描いたのと同じ状態になりますので修正・変更等が出来ます。</br>
この場合は、違うソフト（Photoshop）で開いてもトンボが表示されます。
</p>
<p>
■ペンツールで描く</br>
ペンツールで描いて頂いても大丈夫です。</br>
カラーモードをCMYKにし、各100％の色に設定してください。</br>
<img src="/img/data_format/il_tonbo_4.gif"></br>
他に、オブジェクト → トンボ → 作成 で描く方法もありますが、この方法で描いたトンボは修正出来ず、イラストレーターのみの表示になり、Photoshop等でファイルを開いた場合は表示されません。</br>
トリムマークでトンボを描く方法をお勧めします。
</p>
<h1>別の画像を配置する</h1>
<p>
Photoshopで作成した画像を配置する場合、画像は埋め込まずリンクさせてください。</br>
画像が埋め込まれている場合、画像の色補正は出来ませんので注意が必要です。</br>
配置画像は解像度350・400pixel/inchで、 PSD形式かTIFF形式かEPS形式で保存してください。</br>
画像のファイル名を後から変更すると、リンクが切れてしまいますので注意が必要です。</br>
EPS画像をリンクさせた場合、家庭用プリンタでは綺麗に出力されませんが、印刷には影響ありませんのでご安心ください。
</p>
<p>
【1】ファイルを配置する</br>
<img src="/img/data_format/il_haichi.gif">
</p>
<p>
【2】配置する画像を選んで配置する</br>
「リンク」にチェックを入れてください。</br>
<img src="/img/data_format/il_place.png">
</p>
<p>
【3】リンクパレットで見るとリンクされている事を確認する</br>
8.0より前のバージョンにはリンクパレットはありません。</br>
<img src="/img/data_format/il_link.png">
</p>
<p>
【4】リンク画像は入稿ディスクにillustratorの画像と一緒に入れる</br>
<img src="/img/data_format/il_window.png">
</p>
<h1>配置画像を変更した場合</h1>
<lo>
	<li>Illustratorで「配置画像を変更」した場合は配置画像の再保存だけでなく、必ずIllustrator上でも再保存してください。</li>
	<li>Illustratorで配置画像があるときは保存時に「配置した画像を含む」にチェックを入れてください。</li>
	<li>Illustratorで配置画像があるときはリンクではなく「埋め込み」をおすすめいたします。（※モノクロデータのみ）</li>
</lo>
<h1>CMYKカラーを使う</h1>
<p>
文字やオブジェクトに色を付ける時はCMYKでお願いします。<br>
スポットカラー、カスタムカラー、RGBを使用した時はCMYKに変換してください。
</p>
<p>
<b>【1】すべて選択　→　フィルター　→　カラー　→　CMYKに変換</b></br>
<img src="/img/data_format/il_filter_color_cmyk.gif"></br>
右下に斜め線が付いている色はスポットカラーです。</br>
CMYKに変換してください。</br>
<img src="/img/data_format/il_spotcolor_cmyk.gif">
</p>
<p>
【2】ファイル　→　書類情報のすべての情報を確認できます。</br>
<img src="/img/data_format/il_file_informations.gif"><br>
<img src="/img/data_format/il_documentsinformations.gif">
</p>
<h1>Illustratorでのフルカラー原稿作成</h1>
<li>
<lo>イラストレータデータはケヌキではなくオーバープリントで印刷するため、K100%のみだった場合、ブラックインキにも若干の透明度があるため、黒は薄めに表現されたり、下にある図形や文字の色の影響を受けてしまいます。</lo></br>
C・M・Yのどれかを1％にするだけでオーバープリントにはなりません。もっとも色に影響を与えないのはY1％です。</br>
<table>
	<tr>
		<th>K100％+Y1%</th>
		<th>K100%のみ</th>
	</tr>
	<tr>
		<td><img src="/img/data_format/il_over_print2.png"></td>
		<td><img src="/img/data_format/il_over_print1.png"></td>
	</tr>
</table>
<p>
<li>イラストレータデータの場合、イラストレータ上で作成されたデータの色補正は一切行いません。</li>
画像配置で配置されたビットマップ画像データ（Photoshopなどのデータ）は色補正が可能ですが、画像を「埋め込み」し、元の画像データを入れていない場合は色補正することはできません。</br>
元の画像データが入っていれば、画像部分の色補正は可能です。
</lo>
</p>

<p>&nbsp;</p>

<!-- Word -->
<a name="Word"></a>
<h2>Microsoft Word</h2>
<h1>Wordでご入稿の場合の注意点</h1>
<lo>
	<li>必ず「PDF形式」でご入稿ください。</li>
		　.docファイルには対応しておりません。
	<li>カラーオブジェクトを含まない本文データのみ受付可能です。</li>
	<li>すべてのフォントの埋め込みをしてください。</li>
	<li>サイズは原寸＋塗り足し(上下左右3ミリずつ：B5本･･･ヨコ188×タテ263ミリ、A5本･･･ヨコ154×タテ216ミリ)</li>
		　※まわりが白のときは塗り足しを付けない原寸サイズで大丈夫です。
	<li>その他のフリーソフトで作成されたPDFは不具合が生じる可能性があります。</li>
		　事前に印刷所へ相談頂く事をお勧めいたします。
</lo>
<h1>Word2010以降でのPDF形式保存</h1>
<p>
【1】PDFで保存するword文書を開く</br>
　→「ファイル」タブをクリック</br>
　→「名前を付けて保存」
</p>
<p>
【2】「名前を付けて保存」のウィンドウが表示される</br>
　→ファイルの種類から「PDF」を選択</br>
　→「オプション」ボタンをクリック
</p>
<p>
【3】「PDFのオプション」ISO 19005-1に準拠（PDF/A）をチェック</br>
　→「OK」ボタンをクリック
</p>
<p>
【4】「保存先」を指定し「ファイル名」を付けて</br>
　→「保存」ボタンをクリック</br>
　→PDFファイルが作成される
</p>
<h1>Word2007でのPDF変換</h1>
<p>
Word2007をご使用で「Microsoft PDF/XPS アドイン」が未インストールの場合は先にインストールしてください。
</p>
<p>
【1】Microsoft Download Centerで「2007 Microsoft Office プログラム用 Microsoft PDF/XPS 保存アドイン」をダウンロード</br>
→　手順に従ってインストールする。</br>
<img src="/img/data_format/word-1.gif">
</p>
<p>
【2】PDFで保存するWord文書を開く</br>
　→「オフィス」ボタンをクリック</br>
　→「名前を付けて保存」</br>
　→「PDF または XPS(P)」を選択</br>
<img src="/img/data_format/word-2.gif">
</p>
<p>
【3】「PDF または XPS(P)形式で発行」のウィンドウが表示される</br>
　→ファイルの種類から「PDF」を選択</br>
　→「オプション」ボタンをクリック</br>
<img src="/img/data_format/word-3.gif">
</p>
<p>
【4】「PDFのオプション」ISO 19005-1に準拠（PDF/A）をチェック</br>
　→「OK」ボタンをクリック</br>
<img src="/img/data_format/word-4.gif">
</p>
<p>
【5】「保存先」を指定し「ファイル名」 を付けて</br>
　→「発行」ボタン をクリック</br>
　→PDFファイル が作成される</br>
<img src="/img/data_format/word-5.gif">
</p>
<h1>文章(PDF)データと画像データが混在になる場合</h1>
<lo>
	<li>
	PDFページは１ファイル内に含まれる始まりと終わりのノンブルをファイル番号にして保存してください。</br>
		　例）ノンブル：３ページ～15ページ　→　ファイル名：003-015.pdf</br>
		　例）ノンブル：17ページ～22ページ　→　ファイル名：017-022.pdf
	</li>
	</br>
	<li>
	画像（PSD、TIFF、EPSなど）ファイルは１ページ＝１ファイルで保存し、ノンブルとファイル名を統一してください。</br>
		　例）ノンブル：16ページ　→　ファイル名：016.psd
	</li>
</lo>
<p>
<table>
	<tr>
		<th colspan="4">例）本文が3ページ～22ページの場合</th>
	</tr>
	<tr>
		<th>原稿データ</th>
		<td><img src="/img/data_format/pdf.gif"></td>
		<td><img src="/img/data_format/psd.gif"></td>
		<td><img src="/img/data_format/pdf.gif"></td>
	</tr>
	<tr>
		<th>ファイル名</th>
		<td>003-015.pdf</td>
		<td>016.psd</td>
		<td>017-022.pdf</td>
	</tr>

</table>
</p>
<h1>MacOSXの機能 Quartzで出力したPDFデータについて</h1>
<u>MacOSXの機能「Quartz」でPDFを出力されると、お客様の元でご確認いただいた状態では問題なくても、別環境で開いた場合、以下の不具合が確認されております。</u></br>
<lo>
	<li>あるはずのオブジェクトや文字が消える</li>
	<li>不要なオブジェクトが出る</li>
</lo>
<p>
不具合回避のため、「Quartz」を使用せず別の方式で出力されたPDFでご入稿ください。</br>
「Quartz」で出力されたPDFをご入稿いただいた場合、お客様の意図しない仕上がりになる可能性がございますので予めご了承ください。
</p>

<p>&nbsp;</p>

<!-- indesign -->
<a name="indesign"></a>
<h2>InDesign</h2>
<h1>InDesignでご入稿の場合の注意点</h1>
<lo>
	<li>必ず「PDF形式」でご入稿下さい。</li>
		　.inddファイルでのご入稿は対応しておりません。
	<li>フォントの埋め込み、サイズは原寸＋塗り足し(上下左右3ミリずつ：B5本･･･ヨコ188×タテ263ミリ、A5本･･･ヨコ154×タテ216ミリ)、カラーオブジェクトを含まない本文データのみ受付可能です。</li>
	<li>その他のフリーソフトで作成されたPDFは不具合が生じる可能性があります。</li>
		　事前に印刷所へ相談頂く事をお勧めいたします。
</lo>
<h1>InDesign：PDF書き出し</h1>
<p>
【1】ファイル</br>
　→PDF書き出しプリセット</br>
　→『PDFX1a 2001 JPN（日本）』をクリック</br>
<img src="/img/data_format/indesign1.gif">
</p>
<p>
【2】「書き出し」のウィンドウが表示される</br>
　→「ファイル名」を付ける</br>
　→「保存」ボタンをクリック
</p>
<p>
【3】「Adobe PDFを書き出し」のウィンドウが表示される</br>
　「PDF書き出しプリセット」で『PDFX1a 2001 JPN（日本）』が選択されていることを確認</br>
<img src="/img/data_format/indesign2.gif">
</p>
<p>
【4】左の「圧縮」を選択</br>
　→ カラー・グレー・モノクロの画像：「ダウンサンプルしない」</br>
　圧縮：「ZIP」を選択</br>
<img src="/img/data_format/indesign3.gif">
</p>
<p>
【5】左の「トンボと裁ち落とし」を選択</br>
　→「ドキュメントの裁ち落とし設定を使用」にチェックを入れる</br>
　（下記画像はインデザインのドキュメントの設定で裁ち落としを3ｍｍに設定している場合のものです）</br>
<img src="/img/data_format/indesign4.gif">
</p>
<p>
【6】書き出しボタンをクリック</br>
　→PDFファイルが作成される
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