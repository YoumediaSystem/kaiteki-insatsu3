<?php

const PAGE_NAME = 'メール雛形管理';

?><!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
    <meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
    <title><?= PAGE_NAME ?> |【管理】<?= $site['name'] ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" type="text/css" media="all" href="/css/style.css">
    <link rel="stylesheet" type="text/css" media="all" href="/css/form.css">
    <link rel="stylesheet" type="text/css" media="all" href="/css/form_admin.css">

<style>

.post table td.text-center {
    text-align:center;
}

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

<p>
<a href="/admin/mail_template_detail?mode=new">新規テンプレート登録</a>
／<a href="/admin/mail_sendlist">送信予約管理</a>
／<a href="/admin/mail_magazine">メルマガ一括登録</a>
</p>


<?php if(!empty($result_message)): ?>
    <p><em><?= $result_message ?></em></p>
<?php endif; ?>

<?php if(!empty($error) && count($error) > 0): ?>
    <ul id="error_list" class="attention">
    <?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
    </ul>
<?php endif; ?>



<form id="form_search" method="post" action="/admin/mail_template">
<input type="hidden" id="mode" name="mode" value="search">

<table>

<tr>
    <th>メール件名</th>
    <td><input type="text" name="subject" value="<?= $subject ?? '' ?>" placeholder="メール件名"></td>
</tr>

</table>

<input type="hidden" id="page" name="page" value="0">

<p class="buttons text-center">
<button> 検索 </button>
</p>

</form>



<?php if (isset($pager['count_all'])): ?>

    <p>絞込結果 <?= $pager['count_all'] ?>件　<?php

    for ($i=0; $i<=$pager['max_page']; $i++):

        $page_number =  $i+1;

        if ($i != 0) echo ' '; // delimiter

        if ($i == $page): echo $page_number;
        
        else: ?>
        <button type="button" onclick="changePage(<?= $i ?>)"><?= $page_number ?></button>
    <?php endif;

    endfor; ?>
    </p>

<table>

    <tr>
    <th style="width:2em">ID</th>
    <th>件名</th>
    <th>ステータス</th>
    </tr>

    <?php

    foreach ($template_list as $data): ?>

    <tr>
    <th style="width:2em"><?= $data['id'] ?></th>
    <td><a href="/admin/mail_template_detail?id=<?= $data['id'] ?? '' ?>"><?= $data['subject'] ?? '' ?></a></td>
    <td class="text-center"><?= $data['status_name'] ?? '' ?></td>
    </tr>

    <?php endforeach; ?>

</table>

<?php endif; // user_list ?>



            </article>
        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>