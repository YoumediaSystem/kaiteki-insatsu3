<?php

namespace App\Models\Service;

class MailLimit {
    
    private $_key;
    private $_limit_time;
    private $_expire;

    private $emergency_member  = [
        'yamato-reiv_801002@ezweb.ne.jp'
    ];

    private $NGwords  = [
        '980585.com',
        '115458.com',
        'QQ邮箱',
        '您好',
        '自动回复'
    ];

    private $traffic_sampling	= 600;
    private $traffic_border		= 5;
    private $trafficLogFile		= '';
    
    private $formName = 'スタジオYOU';
    
    private $now;
    
    function __construct() {
        
        session_start();
        
        $this->_key = $this->_getKey();
        
        $this->_limit_time	= 60;// メールの再送を禁止する時間（秒）
        
        $this->_expire		= time() + 86400 * 7;// Cookieの有効期限　一応設定する

//		$this->emergency_member	= $this->initEmergencyMember();
        $this->NGwords			= $this->initNGwords();
        
        $DT = new \DateTime();
        $this->now = (int)$DT->format('U');
    }
    
    // NGワード設定を読み込む
    
    public function initNGwords() {

        return (array)$this->NGwords;
    }
    
    // セッションキー生成
    
    public function makeSessionKey() {
        
        $session_key = 'youyou_'.microtime().'_'.mt_rand();
        
        $_SESSION['key'] = $session_key;
        
        return $session_key;
    }
    
    //  セッションキーが同じか
    
    public function checkSessionKey($session_key) {
        
        return (!empty($_SESSION['key']) && !empty($session_key) && $_SESSION['key'] == $session_key);
    }
    
    // セッションキー削除
    
    public function destroySessionKey() {
        
        unset($_SESSION['key']);
    }
    
    // メール連投ではないかチェック
    
    public function isSendable() {
        
        if (!isset($_COOKIE[$this->_key])) return true; // Cookieが作成されていない or 期限切れの場合は送信可
        
        return ( time() > intval($_COOKIE[$this->_key]) + $this->_limit_time );
    }

    // Cookieが使えるか
    
    public function isCookie() {
        
        if (isset($_COOKIE['test']) && !empty($_COOKIE['test'])) return true;
        
        return (bool) setcookie('test', 'test', $this->_expire);
    }
    
    // メールを送信したタイムスタンプ情報を記録する
    
    public function modInfo() {
        
        setcookie($this->_key, time(), $this->_expire);
    }
    
    // Cookieキー
    
    private function _getKey() { // JavaScriptで管理する場合、下記と同じ処理が必要
        
        return str_replace(
            array('/', '=', ';', '.php')
            , array('_', '||', ':', '')
            , $_SERVER["REQUEST_URI"]
        );
    }

    // --- お問合せ送信時に、短期間のメール送信状況チェック
    
    public function initTrafficLogFile($file_place) {
        
        $this->trafficLogFile = $file_place;
    }
    
    public function initFromName($text) {
        
        $this->formName = $text;
    }
    
    public function checkTraffic($mail) {
        
        $logFile = $this->trafficLogFile;
        if (empty($logFile)) return false;
        
        $count = 1;
        $CR = "\n"; $delimiter = '[,]';
        
        $t = file_get_contents($logFile);
        $res = [];
        
        if (mb_strlen($t, 'UTF-8')) {
            
            $a1 = explode($CR, $t);
            $alert_already_send = false;
            
            foreach ($a1 as $val) {
                
                $a2 = explode($delimiter, $val);
                
                    // ○秒以内のデータのみ継続保存
                    if ($this->now - $this->traffic_sampling <= $a2[0]) {
                        $res[] = $val;
                    
                    // アラート送信以外の件数カウント
                    if ($a2[1] != '*alert*') {
                        $count++;
                        
                    } else {
                        $alert_already_send = true;
                    }
                }
            }
            
            // ○秒以内の送信履歴がしきい値を超える場合はシステムアラート送信
            if ($this->traffic_border <= $count && !$alert_already_send) {
                
                $send = [];
                $send['from']		= 'no-reply@youyou.co.jp';
                $send['subject']	= '['.$this->formName.']お問合せ件数異常';
                $send['body']		=
                        'お問合せ件数が異常です（'
                        .$count.'件/'.($this->traffic_sampling / 60).'分間）'.$CR
                        .'※内容確認・NGワード更新要※';
                $send['to'] = implode(',', $this->emergency_member);
                $this->sendMail($send);
                
                $res[] = $this->now.$delimiter.'*alert*';
            }
        }
        
        // トラフィック履歴更新　○秒以前のデータは削除
        $res[] = $this->now.$delimiter.$mail;
        
        file_put_contents($logFile, implode($CR, $res));
    }
    
    function sendMail($array) { $m = $array; unset($array);
        
        $orgEncoding = mb_internal_encoding();
        $mailEncoding = 'ISO-2022-JP';
        $CR = "\r\n";
        
        $header  = 'From: '.$m['from'].$CR;
    
        if (!empty($m['cc'])) $header .= 'Cc: '.$m['cc'].$CR;
        
        if (!empty($m['bcc'])) $header .= 'Bcc: '.$m['bcc'].$CR;
        
        $header .= 'Content-Type: text/plain; charset="'.$mailEncoding.'"'.$CR;
        $header .= 'Content-Transfer-Encoding: 7bit';
        
        mb_internal_encoding($mailEncoding);
        
        $subject = mb_encode_mimeheader(
            mb_convert_encoding($m['subject'], $mailEncoding, $orgEncoding)
            ,$mailEncoding);
        
        mb_internal_encoding($orgEncoding);
        
        $mailbody = mb_convert_encoding($m['body'], $mailEncoding, $orgEncoding);
        
        return mail($m['to'], $subject, $mailbody, $header);
    }
}
