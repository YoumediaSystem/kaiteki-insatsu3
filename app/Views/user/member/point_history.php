<?php



const PAGE_NAME = 'ポイント履歴';

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

#point_history {
    width:100%;
}

.post table td.number {
    text-align:right;
}

.minus {
    color:#c00;
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



<?php if(!empty($result_message)): ?>
    <p><em><?= $result_message ?></em></p>
<?php endif; ?>

<?php if(!empty($error) && count($error) > 0): ?>
    <ul id="error_list" class="attention">
    <?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
    </ul>
<?php endif; ?>


<?php foreach($history as $data) {
    if (!empty($data['expire_date'])) {
        $expire_text = $data['expire_date'];
        break;
    }
} ?>


                <p>
                    <?= $user['name'] ?>様（会員no.<?= $user['id'] ?>）
                </p>

                <p>
                    現在のポイント　<?= number_format($user['point']) ?>pt　有効期限：<?= $expire_text ?? '' ?>
                </p>



<table id="point_history">

<tr>
    <th>日付</th>
    <th>pt</th>
    <th>内容</th>
</tr>

<?php foreach($history as $data): ?>

<tr>
    <td><?= $data['create_date'] ?? '' ?></td>
    <td class="number"><?= number_format($data['point']) ?? '' ?></td>
    <td><?= $data['detail'] ?? '' ?></td>
</tr>

<?php endforeach; ?>

</table>

<script>

$('.number:contains("-")').addClass('minus');

</script>


<p class="text-right">
    <a href="/contact/index?contact_type=<?= urlencode('ポイント移動申請') ?>">ポイント移動申請＞＞</a>
</p>

            </article>
        </section>


    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>