<?php

$debug = false;
//$debug = true; // debug mode;

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


<?php if (empty($error) || !count($error)):

$sendlist = [
    'trading_id',
    'id',
    'seq_merchant_id',
    'fix_params',
    'customer_id',
    'payment_class',
    'use_card_conf_number',
    'stock_card_mode',
    'sales_flg',
    'customer_family_name',
    'customer_name',
    'customer_family_name_kana',
    'customer_name_kana',
    'customer_tel',
    'payment_detail',
    'payment_detail_kana',
    'merchant_name',
    'merchant_name_kana',
    'payment_term_day',
    'payment_term_min',
//    'hc',
    'return_url',
    'stop_return_url',
    'finish_disable'
];

$param = [];
foreach ($sendlist as $key)
    if (isset($$key))
        $param[$key] = $$key;

if (empty($param['payment_term_day'])) unset($param['payment_term_day']);
if (empty($param['payment_term_min'])) unset($param['payment_term_min']);

if (!empty($param['payment_term_min'])) $param['payment_type'] = $payment_type;

$param['hc'] = $hash;
$param['isbtob'] = 1;

if($debug): ?>
    <pre id="rawdata_0" style="display:none">
    <?= print_r($param, true) ?>
    </pre>
<?php endif;

// cURLで直接アクセスする

$cURL = curl_init($payment_link_url);
        
$option = [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 9,
    CURLOPT_FOLLOWLOCATION => true, //リダイレクト先を取得
    CURLOPT_MAXREDIRS      => 5, //5回のリダイレクトまで
    CURLOPT_SSL_VERIFYPEER => false, //サーバー証明書の検証を行わない
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query($param)
];
curl_setopt_array($cURL, $option);
$result = curl_exec($cURL);

curl_close($cURL);
    
if($debug): ?>
    <pre id="rawdata_1" style="display:none">
    <?= $result ?? '' ?>
    </pre>
<?php endif;

$t = str_replace("\n", "", $result);
$t = str_replace('"', '', $t);
$a = explode("\r", $t);

$r_param = [];
foreach($a as $row)
    if (strpos($row, '=') !== false) {

        $aa = explode('=', $row);

        $key = $aa[0];
        unset($aa[0]);
        $val = implode('=', $aa);

        $r_param[$key] = $val;
    }

if (!empty($r_param['url'])):
    
    // リンクURLをexに書き込む
    (new \App\Models\DB\Payment())->save([
        'id' => $trading_id,
        'ex' => json_encode([
            'url' => $r_param['url']
        ])
    ]);

    if ($debug): ?>
    <p>
        <a href="<?= $r_param['url'] ?>">データ送信OK・決済に進む</a>
    </p>

    <?php else: ?>
            
    <script>
    location.href = '<?= $r_param['url'] ?>';
    </script>

    <?php endif; // $debug

else: ?>

<form id="send_params2" method="post" action="/payment/error">

<input type="hidden" name="id" value="<?= $trading_id ?>">
<input type="hidden" name="error_code" value="<?= $r_param['response_code'] ?? '不明' ?>">
<input type="hidden" name="error_detail" value="<?= $r_param['response_detail'] ?? 'エラー内容取得できません' ?>">

<?php if ($debug): ?>

    <p>[<?= $r_param['response_code'] ?? '????' ?>]エラーが発生しました。</p>
    <p>内容：<b><?= $r_param['response_detail'] ?? 'エラー内容取得できません' ?></b></p>

    <button>　エラーページへ　</button>

<?php endif; // debug ?>

</form>

<?php if (!$debug): ?>
<script>
$('#send_params2').submit();
</script>
<?php endif; // !debug ?>



    <?php 

endif; // !empty($r_param['url'])

    else: // error exists ?>

        <form id="send_params" method="post" action="/payment/form">

            <?php foreach($error as $val): ?>
            <input type="hidden" name="error[]" value="<?= $val ?>">
            <?php endforeach; ?>

        </form>

<script>
$('#send_params').submit();
</script>

<?php endif; // !error ?>

</body>
</html>