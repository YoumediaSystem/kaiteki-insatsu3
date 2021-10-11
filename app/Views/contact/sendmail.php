<?php
/*
//$subject_admin = '【快適印刷さん】お問合せ';

$subject_customer = '【快適本屋さん】お問合せを受付しました（自動返信）';

//$b_debug = true;

$b_debug = false;

include_once(__DIR__.'/_config.php');
include_once(__DIR__.'/_include.php');

$subject_admin = '【快適印刷さん】'.$param['contact_type'];

$MailLimit = new \App\Models\Service\MailLimit();

if (!$MailLimit->isCookie())
    $param['error'][] = 'ブラウザの設定で、Cookieを有効にしてください。携帯端末をご利用の方は、PC・タブレットまたはスマートフォンからお問合せください。';

if (!$MailLimit->isSendable())
$param['error'][] = '既にお問合せ済みです。時間をおいてから再度お問合せください。';


$ignore_keys = [
    'submit'
    ,'error'
];
$CR = "\n";

$MailLimit->initFromName('contact');
$MailLimit->initTrafficLogFile($base_dir.'/count_contact.dat');



// メール送信
if (count($param['error']) == 0) {

    // 管理者宛メール
    $param['subject'] = $subject_admin;
    
    $body = file_get_contents(__DIR__.'/template_admin.cgi');

    $delivery_text = $number_text = '';


    // メールテンプレート流し込み＆送信	
    foreach($param as $key=>$val)
        $body = str_replace('['.$key.']', $val, $body);

    $body .= "---------------------------------------------------------------\n";
    $body .= "Processed       : ".date("Y/m/d (D) H:i:s")."\n";
    $body .= "Server-Name     : ".$_SERVER['SERVER_NAME']."\n";
    $body .= "Script-Name     : ".$_SERVER['PHP_SELF']."\n";
    $body .= "HTTP-Referer    : ".$_SERVER['HTTP_REFERER']."\n";
    $body .= "HTTP-User-Agent : ".$_SERVER['HTTP_USER_AGENT']."\n";
    $body .= "Remote-Addr     : ".get_referer_ip()."\n";
    $body .= "---------------------------------------------------------------\n";

    $result = send_mail([
        'from'		=> $param['mail_address']
        ,'to'		=> $admin_mail_address
        ,'bcc'		=> $bcc_mail_address_2admin
        
        ,'subject'	=> $param['subject']
        ,'body'		=> $body
//		,'file'		=> $param['make_filename']
//		,'file_dir'	=> $file_dir
    ]);
    
    if (!$result) $param['error'][] =
    'フォーム送信できませんでした。何度もこのエラーが出る場合はお問合せフォームか、'
    .$admin_mail_address.'までお問合せください。';
    
    else {
        $MailLimit->modInfo(); // 正常に送信したことを記録する（連投防止）
        $MailLimit->destroySessionKey(); // セッションキー解除
        session_destroy();
    }
}

if (count($param['error']) == 0) {$complete_param = [];

    // 申請者宛メール（自動返信）
    
    $body = file_get_contents(__DIR__.'/template_customer.cgi');

    foreach($param as $key=>$val)
        $body = str_replace('['.$key.']', $val, $body);

    $result = send_mail([
        'from'		=> $admin_mail_address
        ,'to'		=> $param['mail_address']
        ,'bcc'		=> $bcc_mail_address_2user
        
        ,'subject'	=> $subject_customer
        ,'body'		=> $body
//		,'file'		=> $param['make_filename']
//		,'file_dir'	=> $file_dir
    ]);
    
    // エラーではなく完了画面へ遷移
    $complete_param = [];
    if (!$result) $complete_param['auto_reply'] = 'ng';

    $MailLimit->modInfo(); // 正常に送信したことを記録する（連投防止）
    $MailLimit->destroySessionKey(); // セッションキー解除
    session_destroy();

}

function send_mail($array) { $m = $array; unset($array);

    global $b_debug;

    if ($b_debug) var_dump($m);
    
    $orgEncoding = mb_internal_encoding();
//	$mailEncoding = 'ISO-2022-JP';
    $mailEncoding = 'UTF-8';
    $CR = "\n";
    
    $header  = 'From: '.$m['from'].$CR;

    if (!empty($m['cc'])) $header .= 'Cc: '.$m['cc'].$CR;
    
    if (!empty($m['bcc'])) $header .= 'Bcc: '.$m['bcc'].$CR;
    
//	$header .= 'Content-Type: text/plain; charset="'.$mailEncoding.'"'.$CR;
    
    if (isset($m['file'])) {

        // マルチパートメールの区切りIDを設定
        $boundary = md5(uniqid(mt_rand()));
        
        $header .= 'Content-Type: multipart/mixed;'.$CR
                ."\t".'boundary="'.$boundary.'"'.$CR;
        
        $file = file_get_contents($m['file_dir'].$m['file']);
        
        $f_encoded = chunk_split(base64_encode($file)); //エンコードして分割

        $m['body'] =
            "This is a multi-part message in MIME format.\n\n"
            ."--$boundary\n"
            ."Content-Type: text/plain; charset=\"$mailEncoding\"\n\n"
            
            .$m['body'].

            "\n--$boundary\n"
            ."Content-Type: image/".substr($m['file'], -3).";\n"
            ."\tname=\"".$m['file']."\"\n"
            ."Content-Transfer-Encoding: base64\n"
            ."Content-Disposition: attachment;\n"
            ."\tfilename=\"".$m['file']."\"\n\n"
            .$f_encoded
            ."\n"
            ."--$boundary--";
        
    } else {
        $header .= 	'Content-Type: text/plain; charset='.$mailEncoding.$CR.$CR;
    }

    mb_internal_encoding($mailEncoding);
    
    $subject = mb_encode_mimeheader(
        mb_convert_encoding($m['subject'], $mailEncoding, $orgEncoding)
        ,$mailEncoding);
    
    mb_internal_encoding($orgEncoding);
    
    $mailbody = mb_convert_encoding($m['body'], $mailEncoding, $orgEncoding);
    
    return mail($m['to'], $subject, $mailbody, $header);
}

if (count($param['error']) > 0)
    header('location: /contact/index'.http_build_query($param));


$complete_param['product_circle_name']		= $param['circle_name'];
$complete_param['product_circle_name_kana']	= $param['circle_name_kana'];
$complete_param['product_mail_address']		= $param['mail_address'];

$header_query = count($complete_param) ? '?'.http_build_query($complete_param) : '';

if (!$b_debug)
    header('location: /contact/thanks'.$header_query);
*/

?><!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
    <meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">

    <title>お問合せ送信中</title>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/form.css">

    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>

    <script src="/js/warning_freemail.js"></script>
</head>

<body>

<p style="text-align:center">※お待ちください※</p>

<form id="send_params" method="post" action="/contact/thanks">

    <?php
    if (!count($error)):
        
        if (isset($complete_param) && count($complete_param)):
            foreach($complete_param as $key): ?>
                <input type="hidden" name="<?= $key ?>" value="<?= $complete_param[$key] ?? '' ?>">
    <?php
            endforeach;
        endif;
    
    else:
        foreach($key2name as $key => $val):
            if (!empty($$key) && !is_array($kkey)): ?>
                <input type="hidden" name="<?= $key ?>" value="<?= $$key ?? '' ?>">
    <?php
            endif;
        endforeach;
        
        foreach($error as $val): ?>
            <input type="hidden" name="error[]" value="<?= $val ?? '' ?>">
    <?php
        endforeach;
    endif;
    ?>

</form>

<script>
    $('#send_params').submit();
</script>

</body>
</html>