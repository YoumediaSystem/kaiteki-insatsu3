<?php

namespace App\Models\Service;

class SendMail
{
    protected $encoding = 'UTF-8';
    protected $CR = "\n";

    protected $service_name = '';

    protected $admin_mail_address = '';
    protected $bcc_mail_address = '';
    protected $bcc_mail_address_2admin = '';
    protected $bcc_mail_address_2user = '';

	function __construct() {

        $Config = new \App\Models\Service\Config();

        $mail = $Config->getMailAddress();

        $this->admin_mail_address       = $mail['admin_mail_address'];
        $this->bcc_mail_address         = $mail['bcc_mail_address'];
        $this->bcc_mail_address_2admin  = $mail['bcc_mail_address_2admin'];
        $this->bcc_mail_address_2user   = $mail['bcc_mail_address_2user'];

        $this->service_name = $Config->getServiceName();
    }

    function getReport4Admin() {

        $CR = $this->CR;

        $request = \Config\Services::request();        
        $server = $request->getServer();

        $referer = !empty($server['HTTP_X_FORWARDED_FOR'])
            ? $server['HTTP_X_FORWARDED_FOR']
            : ($server['HTTP_REFERER'] ?? '');

        $text = '';

        $text .= "---------------------------------------------------------------".$CR;
        $text .= "Processed       : ".date("Y/m/d (D) H:i:s").$CR;
        $text .= "Server-Name     : ".$server['SERVER_NAME'].$CR;
        $text .= "Script-Name     : ".$server['PHP_SELF'].$CR;
        $text .= "HTTP-Referer    : ".$referer.$CR;
        $text .= "HTTP-User-Agent : ".$server['HTTP_USER_AGENT'].$CR;
        $text .= "---------------------------------------------------------------".$CR;

        return $text;
    }

    function addMailBuffer($param) {

        if (empty($param['mail_address'])
        ||  empty($param['subject'])
        ||  empty($param['body'])
        ) return false;

        $MailBuffer = new \App\Models\DB\MailBuffer();
        $data = $MailBuffer->makeData($param);
        $MailBuffer->insert($data);
        return true;
    }

    /*
    SendMail->send($param):
        from
        to
        bcc
        subject
        body
    ※ファイル添付未対応
    */

    function sendMail($param) {

        $orgEncoding = mb_internal_encoding();
        $mailEncoding = $this->encoding;
        $CR = $this->CR;

        $Email = \Config\Services::email();
        
        $Email->setFrom($param['from']);
        $Email->setTo($param['to']);
        $Email->setBCC($param['bcc']);
//        $Email->setCC('another@another-example.com');

        $Email->setSubject($param['subject']);
        $Email->setMessage($param['body']);

        return $Email->send();

/*
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
*/
    }
}