<?php

const PAGE_NAME = '入稿一覧';

//include_once('../../../config_all. php');
include_once('../../_config_site.php');
include_once('../../php/lib.php');
include_once('_config.php');

foreach($_GET  as $key=>$val) $param[htmlspecialchars($key, ENT_QUOTES)] = $val;
foreach($_POST as $key=>$val) $param[htmlspecialchars($key, ENT_QUOTES)] = $val;

$data_dir = SITE_PATH.'/order/data/';
$file_list = getFileList($data_dir);

$order_list = [];
if (count($file_list)) {

	foreach($file_list as $key=>$file) {

		$data = json_decode(file_get_contents($file), true);

		if (!empty($data['id'])) {

			$id = $data['id'];

			$order_list[$id] = [
				'id'				=> $id
				,'price'			=> $data['price']
				,'real_name'		=> $data['real_name']
				,'real_name_kana'	=> $data['real_name_kana']
				,'print_data_url'	=> $data['print_data_url']
				,'status'			=> '未実装'
			];
		}
	}

	krsort($order_list);
}

$status_list = json_decode(file_get_contents($data_dir.'order_status_list.json'), true);


$checked	= ' checked="checked"';
$selected	= ' selected="selected"';
$disabled	= ' disabled="disabled"';
$hidden		= ' style="display:none"';

?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
	<meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
	<title><?= PAGE_NAME ?> | <?= SITE_NAME ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="stylesheet" type="text/css" href="<?= SITE_ROOT ?>/css/style.css">
	<link rel="stylesheet" type="text/css" href="<?= SITE_ROOT ?>/css/form.css">

	<script src="<?= SITE_ROOT ?>/js/jquery.js"></script>
	<script src="<?= SITE_ROOT ?>/js/script.js"></script>
	<script src="<?= SITE_ROOT ?>/js/pagetop.js"></script>

	<script src="<?= SITE_ROOT ?>/js/warning_freemail.js"></script>
</head>

<body>

	<?php // include_once(SITE_PATH.'/_header.php') ?>

	<div id="wrapper">

		<section class="content">




<h2 class="heading"><?= PAGE_NAME ?></h2>

<style>

dt span,
dt small {
	font-weight:normal;
}

dt small {
	font-size:0.875em;
}

.ec-input form {
	display:inline-block;
	margin-left:0.5em;
}

.ec-input a,
.ec-input span {
	display:inline-block;
	margin-right:1em;
}

<?php for($i=1; $i<20; $i++): ?>
<?= '.digit-'.$i ?> {width:<?= $i+1 ?>em !important;}
<?php endfor; ?>

</style>



<div id="form_area" class="text">



<?php if(!empty($param['result'])): ?>
<p><em style="color:#090"><?= $param['result'] ?></em></p>
<?php endif; ?>



<?php if(count($param['error']) > 0): ?>
<ul id="error_list" class="attention">
<?php foreach($param['error'] as $message) echo '<li>'.$message.'</li>'; ?>
</ul>
<?php endif; ?>



<h3>入稿対応メニュー
　<a href="list.php?mode=not_payment">未入金</a>
　<a href="list.php?mode=payment">入金済</a>
</h3><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<?php if (empty($order_list)): ?>

<p>※入稿は現在ありません。</p>


<?php else: ?>

	<div class="ec-borderedDefs">

		<?php foreach($order_list as $id=>$order):

			if (empty($id) || empty($order)) continue;

			$order_status = !empty($status_list[$id])
				? $status_list[$id]['status_code'] : '';

			if ($param['mode'] == 'not_payment' && is_payed($order_status)) continue;
			
			if ($param['mode'] == 'payment' && !is_payed($order_status)) continue;

			?>

			<dl>
			<dt><h4><?= $id ?></h4>
				<span><?= $order['real_name'] ?>
				<small>（<?= $order['real_name_kana'] ?>）</small>
				</span>
			</dt>
			<dd>
				<div class="ec-input">

				<a class="button" href="<?= $order['print_data_url'] ?>" target="_blank">原稿DL</a>
				
				<?php if (is_payed($order_status)): ?>
					
					<a class="button" href="sendmail_accept.php?id=<?= $id ?>">受付メール送信</a>
					<a class="button reload" href="export_sheet.php?id=<?= $id ?>" target="_blank">発注書出力</a>

					<span class="status">状態：<?= putStatusText($order_status) ?></span>

				<?php else: ?>

					<span class="status">状態：<?= putStatusText($order_status) ?></span>

					<form method="post" action="mod_order.php">
						<input type="hidden" name="id" value="<?= $id ?>">
						<input type="hidden" name="mode" value="payment_on">
						<button>￥<?= number_format($order['price']) ?> 入金確認</button>
					</form>

				<?php endif; ?>


				</div>
			</dd>
			</dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

		<?php endforeach; ?>
		
	</div><!-- ec-borderedDefs -->

<?php endif; ?>



</div><!-- form_area -->






</section>



</div><!-- wrapper -->



<script>

var global = global || {};

$('a.reload').on('click',function(){

	var time = setTimeout(delayTask, 1000);

	function delayTask() {
		location.href='list.php?mode=payment';
	}
});

</script>



<?php // include_once(SITE_PATH.'/_footer.php') ?>
<!--
<script src="//yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
-->

</body>
</html>