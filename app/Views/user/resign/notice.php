<?php



const PAGE_NAME = '退会ご希望の方へ | 退会';

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


    <section class="content">

<h2 class="heading"><?= PAGE_NAME ?></h2>

<style>

pre {
white-space:pre-wrap;
margin-bottom:1em;
}

.text-right {
    text-align:right;
}

</style>


<article class="post">

    <h3>退会前に以下をご確認ください。</h3>

    <ul class="attention">

        <?php if($b_doing_order): ?>

            <li><strong>現在進行中の入稿発注がある場合は退会できません。</strong>納品完了後から退会手続き可能となります。</li>

        <?php else: ?>

            <li>現在進行中の入稿発注がある場合は退会できません。納品完了後から退会手続き可能となります。</li>

        <?php endif; ?>

        <li>ご登録のメールアドレスを既に使用していない場合は、
        メールアドレス変更手続きで現在お使いのアドレスに変更可能です。</li>

        <li>その他「よくあるご質問」ページに記載のない質問や不明点は、お気軽に
        <a href="/contact">お問合せ</a>ください。</li>
        
    </ul>

    <?php if(!$b_doing_order): ?>
        <p class="text-right">
        <a href="/resign_form">退会手続き＞＞</a>
        </p>
    <?php endif; ?>



</article>
</section>


</div><!-- wrapper -->

<?= $view['footer'] ?>

</body>
</html>