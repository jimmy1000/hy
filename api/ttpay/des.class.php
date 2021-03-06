<?php
//PHP 版DES 加解密算法类
class DES {
    var $key;
    var $iv; //偏移量

    function __construct($key) {
        //key长度为8位,例如:1234abcd
        $this->key = $key;
        $this->iv = $key; //默认以$key 作为 iv
    }

    //加密
    function encrypt($str) {
        $size = mcrypt_get_block_size ( MCRYPT_DES, MCRYPT_MODE_CBC );
        $str = $this->pkcs5Pad ( $str, $size );
        return strtoupper( bin2hex( mcrypt_encrypt(MCRYPT_DES, $this->key, $str, MCRYPT_MODE_CBC, $this->iv ) ) );
    }

    //解密
    function decrypt($str) {
        $strBin = $this->hex2bin( strtolower($str) );
        $str =  mcrypt_decrypt( MCRYPT_DES, $this->key, $strBin, MCRYPT_MODE_CBC, $this->iv );
        $str = $this->pkcs5Unpad( $str );
        return $str;
    }

    function hex2bin($hexData) {
        $binData = "";
        for($i = 0; $i < strlen ( $hexData ); $i += 2) {
            $binData .= chr ( hexdec ( substr ( $hexData, $i, 2 ) ) );
        }
        return $binData;
    }

    function pkcs5Pad($text, $blocksize) {
        $pad = $blocksize - (strlen ( $text ) % $blocksize);
        return $text . str_repeat ( chr ( $pad ), $pad );
    }

    function pkcs5Unpad($text) {
        $pad = ord ( $text {strlen ( $text ) - 1} );
        if ($pad > strlen ( $text ))
            return false;
        if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)
            return false;
        return substr ( $text, 0, - 1 * $pad );
    }
}
?>  