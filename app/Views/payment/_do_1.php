<?php

//$debug = false;
$debug = true; // debug mode;

const PAGE_NAME = '決済代行へ接続中';

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

<p style="text-align:center">※お待ちください※</p>


    <?php if (empty($error) || !count($error)): ?>

    <form id="send_params" method="post" action="<?= $payment_link_url ?? '/' ?>">

        <input type="hidden" name="trading_id" value="<?= $trading_id ?? 0 ?>">
        <input type="hidden" name="id" value="<?= $id ?? 0 ?>">

        <input type="hidden" name="seq_merchant_id" value="<?= $seq_merchant_id ?? 0 ?>">
<!--        <input type="hidden" name="payment_type" value="<?= $payment_type ?? '' ?>">-->
        <input type="hidden" name="fix_params" value="<?= $fix_params ?? '' ?>">

        <input type="hidden" name="customer_id" value="<?= $customer_id ?? '' ?>">

        <input type="hidden" name="payment_class" value="<?= $payment_class ?? '' ?>">
        <input type="hidden" name="use_card_conf_number" value="<?= $use_card_conf_number ?? '' ?>">
        <input type="hidden" name="stock_card_mode" value="<?= $stock_card_mode ?? '' ?>">
        <input type="hidden" name="sales_flg" value="<?= $sales_flg ?>">

        <input type="hidden" name="customer_family_name" value="<?= $customer_family_name ?? '' ?>">
        <input type="hidden" name="customer_name" value="<?= $customer_name ?? '' ?>">
        <input type="hidden" name="customer_family_name_kana" value="<?= $customer_family_name_kana ?? '' ?>">
        <input type="hidden" name="customer_name_kana" value="<?= $customer_name_kana ?>">
        <input type="hidden" name="customer_tel" value="<?= $customer_tel ?>">

        <input type="hidden" name="payment_detail" value="<?= $payment_detail ?? '' ?>" />
        <input type="hidden" name="payment_detail_kana" value="<?= $payment_detail_kana ?? '' ?>" />
        <input type="hidden" name="merchant_name" value="<?= $merchant_name ?? '' ?>">
        <input type="hidden" name="merchant_name_kana" value="<?= $merchant_name_kana ?? '' ?>">

<?php if(!empty($payment_term_day) && empty($payment_term_min)): ?>
        <input type="hidden" name="payment_term_day" value="<?= $payment_term_day ?? '' ?>">

<?php else: /*if(empty($payment_term_day) && !empty($payment_term_min)):*/ ?>
        <input type="hidden" name="payment_term_min" value="<?= $payment_term_min ?? 0 ?>">

<?php endif; ?>

<pre id="hash" style="display:none;">
<?= $org_str ?? '' ?>
</pre>
        <input type="hidden" name="hc" value="<?= $hash ?? '' ?>">

        <input type="hidden" name="return_url" value="<?= $return_url ?? '' ?>">
        <input type="hidden" name="stop_return_url" value="<?= $stop_return_url ?? '' ?>">
        <input type="hidden" name="finish_disable" value="<?= $finish_disable ?? '' ?>">

<?php if($debug): ?>
<button>　送信する　</button>
<?php endif; ?>

    </form>

    <?php else: ?>

        <form id="send_params" method="post" action="/payment/form">

            <?php foreach($error as $val): ?>
            <input type="hidden" name="error[]" value="<?= $val ?>">
            <?php endforeach; ?>

        </form>

    <?php endif; ?>



    <form id="send_params2" method="post" action="/payment/form">

    <input type="hidden" name="url" value="">
    <input type="hidden" name="error_code" value="">
    <input type="hidden" name="error_detail" value="">

    </form>

    <?php if($debug): ?>
    <pre id="rawdata" style="height:9em; overflow-y:hidden;">
    <?= $result ?? '' ?>
    </pre>
    <?php endif; ?>



    <script type="text/javascript">

var b,i,n,t,a,aa,len;

var global = global || {};

var send_param = [];


$('#send_params input').each(function(){

    var key = $(this).attr('name');
    var val = $(this).val();
    if (typeof val == 'undefined') val = '';

    send_param[key] = val + '';
});


// ペイジェント照会送信する
<?php if(!$debug): ?>
//    $('#send_params').submit();
<?php endif; ?>


$.ajax({
    url : '/api/send_payment_data',
    data: send_param,
    type: 'POST',
    success:function(text) {

        $('#rawdata').text(text);

        if (text.length) {
            text = text.replace(/<!--[\s\S]*?-->/g, ''); // コメント領域削除
            text = text.split('"').join('');
            text = text.split('\n').join('');
            a = text.split("\r");

            var len = a.length;

            for (i=0; i<len; i++) {

                if (a[i].indexOf('=') != -1 && a[i].indexOf('<!--') == -1) {
                    aa = a[i].split('=');
                    global[aa[0]] = aa[1] + '';
                }
            }

            if (global.result == '0') {
                $('#send_params2 input[name=url]').val(global.url);
                $('#send_params2').attr('action', '/payment/do_standby');

            } else {
                $('#send_params2 input[name=error_code]').val(global.response_code);
                $('#send_params2 input[name=error_detail]').val(global.response_detail);
                $('#send_params2').attr('action', '/payment/error');
            }

<?php if(!$debug): ?>
            $('#send_params2').submit();
<?php endif; ?>

        }
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {

        console.log('エラーが発生しました。'
            + XMLHttpRequest.status + ' / '
            + textStatus + ' / '
            + errorThrown // errorThrown.message
        );
    }
});

</script>


</body>
</html>