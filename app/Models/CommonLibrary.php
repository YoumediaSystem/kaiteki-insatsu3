<?php

namespace App\Models;

class CommonLibrary {

    private $NGwords = [
        '980585.com',
        '115458.com',
        'QQ邮箱',
        '您好',
        '自动回复'
    ];

    function getYoubiArray() {
        return ['(日)','(月)','(火)','(水)','(木)','(金)','(土)'];
    }

    function is_url($url)
    {
        return false !==
            filter_var($url, FILTER_VALIDATE_URL)
        &&	preg_match('@^https?+://@i', $url);
    }
    
    function is_mail($mail_address, $check_dns = null)
    {
        if (is_null($check_dns)) {
            $Config = new \App\Models\Service\Config();
            $check_dns = ($Config->getProtocol() == 'https://');
        }

        switch (true) {
            case false === filter_var($mail_address, FILTER_VALIDATE_EMAIL):
            case !preg_match('/@(?!\[)(.++)\z/', $mail_address, $m):
                return false;
    
            case !$check_dns:
            case checkdnsrr($m[1], 'MX'):
            case checkdnsrr($m[1], 'A'):
            case checkdnsrr($m[1], 'AAAA'):
                return true;
                
            default:
                return false;
        }
    }

    function is_include_NGword($text)
    {
        $b = false;
        
        foreach ($this->NGwords as $needle) {
            
            $b |= (
                mb_strpos($text, $needle, 0, 'UTF-8') !== false);
        }
        return $b;
    }

    function getSelectItemsYMD() {

        $a = [];
        $DT = new \DateTime();
        $a['now_y'] = (int)$DT->format('Y');

        $a['now_datetext'] = $DT->format('Y/n/j');

        $a['select_y'] = range($a['now_y'] - 100, $a['now_y'] + 1);
        $a['select_m'] = range(1,12);
        $a['select_d'] = range(1,31);

        return $a;
    }
    
    function join_date($param, $key = null)
    {
        if (empty($key)) {
            
            if (empty($param['y'])
            ||	empty($param['m'])
            ||	empty($param['d'])
            ) return NULL;
        
            return	 $param['y']
                .'/'.$param['m']
                .'/'.$param['d'];
    
        } elseif (is_string($key)) {
    
            if (empty($param[$key.'_y'])
            ||	empty($param[$key.'_m'])
            ||	empty($param[$key.'_d'])
            ) return NULL;
        
            return	 $param[$key.'_y']
                .'/'.$param[$key.'_m']
                .'/'.$param[$key.'_d'];
        }
        return NULL;
    }
    
    function split_date($date)
    {
        if (mb_substr_count($date, '/', 'UTF-8') < 2)
            return ['','',''];
        
        return explode('/', $date);
    }
    
    function shortHash($string, $len=6, $algo='sha512')
    {
        $hash   = hash($algo, $string);  // ハッシュ値の取得
        $number = hexdec($hash);         // 16進数ハッシュ値を10進数
        $result = dec62th($number);      // 62進数に変換
    
        return substr($result, 0, $len); //$len の長さぶん抜き出し
    }
    
    function dec62th($number)
    {
        // 0-9,a-z,A-Z の 62 文字
        $char = array_merge(
            range('0', '9'),
            range('a', 'z'),
            range('A', 'Z')
        );
        
        return decNth($number, $char);
    }
    
    function decNth($number, array $char)
    {
        $base   = count($char);
        $result = "";
    
        while ($number > 0) {
            $result = $char[ fmod($number, $base) ] . $result;
            $number = floor($number / $base);
        }
    
        return empty($result) ? 0 : $result;
    }

    function getRandomStrings($length = 8)
    {
        return substr(bin2hex(random_bytes($length)), 0, $length);
    }

    function getRandomBytes_base64($length = 32)
    {
        return base64_encode(random_bytes($length));
    }

    function findOutOfJIS1or2($text){

        $rtn = "";

        for($idx = 0; $idx < mb_strlen($text, 'utf-8'); $idx++){

            $str0 = mb_substr($text, $idx, 1, 'utf-8');
            // 1文字をSJISにする。
            $str = mb_convert_encoding($str0, "sjis-win", 'utf-8');

            if ((strlen(bin2hex($str)) / 2) == 1) { // 1バイト文字
                $c = ord($str[0]);
            } else {
                $c = ord($str[0]); // 先頭1バイト
                $c2 = ord($str[1]); // 2バイト目
                $c3 = $c * 0x100 + $c2; // 2バイト分の数値にする。
                if ((($c3 >= 0x8140) && ($c3 <= 0x853D)) || // 2バイト文字
                    (($c3 >= 0x889F) && ($c3 <= 0x988F)) || // 第一水準
                    (($c3 >= 0x9890) && ($c3 <= 0x9FFF)) || // 第二水準
                    (($c3 >= 0xE040) && ($c3 <= 0xEAFF))) { // 第二水準
                } else {
                    $rtn .= $str0;
                }
            }
        }
        return $rtn;
    }

    function httpGetContents($url, $param, $type = 'get') {
		
        $cURL = curl_init($url);
        
        $option = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 9,
            CURLOPT_FOLLOWLOCATION => true, //リダイレクト先を取得
            CURLOPT_MAXREDIRS      => 5, //5回のリダイレクトまで
            CURLOPT_SSL_VERIFYPEER => false, //サーバー証明書の検証を行わない
            CURLOPT_POSTFIELDS     => http_build_query($param)
        ];
        if ($type == 'post') $option[CURLOPT_POST] = true;
        
        curl_setopt_array($cURL, $option);
        $ret = curl_exec($cURL); curl_close($cURL);
            
        return $ret;
    }

    function unicode_unescape($str) {

        if (empty($str) || !is_string($str)) return '';

        $a = json_decode('["'.$str.'"]');

        return $a[0];
    }

    function unicode_escape($str) {

        if (empty($str) || !is_string($str)) return '';

        $t1 = json_encode([$str]);
        $t2 = substr($t1,2);
        $t3 = substr($t2,0,strlen($t2) - 2);

        return $t3;
    }

    function getPagerInfo($param) {

        $page = $param['page'] ?? 0;
        $count_all = $param['count_all'] ?? 0;
        $rows_of_page = $param['rows_of_page'] ?? 10;

        $pager = [
            'page'      => $page,
            'count_all' => $count_all,
            'max_page'  => intval($count_all / $rows_of_page),
            'offset'    => $rows_of_page * $page
        ];
        return $pager;
    }
}