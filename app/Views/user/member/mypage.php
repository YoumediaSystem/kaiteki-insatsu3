<?php



const PAGE_NAME = 'マイページ';

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

<style>

.text-right {
    text-align:right;
}

h4 {
    margin: 1em 0;
    text-align:center;
}

</style>

    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>
</head>

<body>

    <?= $view['header'] ?>

    <div id="wrapper">

        <section class="content">

            <h3 class="heading"><?= PAGE_NAME ?></h3>
            <article class="post">



<?php if(!empty($payment_result)): ?>
    <p><em>決済手続き完了しました。お支払い方法によっては入金確認に時間がかかります。最新の状況は入稿履歴をご覧ください。</em></p>
<?php endif; ?>

<?php if(!empty($result_message)): ?>
    <p><em><?= $result_message ?></em></p>
<?php endif; ?>

<?php if(!empty($error) && count($error) > 0): ?>
    <ul id="error_list" class="attention">
    <?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
    </ul>
<?php endif; ?>


<?php if(!empty($b_yet_payment)): ?>

    <p>
        <strong>未入金の発注があります。</strong>
        <a href="/payment/form">お支払い手続きする＞＞</a>
    </p>

<?php endif; ?>

<?php if(!empty($b_aborted_payment)): ?>

<ul class="attention">
    <li>ATM決済・コンビニ決済で予約番号発行済の場合は、店頭お支払い後反映までしばらくお待ちください。</li>
    <li>ネットバンク決済で金融機関サイトから先にアクセスした場合は、入金確認または再決済案内が出るまでしばらくお待ちください。</li>
    <li>上記以外の場合は、入金待ち発注詳細ページから再度お支払い手続きしてください。</strong>
    <a href="/order/list">入稿履歴＞＞</a>
</p>

<?php endif; ?>

<?php if(!empty($b_waiting_netbank)): ?>

<p>
    <strong>ネットバンク決済手続きの途中でタブを閉じた場合は、入金待ち発注詳細ページから再度お支払い手続きしてください。</strong>
    <a href="/order/list">入稿履歴＞＞</a>
</p>

<?php endif; ?>



<?php if(!empty($auth_url)): ?>

<p>24時間以内にメール内のURLにアクセスし、お手続きを完了してください。</p>

<p>メールが届かない場合は、迷惑メールフォルダもご確認ください。</p>

<?php endif; ?>
<?php if($environ != 'real' && !empty($auth_url)): ?>

<p>
    <a href="<?= $auth_url ?? '' ?>">認証をスキップする＞</a>
</p>

<?php endif;

$user_info = [];
$user_info[] = '会員no.'.$user['id'];

if (!empty($honya_id))
    $user_info[] = '快適本屋さんID.'.$honya_id;

if (!empty($youclub_id))
    $user_info[] = 'YouClub登録ナンバー.'.$youclub_id;

?>


<p>
    <?= $user['name'] ?>様（<?= implode('　', $user_info) ?>）
</p>

<p>
    現在のポイント　<b><?= number_format($user['point']) ?></b>pt
    　<a href="/point_history">ポイント履歴＞＞</a>
</p>

<h4>お知らせ</h4>

<p>※現在のお知らせはありません。</p>


<p class="text-right">
    <a href="/order/list">入稿履歴＞＞</a>
</p>

<p class="text-right">
    <a href="/user/user_form">会員情報の変更＞＞</a>
</p>

<p class="text-right">
    <a href="/user/pass_form">パスワード変更＞＞</a>
</p>

<p class="text-right">
    <a href="/user/mail_form">メールアドレス変更＞＞</a>
</p>




            </article>
        </section>


    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>