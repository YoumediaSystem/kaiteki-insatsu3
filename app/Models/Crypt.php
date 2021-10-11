<?php

namespace App\Models;

// 暗号化・復号化ライブラリ（openSSL PHP7.2～対応）

class Crypt {
	
	private $method = 'AES-256-CBC'; // necessary vector
//    private $method = 'AES-256-ECB'; // no need vector

    private $key = 'drive_on_kaiteki-insatsu-3';

    protected $option = 0;

    protected $iv = null;

    function __construct($iv = null){

        if (!is_null($iv)) {
            $this->setIV($iv);

        } elseif ($this->method == 'AES-256-CBC') {
            $this->setRandomIV();
        }
    }

    function setRandomIV() {
        $this->iv = openssl_random_pseudo_bytes(16);
    }

    function setIV($iv = null) {

        if (!is_null($iv)) {
        
            if (preg_match('/[^0-9A-Za-z_-]/u',$iv))
                $this->iv = $iv; // raw input
            
            else {
                $base64 = $this->decodeBase64URL($iv);
                $this->iv = base64_decode($base64, true);
            }
        }
    }
    
    function getIV() {
        $iv = base64_encode($this->iv);
        return $this->encodeBase64URL($iv);
    }

    function encryptWithIV($text) {

        if ($this->method == 'AES-256-ECB') return $this->encrypt($text);

        $a = [
            $this->getIV(),
            $this->encrypt($text)
        ];
        return implode('.', $a);
    }
    
    function decryptWithIV($text) {

        if ($this->method == 'AES-256-ECB') return $this->decrypt($text);

        $a = explode('.', $text);

        $this->setIV($a[0]);
        return $this->decrypt($a[1]);
    }
    
    function encrypt($data) {

        $encrypt = openssl_encrypt(
            $data
            ,$this->method
            ,$this->key
            ,$this->option
            ,$this->iv
        );

        return $this->encodeBase64URL($encrypt);
    }

    function decrypt($base64url) {
        
        $base64 = $this->decodeBase64URL($base64url);

        $decrypt = openssl_decrypt(
            $base64
            ,$this->method
            ,$this->key
            ,$this->option
            ,$this->iv
        );

        return $decrypt;

//		return preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $decrypted); // BOM delete
    }

    protected function encodeBase64URL($text) {
        
        $text = str_replace('+','-',$text);
        $text = str_replace('/','_',$text);
        $text = str_replace('=','',$text);
        return $text;
    }

    protected function decodeBase64URL($text) {
    
        $text = str_replace('-','+',$text);
        $text = str_replace('_','/',$text);
        
        $len = strlen($text);
        if ($len % 4 != 0) $len = $len + 4 - ($len % 4);
        
        $text = substr($text.'===', 0, $len);

        return $text;
    }
}