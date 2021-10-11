<?php

const PAGE_NAME = '入稿フォーム';

//$param['price'] = get_price($param);
$lib = new \App\Models\CommonLibrary();
$OrderForm = new \App\Models\Service\OrderForm();
$key2name       = $OrderForm->getKey2Names();
$preview        = $OrderForm->getPreviewKeys();
$not_preview    = $OrderForm->getNotPreviewKeys();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
    <meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">

    <title>内容確認 | <?= PAGE_NAME ?> | <?= $site['name'] ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/form.css">

    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>

    <script src="/js/warning_freemail.js"></script>
</head>

<body>

    <?= $view['header'] ?>

    <div id="wrapper">

        <section class="content">



<h2 class="heading">内容確認 | 入稿フォーム</h2>

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



<?php if(!empty($error)): ?>
<ul id="error_list" class="attention">
<?php foreach($error as $message) echo '<li>'.$message.'</li>'; ?>
</ul>
<?php endif; ?>



<ul class="attention form">
<li>入力した内容を確認してください。よろしければ「送信」ボタンをクリックしてください。</li>
<li>メールアドレスに誤りがございますと詳細のご連絡を差し上げられませんので、再度ご確認ください。</li>
<li>「ドメイン指定受信」設定を行っている場合は【<strong>kaitekiinsatsu.com</strong>】からのメールを受信できるように設定を行ってください。</li>
<li>内容修正は「修正」ボタンを押してください。ブラウザバック(左スワイプ)しないでください。</li>
</ul>



<div id="form_area" class="text">

<div class="ec-borderedDefs">

    <?php
    
    $b_delivery_event_1 = !empty($number_event_1);
    $b_delivery_event_2 = !empty($number_event_2);
    $b_delivery_other  = !empty($number_other);
    
    foreach($preview as $key):

        if (strpos($key, 'event_1_') !== false
        &&  !$b_delivery_event_1) continue;

        if (strpos($key, 'event_2_') !== false
        &&  !$b_delivery_event_2) continue;

        if (strpos($key, 'other_') !== false
        &&  !$b_delivery_other) continue;

        if (!empty($$key)):

    /*
            $index_header = '';
            if (strpos($key, 'delivery_1') !== false)
                $index_header = '納品先1・';

            if (strpos($key, 'delivery_2') !== false)
                $index_header = '納品先2・';
    */
            $index_key = $key;
    /*		
            $index_key = str_replace('delivery_1','', $index_key);
            $index_key = str_replace('delivery_2','', $index_key);
    */
            $index_text = $key2name[$index_key] ?? '';

            $value_text = ($key == 'auth_key')
                ? str_repeat('*', strlen($$key))
                : htmlspecialchars($$key, ENT_QUOTES);
            
            if ($key == 'b_overprint_kaiteki') {
                $value_text = !empty($$key)
                ? '余部を快適本屋さんに委託する'
                : '（なし）';
            }
        ?>
    <dl>
    <dt>
        <h4><?= $index_text ?></h4>
    </dt>
    <dd><?= $value_text ?></dd>
    </dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

    <?php endif; endforeach; ?>


    <dl>
    <dt>
        <h4>発注金額</h4>
    </dt>
    <dd>
        
        <table>
            <thead>
                <tr>
                    <th>内容</th>
                    <th class="digit-5">金額</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($price['detail'] as $key=>$val): ?>
                <tr>
                    <th><?= $val['text'] ?></th>
                    <td class="text-number"><?= $val['price'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="2" class="text-number">
                    合計（税込）
                    <b><?= number_format($price['total'], 0); ?>円</b>
                    </td>
                </tr>
            </tfoot>
        </table>
        
    </dd>
    </dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->


</div><!-- ec-borderedDefs -->



    <div class="text-center buttons">

        <form method="post" action="/order/form">
            <input type="hidden" name="from_id" value="<?= $from_id ?? '' ?>">

            <?php foreach($preview as $key): ?>
            <input type="hidden" name="<?= $key ?>" value="<?= htmlspecialchars($$key ?? '', ENT_QUOTES) ?>">
            <?php endforeach; ?>

            <?php foreach($not_preview as $key): ?>
            <input type="hidden" name="<?= $key ?>" value="<?= htmlspecialchars($$key ?? '', ENT_QUOTES) ?>">
            <?php endforeach; ?>

            <button class="ec-blockBtn--cancel" type="submit" name="mode" value="back">編集</button>
        </form>

        <form method="post" action="/order/do">

            <?php foreach($preview as $key): ?>
            <input type="hidden" name="<?= $key ?>" value="<?= htmlspecialchars($$key ?? '', ENT_QUOTES) ?>">
            <?php endforeach; ?>

            <?php foreach($not_preview as $key): ?>
            <input type="hidden" name="<?= $key ?>" value="<?= htmlspecialchars($$key ?? '', ENT_QUOTES) ?>">
            <?php endforeach; ?>

            <input type="hidden" name="price" value="<?= $price['total'] ?>">
            <input type="hidden" name="price_text" value="<?= $lib->unicode_escape($price['price_text']) ?>">

            <button class="ec-blockBtn--action" type="submit" name="mode" value="complete">送信</button>
        </form>

    </div><!-- text-center -->



</div><!-- form_area -->


<script type="text/javascript">

var global = global || {};

</script>



</section>



</div><!-- wrapper -->

<?= $view['footer'] ?>



</body>
</html>