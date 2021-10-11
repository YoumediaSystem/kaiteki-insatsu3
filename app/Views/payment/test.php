<?php

$debug = true;//false;

$checked = ' checked="checked"';

const PAGE_NAME = '決済代行接続テスト';

$v_payment_type = !empty($payment_term_day) ? "01,02,03,05" : "02,05";

$v_fix_params = "customer_family_name,customer_name,customer_family_name_kana,customer_name_kana,customer_tel";

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

<p style="text-align:center">※決済接続テスト※</p>


    <form id="send_params" method="post" action="">
    
        <input type="hidden" name="mode" value="mod_hash">
        <input type="hidden" name="payment_link_url" value="<?= $payment_link_url ?? '' ?>">

        <p><input type="hidden" name="trading_id" value="<?= $trading_id ?? 0 ?>">
        trading_id (id) : <?= $trading_id ?? 0 ?></p>

        <p><input type="hidden" name="id" value="<?= $id ?? 0 ?>">
        id (amount) : <?= $id ?? 0 ?></p>

        <p><input type="hidden" name="seq_merchant_id" value="<?= $seq_merchant_id ?? 0 ?>">
        seq_merchant_id : <?= $seq_merchant_id ?? 0 ?></p>


        <?php $prop = !empty($payment_type) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="payment_type" value="<?= $v_payment_type ?? '' ?>"<?= $prop ?>>
        payment_type : <?= $v_payment_type ?? '' ?></label></p>

        <?php $prop = !empty($fix_params) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="fix_params" value="<?= $v_fix_params ?? '' ?>"<?= $prop ?>>
        fix_params : <?= $v_fix_params ?? '' ?></label></p>

        <?php $prop = !empty($customer_id) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="customer_id" value="<?= $customer_id ?? '' ?>"<?= $prop ?>>
        customer_id : <?= $customer_id ?? '' ?></label></p>

        <?php $prop = isset($payment_class) && !is_null($payment_class) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="payment_class" value="<?= $payment_class ?? '' ?>"<?= $prop ?>>
        payment_class : <?= $payment_class ?? '' ?></label></p>

        <?php $prop = isset($use_card_conf_number) && !is_null($use_card_conf_number) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="use_card_conf_number" value="<?= $use_card_conf_number ?? '' ?>"<?= $prop ?>>
        use_card_conf_number : <?= $use_card_conf_number ?? '' ?></label></p>

        <?php $prop = isset($stock_card_mode) && !is_null($stock_card_mode) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="stock_card_mode" value="<?= $stock_card_mode ?? '' ?>"<?= $prop ?>>
        stock_card_mode : <?= $stock_card_mode ?? '' ?></label></p>

        <?php $prop = isset($sales_flg) && !is_null($sales_flg) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="sales_flg" value="<?= $sales_flg ?? '' ?>"<?= $prop ?>>
        sales_flg : <?= $sales_flg ?? '' ?></label></p>

        <?php $prop = !empty($customer_family_name) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="customer_family_name" value="<?= $customer_family_name ?? '' ?>"<?= $prop ?>>
        customer_family_name : <?= $customer_family_name ?? '' ?></label></p>
        
        <?php $prop = !empty($customer_name) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="customer_name" value="<?= $customer_name ?? '' ?>"<?= $prop ?>>
        customer_name : <?= $customer_name ?? '' ?></label></p>
        
        <?php $prop = !empty($customer_family_name_kana) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="customer_family_name_kana" value="<?= $customer_family_name_kana ?? '' ?>"<?= $prop ?>>
        customer_family_name_kana : <?= $customer_family_name_kana ?? '' ?></label></p>
        
        <?php $prop = !empty($customer_name_kana) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="customer_name_kana" value="<?= $customer_name_kana ?? '' ?>"<?= $prop ?>>
        customer_name_kana : <?= $customer_name_kana ?? '' ?></label></p>

        <?php $prop = !empty($customer_tel) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="customer_tel" value="<?= $customer_tel ?? '' ?>"<?= $prop ?>>
        customer_tel : <?= $customer_tel ?? '' ?></label></p>

        <?php $prop = !empty($payment_detail) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="payment_detail" value="<?= $payment_detail ?? '' ?>"<?= $prop ?>>
        payment_detail : <?= $payment_detail ?? '' ?></label></p>

        <?php $prop = !empty($payment_detail_kana) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="payment_detail_kana" value="<?= $payment_detail_kana ?? '' ?>"<?= $prop ?>>
        payment_detail_kana : <?= $payment_detail_kana ?? '' ?></label></p>

        <?php $prop = !empty($merchant_name) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="merchant_name" value="<?= $merchant_name ?? '' ?>"<?= $prop ?>>
        merchant_name : <?= $merchant_name ?? '' ?></label></p>

        <?php $prop = !empty($merchant_name_kana) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="merchant_name_kana" value="<?= $merchant_name_kana ?? '' ?>"<?= $prop ?>>
        merchant_name_kana : <?= $merchant_name_kana ?? '' ?></label></p>

        <div style="padding:1em; border:1px solid #060;">

            <?php $prop = !empty($payment_term_day) ? $checked : ''; ?>
            <p><label><input type="checkbox" data-group="term" name="payment_term_day" value="6"<?= $prop ?>>
            payment_term_day : 6</label></p>

            <?php $prop = !empty($payment_term_min) ? $checked : ''; ?>
            <p><label><input type="checkbox" data-group="term" name="payment_term_min" value="180"<?= $prop ?>>
            payment_term_min : 180</label></p>

        </div>


        <p id="hash">
        hash_proto : <?= $org_str ?? '' ?></p>

        <p>
        <input type="hidden" name="hc" value="<?= $hash ?? '' ?>">
        hc : <?= $hash ?? '' ?></p>

<p>
    <button type="button" onclick="removeChecks()"> 全チェック解除 </button>
    <button type="button" onclick="mod_hash()"> ハッシュ値更新 </button>
</p>

        <?php $prop = !empty($return_url) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="return_url" value="<?= $return_url ?? '' ?>"<?= $prop ?>>
        return_url : <?= $return_url ?? '' ?></label></p>

        <?php $prop = !empty($stop_return_url) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="stop_return_url" value="<?= $stop_return_url ?? '' ?>"<?= $prop ?>>
        stop_return_url : <?= $stop_return_url ?? '' ?></label></p>

        <?php $prop = !empty($finish_disable) ? $checked : ''; ?>
        <p><label><input type="checkbox" data-group="1" name="finish_disable" value="<?= $finish_disable ?? '' ?>"<?= $prop ?>>
        finish_disable : <?= $finish_disable ?? '' ?></label></p>

<?php if(!empty($hash)): ?>
    <p>
        <button type="button" onclick="go_payment()"> 決済手続開始 </button>
    </p>
<?php endif; ?>

    </form>



<script>

function mod_hash() {
    $('#send_params').attr('action', '/payment/test');
    $('#send_params').submit();
}

function go_payment() {
    $('input[name=mode]').attr('disabled', 'disabled');
    $('#send_params').attr('action', '<?= $payment_link_url ?? '' ?>');

    if ($('input[name=payment_term_min]').prop('checked')) {
        $('input[name=payment_term_day]').val('').attr('checked','checked');
    }

    $('#send_params').submit();
}

function removeChecks() {
    $('input[type=checkbox][data-group=1]').removeAttr('checked');
}

$('input[data-group=term]').on('change',function(){

    var current = $(this).attr('name');
    $('input[data-group=term]:not([name='+current+'])').removeAttr('checked');
})

</script>


</body>
</html>