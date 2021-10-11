<?php

//include_once(__DIR__.'/_config.php');
/*include_once($param['set_id'].'/_config.php');*/

const STEP_NAME = '内容確認';
const PAGE_NAME = 'お問合せ';

//include_once(__DIR__.'/_include.php');


?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
    <meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">

    <title><?= STEP_NAME ?> | <?= PAGE_NAME ?> | <?= $site['name'] ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/form.css">

    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>

    <script src="/js/warning_freemail.js"></script>
</head>

<body>
<?php if (isset($error) && count($error)): ?>

<p style="text-align:center">※お待ちください※</p>

<form id="form_error_back" method="post" action="/contact/index">

    <?php foreach($preview as $key): ?>
    <input type="hidden" name="<?= $key ?>" value="<?= $$key ?? '' ?>">
    <?php endforeach; ?>

    <?php foreach($not_preview as $key): ?>
    <input type="hidden" name="<?= $key ?>" value="<?= $$key ?? '' ?>">
    <?php endforeach; ?>

    <?php foreach($error as $val): ?>
    <input type="hidden" name="error[]" value="<?= $val ?>">
    <?php endforeach; ?>

</form>

<script>
    $('#form_error_back').submit();
</script>

<?php else: ?>

    <?= $view['header'] ?>

    <div id="wrapper">

        <section class="content">

<!--
<?php foreach($param as $key=>$val): ?>
<?= $key.':'.$val ?>,
<?php endforeach; ?>
-->

<h2 class="heading"><?= STEP_NAME ?> | <?= PAGE_NAME ?></h2>

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



<?php if(isset($error) && count($error)): ?>
<ul id="error_list" class="attention">
<?php foreach($error as $message) echo '<li>'.$message.'</li>'; ?>
</ul>
<?php endif; ?>



<ul class="attention form">
<li>入力した内容を確認してください。よろしければ「送信」ボタンをクリックしてください。</li>
<li>メールアドレスに誤りがございますと詳細のご連絡を差し上げられませんので、再度ご確認ください。</li>
<li>「ドメイン指定受信」設定を行っている場合は【<strong><?= explode('@', $admin_mail_address)[1] ?></strong>】からのメールを受信できるように設定を行ってください。</li>
<li>内容修正は「修正」ボタンを押してください。ブラウザバック(左スワイプ)しないでください。</li>
</ul>



<div id="form_area" class="text">

<div class="ec-borderedDefs">

    <?php foreach($preview as $key):

    if (!empty($$key)):

        $index_key = $key;
        
        $index_text = $key2name[$index_key];

        $value_text = ($key == 'auth_key')
            ? str_repeat('*', strlen($param[$key]))
            : htmlspecialchars($param[$key] ?? '', ENT_QUOTES);
        ?>
    <dl>
    <dt>
        <h4><?= $index_text ?></h4>
    </dt>
    <dd><?= $value_text ?></dd>
    </dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->

    <?php endif; endforeach; ?>

</div><!-- ec-borderedDefs -->



    <div class="text-center buttons">

        <form method="post" action="/contact/index">
        
            <?php foreach($preview as $key): ?>
            <input type="hidden" name="<?= $key ?>" value="<?= htmlspecialchars($param[$key] ?? '', ENT_QUOTES); ?>">
            <?php endforeach; ?>

            <?php foreach($not_preview as $key): ?>
            <input type="hidden" name="<?= $key ?>" value="<?= htmlspecialchars($param[$key] ?? '', ENT_QUOTES); ?>">
            <?php endforeach; ?>

            <button class="ec-blockBtn--cancel" type="submit" name="mode" value="back">編集</button>
        </form>

        <form method="post" action="/contact/sendmail">

            <?php foreach($preview as $key): ?>
            <input type="hidden" name="<?= $key ?>" value="<?= htmlspecialchars($param[$key] ?? '', ENT_QUOTES); ?>">
            <?php endforeach; ?>

            <?php foreach($not_preview as $key): ?>
            <input type="hidden" name="<?= $key ?>" value="<?= htmlspecialchars($param[$key] ?? '', ENT_QUOTES); ?>">
            <?php endforeach; ?>

            <button class="ec-blockBtn--action" type="submit" name="mode" value="complete">送信</button>
        </form>

    </div><!-- text-center -->



</div><!-- form_area -->


<script type="text/javascript">

var global = global || {};

</script>



</section>



</div><!-- wrapper -->

<?=  $view['footer']  ?>

<?php endif; // error or ok ?>

</body>
</html>