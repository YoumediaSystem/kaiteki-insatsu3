<?php

const PAGE_NAME = '内容確認 | 決済情報変更';

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



<?php if(!empty($error) && count($error) > 0): ?>
<ul id="error_list" class="attention">
<?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
</ul>
<?php endif; ?>



<h4>決済no.<?= $id ?? '' ?> 編集内容確認</h4>

<form id="form1" method="post" action="/admin/payment_do">
<input type="hidden" name="mode" value="modify">
<input type="hidden" name="id" value="<?= $id ?>">



<table>

<tr>
    <th>決済状況</th>
    <td>
        <input type="hidden" name="status" value="<?= $status ?? '' ?>">
        <?= $statusName[$status] ?? '' ?>
    </td>
</tr>

<tr>
<th>ペイジェント決済URL</th>
<td>
    <input type="hidden" name="url" value="<?= $url ?? '' ?>">
    <?= (!empty($paygent_id) && !empty($url))
    ? '<s>'.($url ?? '').'</s>'
    : $url ?? '' ?>
</td>
</tr>

<tr>
<th>ペイジェント決済ID</th>
<td>
    <input type="hidden" name="paygent_id" value="<?= $paygent_id ?? '' ?>">
    <?= $paygent_id ?? '' ?>
</td>
</tr>

<tr>
<th>管理備考</th>
<td>
    <input type="hidden" name="note" value="<?= htmlspecialchars($note, ENT_QUOTES) ?>">
    <?= htmlspecialchars($note, ENT_QUOTES) ?>
</td>
</tr>

</table>



<div class="text-center buttons">

    <button type="button" id="go_prev" class="ec-blockBtn--cancel" name="mode" value="form">再編集</button>

    <button type="button" id="go_next" class="ec-blockBtn--action" name="mode" value="confirm">編集確定</button>

</div><!-- text-center -->

                </form>


            
            </div><!-- form_area -->

        </section>

	</div><!-- wrapper -->

	<?= $view['footer'] ?>

<script type="text/javascript">

var global = global || {};

$('#go_prev').on('click', function(){

    $('#form1').attr('action','/admin/payment_form');
    $('#form1').submit();
});

$('#go_next').on('click', function(){

    var url = $('input[name="url"]').val();
    var paygent_id = $('input[name="paygent_id"]').val();

    if (url && paygent_id) {
        $('input[name="url"]').val('');
    }

    $('#form1').attr('action','/admin/payment_do');
    $('#form1').submit();
});

</script>

</body>
</html>