<?php

const PAGE_NAME = '管理ユーザー情報';

$selected = ' selected="selected"';

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


<?php if(!empty($admin['role']) && $admin['role'] == 'master'): ?>

    <p><a href="/admin/system">システム管理者専用</a></p>

    <hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<?php endif; ?>


<form id="form_login_user" method="post" action="/admin/admin_do">
<input type="hidden" name="mode" value="modify">
<input type="hidden" name="id" value="<?= $admin['id'] ?? 0 ?>">
<input type="hidden" name="role" value="<?= $admin['role'] ?? '' ?>">
<input type="hidden" name="status" value="<?= $status ?? 0 ?>">

<table>

<tr>
    <th class="column_id">ID</th>
    <th>メールアドレス</th>
    <th class="pass">パスワード</th>
    <th>ユーザー名</th>
</tr>

<tr>
    <th class="column_id"><?= $admin['id'] ?? '' ?></th>
    <td><input type="text" name="mail_address" value="<?= $mail_address ?? '' ?>" placeholder="name@domain.jp"></td>
    <td><input type="password" name="pass" value=""></td>
    <td><input type="text" name="name" value="<?= $name ?? '' ?>"></td>
</tr>

</table>


<p class="buttons text-center">
<button>変更する</button>
</p>

</form>



<?php if(!empty($admin['role']) && $admin['role'] == 'master'): ?>

<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<h4>管理ユーザー追加登録</h4>

<form id="form_add_user" method="post" action="/admin/admin_do">
<input type="hidden" name="mode" value="create">
<input type="hidden" name="status" value="0">

<table>

<tr>
    <th>メールアドレス</th>
    <th class="pass">パスワード</th>
    <th>ユーザー名</th>
    <th>クライアント</th>
    <th>権限</th>
</tr>

<tr>
    <td><input type="text" name="mail_address" value="" placeholder="name@domain.jp"></td>
    <td><input type="password" name="pass" value=""></td>
    <td><input type="text" name="name" value=""></td>
    <td class="text-center">
        <select name="client_code">
            <?php foreach($client_list as $client): ?>
            <option><?= $client ?></option>
            <?php endforeach; ?>
            <option value="">（なし）</option>
        </select>
    </td>
    <td class="text-center">
        <select name="role">
            <option>client</option>
            <option>staff</option>
            <option>master</option>
        </select>
    </td>
</tr>

</table>


<p class="buttons text-center">
<button>追加する</button>
</p>

</form>




<hr><!-- －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－ -->

<h4>管理ユーザーリスト</h4>

<?php endif; // is master ?>


<?php
/*

if (isset($pager['count_all'])): ?>

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
*/ ?>

<?php if(isset($admin_list) && count($admin_list)): ?>

<table>

<tr>
    <th>ID</th>
    <th>メールアドレス</th>
    <th class="pass">パスワード</th>
    <th>ユーザー名</th>
    <th>クライアント</th>
    <th>権限</th>
    <th>状態</th>
    <th>&nbsp;</th>
</tr>

<?php foreach($admin_list as $key=>$data): ?>

<form id="form_mod_user_<?= $data['id'] ?>" method="post" action="/admin/admin_do">
<input type="hidden" name="mode" value="modify">

<tr>
    <td class="text-center"><input type="hidden" name="id" value="<?= $data['id'] ?? '' ?>"><?= $data['id'] ?? '' ?></td>
    <td><input type="text" name="mail_address" value="<?= $data['mail_address'] ?? '' ?>" placeholder="name@domain.jp"></td>
    <td><input type="password" name="pass" value=""></td>
    <td><input type="text" name="name" value="<?= $data['name'] ?? '' ?>"></td>
    <td>
        <select name="client_code">
            <option value="">（なし）</option>
            <?php foreach($client_list as $client):
                
                $prop = ($client == $data['client_code'])
                ? $selected : '';
                ?>
            <option <?= $prop ?>><?= $client ?></option>
            <?php endforeach; ?>
        </select>
    </td>
    <td>
        <select name="role">
            <?php foreach(['client','staff','master'] as $role):
                
                $prop = ($role == $data['role'])
                ? $selected : '';
                ?>
            <option <?= $prop ?>><?= $role ?></option>
            <?php endforeach; ?>
        </select>
    </td>
    <td>
        <select name="status">
            <?php foreach([0 =>'有効', -1 =>'無効'] as $status=>$s_name):
                
                $prop = ($status == $data['status'])
                ? $selected : '';
                ?>
            <option <?= $prop ?>><?= $s_name ?></option>
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

<?php endif; // admin_list ?>



            </article>
        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>