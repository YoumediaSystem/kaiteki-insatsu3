<?php

const PAGE_NAME = 'メルマガ一括設定';

$lib = new \App\Models\CommonLibrary();
$Config = new \App\Models\Service\Config();

$DT = new \Datetime();
$now_h = (int)$DT->format('H');
$now_y = (int)$DT->format('Y');
if (18 <= $now_h) $DT->modify('+1 day');
$DT->modify($Config->getSendMagazineHour().'00');

$select_y = range($now_y, $now_y + 1);
$select_m = range(1,12);
$select_d = range(1,31);
$select_h = range(0,23);

$request_date_y = $request_date_y ?? $DT->format('Y');
$request_date_m = $request_date_m ?? $DT->format('n');
$request_date_d = $request_date_d ?? $DT->format('j');
$request_date_h = $request_date_h ?? $DT->format('H');

$checked	= ' checked="checked"';
$selected	= ' selected="selected"';
$disabled	= ' disabled="disabled"';
$hidden		= ' style="display:none"';

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



<form id="form_search" method="post" action="/admin/mail_magazine">
<input type="hidden" id="mode" name="mode" value="search">

<table>

    <tr>
        <th>受注履歴・クライアント</th>
        <td>
        
        <select name="client_code">
            <option value="">（全て）</option>
            <option value="taiyou">大陽出版株式会社</option>
        </select>
        
        </td>
    </tr>

    <tr>
        <th>メールテンプレート</th>
        <td>
        
        <select name="template_id">
            <option value="">（指定なし）</option>
            <?php
            if (!empty($template_list) && count($template_list)):

            foreach ($template_list as $list):
                if (!empty($list['id'])):
            ?>
                <option value="<?= $list['id'] ?? '' ?>"><?= $list['subject'] ?? '' ?></option>

            <?php endif; endforeach; endif; ?>
        </select>
        
        </td>
    </tr>

</table>

<p class="wrap_checkbox">

    <label>
    <input type="checkbox" name="b_repeat"> 選択雛形メール送信済・予約済を含む
    </label>

</p>

<input type="hidden" id="page" name="page" value="0">

<p class="buttons text-center">
<button> 検索 </button>
</p>

</form><!-- search -->



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

<form id="form1" method="post" action="/admin/mail_magazine_confirm">

<table>

    <tr>
    <th style="width:2em"></th>
    <th style="width:2em">ID</th>
    <th style="width:16em">氏名（カナ）</th>
    <th>メールアドレス</th>
    <th>送信状況</th>
    </tr>

    <?php

    foreach ($user_list as $data):
    
        $user_status = ($data['status'] != 0)
        ? $statusName[$data['status'] ?? '']
        : '';
        ?>

    <tr>
        <th style="width:2em">
            <input type="checkbox" name="user_id[]" value="<?= $data['id'] ?>">
        </th>
        <td class="number"><?= $data['id'] ?></td>
        <td><a href="/admin/user_detail?id=<?= $data['id'] ?>"><?= $data['name'] ?></a><small>（<?= $data['name_kana'] ?>）<?= $user_status ?? '' ?></small></td>
        <td><?= $data['mail_address'] ?></td>
        <td><?= $send_statusName[$data['send_status'] ?? ''] ?? '' ?></td>
    </tr>

    <?php endforeach; ?>

</table>



<p class="text-center">
送信するテンプレート

<select name="template_id">
    <?php
    if (!empty($template_list) && count($template_list)):

    foreach ($template_list as $list):
        if (!empty($list['id'])):
    ?>
        <option value="<?= $list['id'] ?? '' ?>"><?= $list['subject'] ?? '' ?></option>

    <?php endif; endforeach; endif; ?>
</select>

</p>



<p class="wrap_input_datetime text-center">
予約日時

<?php $key = 'request_date'; ?>

    <?php $kkey = $key.'_y'; ?>
    <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-6">
        <option value="">-</option>
        <?php foreach($select_y as $val):
            $prop = ($val == $$kkey) ? $selected : ''; ?>
            <option value="<?= $val ?>"<?= $prop ?>><?= $val ?>年</option>
        <?php endforeach; ?>
    </select>

    <?php $kkey = $key.'_m'; ?>
    <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
        <option value="">-</option>
        <?php foreach($select_m as $val):
            $prop = ($val == $$kkey) ? $selected : ''; ?>
            <option value="<?= $val ?>"<?= $prop ?>><?= $val ?>月</option>
        <?php endforeach; ?>
    </select>

    <?php $kkey = $key.'_d'; ?>
    <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
        <option value="">-</option>
        <?php foreach($select_d as $val):
            $prop = ($val == $$kkey) ? $selected : ''; ?>
            <option value="<?= $val ?>"<?= $prop ?>><?= $val ?>日</option>
        <?php endforeach; ?>
    </select>

    <?php $kkey = $key.'_h'; ?>
    <select id="<?= $kkey ?>" name="<?= $kkey ?>" class="digit-4">
        <option value="">-</option>
        <?php foreach($select_h as $val):
            $prop = ($val == $$kkey) ? $selected : ''; ?>
            <option value="<?= $val ?>"<?= $prop ?>><?= $val ?>時</option>
        <?php endforeach; ?>
    </select>

</p>


<p class="buttons text-center">
<button> 一括予約する </button>
</p>

</form>

<?php endif; // user_list ?>



            </article>
        </section>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>