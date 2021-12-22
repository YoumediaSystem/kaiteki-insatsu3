<?php

const PAGE_NAME = '商品管理';

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



<?php if(!empty($result_message)): ?>
    <p><em><?= $result_message ?></em></p>
<?php endif; ?>

<?php if(isset($error)): ?>
    <ul id="error_list" class="attention">
    <?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
    </ul>
<?php endif; ?>



<form id="form_search" method="post" action="/admin/product">
<input type="hidden" id="mode" name="mode" value="search">

<table>

<tr>
    <th>クライアント</th>
    <td>
        <select name="client_code">
            <?php
            if (isset($client_list)):
                foreach($client_list as $client): ?>
            <option><?= $client ?></option>
            <?php endforeach; endif; ?>
            <option value="">（なし）</option>
        </select>
    </td>
</tr>

<tr>
    <th>商品名</th>
    <td><input type="text" name="name" value="<?= $name ?? '' ?>" placeholder="○○セット"></td>
</tr>

</table>

<input type="hidden" id="page" name="page" value="0">

<p class="buttons text-center">
<button> 検索 </button>
</p>

</form>

<!--
pager : <?= print_r($pager ?? '', true) ?>
-->

<!--
product_list : <?= print_r($product_list ?? '', true) ?>
-->

<?php if (isset($product_list['pager']['count_all'])):
    $pager = $product_list['pager'];
    unset($product_list['pager']);
?>

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
    <th style="width:2em">商品<br>no.</th>
    <th style="width:16em">商品名</th>
    <th>販売数 ／ 販売上限</th>
    <th style="width:5em">販売期間</th>
    </tr>

    <?php
    foreach ($product_list as $data):
    
        $DT = new \Datetime($data['open_date']);
        $open_date  = $DT->format('Y/n/j');

        $DT = new \Datetime($data['close_date']);
        $close_date = $DT->format('Y/n/j');
    ?>

    <tr>
    <th style="width:2em"><?= $data['id'] ?></th>
    <td><a href="/admin/product_detail?id=<?= $data['id'] ?>"><?= $data['name'] ?></a></td>
    <td>
    <?= $data['ordered'] ?? 0 ?>
    ／
    <?= $data['max_order'] ?? '上限なし' ?></td>
    <td><?= $open_date.'～'.$close_date ?></td>
    </tr>

    <?php endforeach; ?>

</table>

<?php endif; // product_list ?>



            </article>
        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>