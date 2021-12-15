<?php

const PAGE_NAME = '締切管理';

$checked	= ' checked="checked"';
$selected	= ' selected="selected"';
$disabled	= ' disabled="disabled"';
$hidden		= ' style="display:none"';

$DT = new DateTime();
$now_y = (int)$DT->format('Y');

$select_y = range($now_y - 100, $now_y + 1);
$select_m = range(1,12);
$select_d = range(1,31);

$b_client_select = (!empty($client_list) && 1 < count($client_list));

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

.text-center {
    text-align:center;
}

.text-right {
    text-align:right;
}

h4 {
    margin: 1em 0;
    text-align:center;
}

.column_id {
    width:2em;
}

input[type="password"],
th.pass {
    width:6em;
}

.post table td.text-center {
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

<?php if(!empty($error) && count($error)): ?>
    <ul id="error_list" class="attention">
    <?php foreach($error as $error_message) echo '<li>'.$error_message.'</li>'; ?>
    </ul>
<?php endif; ?>



<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<h4>前倒し締切を追加登録</h4>

<form id="form_add_user" method="post" action="/admin/limit_do">
<input type="hidden" name="mode" value="create">
<input type="hidden" name="status" value="0">

<table>

<tr>
    <th>業者コード</th>
    <th>納品日</th>
    <th>入稿締切日</th>
</tr>

<tr>
    <td class="text-center">
<?php if ($b_client_select): ?>

    <select name="client_code">
        <?php foreach($client_list as $item): ?>
            <option value="<?= $item ?>"><?= $item ?></option>
        <?php endforeach; ?>
    </select>

<?php else: ?>
    <input type="hidden" name="client_code" value="<?= $admin['client_code'] ?? '' ?>">
    <?= $admin['client_code'] ?? '' ?>

<?php endif; ?>
    </td>


<?php $key = 'print_up_date';

$print_up_date_y = $print_up_date_y ?? $now_y;
$print_up_date_m = $print_up_date_m ?? 1;
$print_up_date_d = $print_up_date_d ?? 1;

?>
    <td>

    <?php $kkey = $key.'_y'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-6">
            <option value="">-</option>
            <?php foreach($select_y as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_m'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_m as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_d'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_d as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>



<?php $key = 'limit_date';

$limit_date_y = $limit_date_y ?? $now_y;
$limit_date_m = $limit_date_m ?? 1;
$limit_date_d = $limit_date_d ?? 1;

?>
    <td>

    <?php $kkey = $key.'_y'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-6">
            <option value="">-</option>
            <?php foreach($select_y as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_m'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_m as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_d'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_d as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>
</tr>

</table>


<p class="buttons text-center">
<button>追加する</button>
</p>

</form>




<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<h4>前倒し締切一覧</h4>


<?php if(isset($limit_date_list) && count($limit_date_list)): ?>

<table>

<tr>
    <th style="width:2em">ID</th>
    <th>業者コード</th>
    <th>納品日</th>
    <th>入稿締切日</th>
    <th style="width:5em">状態</th>
    <th style="width:4em">&nbsp;</th>
</tr>

<?php foreach($limit_date_list as $k=>$data): ?>

<!--
<?= print_r($data, true) ?>
-->

<form id="form_mod_<?= $data['id'] ?>" method="post" action="/admin/limit_do">
<input type="hidden" name="mode" value="modify">

<tr>
    <td class="text-center">
        <input type="hidden" name="id" value="<?= $data['id'] ?? '' ?>"><?= $data['id'] ?? '' ?>
    </td>
    
    <td class="text-center">
    <?php if ($b_client_select): ?>

        <select name="client_code">
            <?php foreach($client_list as $item):
                $prop = ($item == $data['client_code']) ? $selected : ''; ?>
                <option value="<?= $item ?>"<?= $prop ?>><?= $item ?></option>
            <?php endforeach; ?>
        </select>

        <?php else: ?>
        <input type="hidden" name="client_code" value="<?= $admin['client_code'] ?? '' ?>">
        <?= $admin['client_code'] ?? '' ?>

        <?php endif; ?>
    </td>


<?php $key = 'print_up_date';

$print_up_date_y = $data['print_up_date_y'] ?? $now_y;
$print_up_date_m = $data['print_up_date_m'] ?? 1;
$print_up_date_d = $data['print_up_date_d'] ?? 1;

?>
    <td>

    <?php $kkey = $key.'_y'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-6">
            <option value="">-</option>
            <?php foreach($select_y as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_m'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_m as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_d'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_d as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>



<?php $key = 'limit_date';

$limit_date_y = $data['limit_date_y'] ?? $now_y;
$limit_date_m = $data['limit_date_m'] ?? 1;
$limit_date_d = $data['limit_date_d'] ?? 1;

?>
    <td>

    <?php $kkey = $key.'_y'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-6">
            <option value="">-</option>
            <?php foreach($select_y as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_m'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_m as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

        <?php $kkey = $key.'_d'; ?>
        <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
            <option value="">-</option>
            <?php foreach($select_d as $val):
                $prop = ($val == $$kkey) ? $selected : ''; ?>
                <option value="<?= $val ?>"<?= $prop ?>><?= $val ?></option>
            <?php endforeach; ?>
        </select>

    </td>

    <td>
        <select name="status">
            <?php foreach([0 =>'有効', -1 =>'無効'] as $status=>$s_name):
                
                $prop = ($status == $data['status'])
                ? $selected : '';
                ?>
            <option value="<?= $status ?>" <?= $prop ?>><?= $s_name ?></option>
            <?php endforeach; ?>
        </select>
    </td>
    <td>
        <button>更新</button>
    </td>
</tr>
</form>

    <?php endforeach; ?>

</table>

<?php endif; // limit_date_list ?>



            </article>
        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>