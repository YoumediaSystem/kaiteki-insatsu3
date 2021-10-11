<?php

const PAGE_NAME = '内容確認 | 新規登録フォーム';

?><!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
	<meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
	<title><?= PAGE_NAME ?> | <?= $site['name'] ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="stylesheet" type="text/css" media="all" href="/css/style.css">
	<link rel="stylesheet" type="text/css" media="all" href="/css/form.css">

	<script src="/js/jquery.js"></script>
	<script src="/js/script.js"></script>
	<script src="/js/pagetop.js"></script>
</head>

<body>

	<?= $view['header'] ?>

	<div id="wrapper">

        <section class="content">

            <h3 class="heading"><?= PAGE_NAME ?></h3>

            <div id="form_area" class="text">

                <form id="form1" method="post" action="/user/signup_form">
                <input type="hidden" name="mode" value="signup">
                <input type="hidden" name="hash" value="<?= $hash ?? '' ?>">



                <div class="ec-borderedDefs">

                    <dl>
                    <dt><h4>メールアドレス</h4></dt>
                    <dd>
                        <input type="hidden" name="mail_address" value="<?= htmlspecialchars($mail_address ?? '', ENT_QUOTES) ?>">
                        <?= $mail_address ?>
                    </dd>
                    </dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->


                    <dl>
                    <dt><h4>パスワード</h4></dt>
                    <dd>
                        <input type="hidden" name="pass" value="<?= htmlspecialchars($pass ?? '', ENT_QUOTES) ?>">
                        <?= str_pad('', strlen($pass), '*') ?>
                    </dd>
                    </dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->


                    <dl>
                    <dt><h4>氏名（カナ）</h4></dt>
                    <dd>
                        <input type="hidden" name="sei" value="<?= htmlspecialchars($sei ?? '', ENT_QUOTES) ?>">
                        <input type="hidden" name="mei" value="<?= htmlspecialchars($mei ?? '', ENT_QUOTES) ?>">
                        <input type="hidden" name="sei_kana" value="<?= htmlspecialchars($sei_kana ?? '', ENT_QUOTES) ?>">
                        <input type="hidden" name="mei_kana" value="<?= htmlspecialchars($mei_kana ?? '', ENT_QUOTES) ?>">
                        <?= htmlspecialchars($sei.' '.$mei, ENT_QUOTES) ?>（<?= htmlspecialchars($sei_kana.' '.$mei_kana, ENT_QUOTES) ?>）
                    </dd>
                    </dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->


                    <dl>
                    <dt><h4>生年月日</h4></dt>
                    <dd>
                        <input type="hidden" name="birthday" value="<?= $birthday ?? '' ?>">
                        <input type="hidden" name="birthday_y" value="<?= $birthday_y ?? '' ?>">
                        <input type="hidden" name="birthday_m" value="<?= $birthday_m ?? '' ?>">
                        <input type="hidden" name="birthday_d" value="<?= $birthday_d ?? '' ?>">
                        <?= $birthday ? str_replace('-', '/', $birthday) : '' ?>
                    </dd>
                    </dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->


                    <dl>
                    <dt><h4>性別</h4></dt>
                    <dd>
                        <input type="hidden" name="sextype" value="<?= $sextype ?? '' ?>">
                        <?= $sextype ?? '' ?>
                    </dd>
                    </dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->


                    <dl>
                    <dt><h4>郵便番号・住所</h4></dt>
                    <dd>
                        <input type="hidden" name="zipcode" value="<?= htmlspecialchars($zipcode ?? '', ENT_QUOTES) ?>">
                        <input type="hidden" name="address1" value="<?= htmlspecialchars($address1 ?? '', ENT_QUOTES) ?>">
                        <input type="hidden" name="address2" value="<?= htmlspecialchars($address2 ?? '', ENT_QUOTES) ?>">
                        <?= htmlspecialchars($zipcode ?? '', ENT_QUOTES)
                        ?>　<?= htmlspecialchars($address1 ?? '', ENT_QUOTES)
                        ?>　<?= htmlspecialchars($address2 ?? '', ENT_QUOTES) ?>
                    </dd>
                    </dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->


                    <dl>
                    <dt><h4>電話番号（連絡可能時間帯）</h4></dt>
                    <dd>
                        <input type="hidden" name="tel" value="<?= htmlspecialchars($tel ?? '', ENT_QUOTES) ?>">
                        <input type="hidden" name="tel_range" value="<?= htmlspecialchars($tel_range ?? '', ENT_QUOTES) ?>">
                        <?= htmlspecialchars($tel ?? '', ENT_QUOTES)
                        ?><?= $tel_range ? htmlspecialchars('（'.$tel_range.'）', ENT_QUOTES) : '' ?>
                    </dd>
                    </dl><!-- －－－－－－－－－－－－－－－－－－－－－ -->


                </div><!-- ec-borderedDefs -->

                <div class="text-center buttons">

                <button type="button" id="go_prev" class="ec-blockBtn--cancel" name="mode" value="form">再編集</button>

                <button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="confirm">登録する</button>

                </div><!-- text-center -->

                </form>


            
            </div><!-- form_area -->

        </section>

	</div><!-- wrapper -->

	<?= $view['footer'] ?>

<script type="text/javascript">

var global = global || {};

$('#go_prev').on('click', function(){

    $('#form1').attr('action','/user/signup_form');
    $('#form1').submit();
});

$('#go_next').on('click', function(){

    $('#form1').attr('action','/user/signup_do');
    $('#form1').submit();
});

</script>

</body>
</html>