<?php

const PAGE_NAME = '入稿フォーム';

?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
	<meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">

	<title>登録完了 | <?= PAGE_NAME ?> | <?= $site['name'] ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<link rel="stylesheet" type="text/css" href="/css/form.css">

	<script src="/js/jquery.js"></script>
	<script src="/js/script.js"></script>
	<script src="/js/pagetop.js"></script>

	<script src="/js/backbutton_blocker.js"></script>
</head>

<body>

    <?= $view['header'] ?>

	<div id="wrapper">

		<section class="content">



<h2 class="heading">正常送信完了 | <?= PAGE_NAME ?></h2>

            <article class="post">


<style>

#buttons_lang, #language {
	text-align:center;
	vertical-align:middle;
}

#buttons_lang button {
	display:inline-block;
	line-height:1.5;
}

label {
	cursor:pointer;
}

#error_list {
	color:#c00;
}

ul.list_numeric li {
	list-style:none;
	list-style-position:inside;
}

<?php for($i=1; $i<20; $i++): ?>
<?= '.digit-'.$i ?> {width:<?= $i+1 ?>em !important;}
<?php endfor; ?>

</style>



<div id="form_area" class="text">

	<p><b>入稿内容を登録しました。</b></p>

    <?php if(isset($order_list) && count($order_list)): ?>

<table>

<tr>
<th>no.</th>
<th>本のタイトル</th>
<th>入金期限</th>
<th>発注状況</th>
</tr>

<?php

$DT_now   = new \Datetime();

foreach($order_list as $order):

	$b_extra = ($order['status'] == 12 || !empty($order['b_extra_order']));

    $DT_limit = new \Datetime($order['payment_limit']); ?>

<tr>
<td class="number"><?= $order['id'] ?></th>
<td><!-- <a href="/order/detail?id=<?= $order['id'] ?>"> --><?= $order['print_title'] ?><!-- </a> --></td>
<td><?= $DT_limit->format('Y/n/j H時まで'); ?></td>
<td><?= $order['status_name'] ?? '' ?>　<?php

if ($order['status_name'] == '未入金'
&&  $DT_now < $DT_limit
): ?>

<a href="/payment/form">お支払い手続きする＞＞</a>

<?php else: ?>
<span class="attention">金額調整後に連絡します。お待ちください。</span>

<?php endif; ?></td>
</tr>

<?php endforeach; ?>

</table>

<?php endif; // $order_list ?>


    <p>期限までにお支払いが確認できない場合、ご登録いただいた入稿内容は無効となります。</p>
<!--
	<p>入力いただいたメールアドレス宛に、お支払いのご案内メールが自動送信されておりますので、必ずご確認ください。<br class="pc_mid_only">
	こちらが届かない場合、お手数ですが<a href="/contact">お問合せフォームからお問い合わせください。</a>
-->

	</div><!-- form_area -->


<script type="text/javascript">

var global = global || {};
/*
var array_target = [];

array_target[0] = 'input[name="mail_address"]';
//array_target[1] = 'input[name="product_mail_address"]';

warning_freemail(array_target); // フリーメールドメインが入力されたら警告表示する
*/
</script>


</article>

</section>



</div><!-- wrapper -->

<?= $view['footer'] ?>


<!--
<script src="//yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
-->
</body>
</html>