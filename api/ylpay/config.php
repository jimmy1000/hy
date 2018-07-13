<?php
// 
//                                  _oo8oo_
//                                 o8888888o
//                                 88" . "88
//                                 (| -_- |)
//                                 0\  =  /0
//                               ___/'==='\___
//                             .' \\|     |// '.
//                            / \\|||  :  |||// \
//                           / _||||| -:- |||||_ \
//                          |   | \\\  -  /// |   |
//                          | \_|  ''\---/''  |_/ |
//                          \  .-\__  '-'  __/-.  /
//                        ___'. .'  /--.--\  '. .'___
//                     ."" '<  '.___\_<|>_/___.'  >' "".
//                    | | :  `- \`.:`\ _ /`:.`/ -`  : | |
//                    \  \ `-.   \_ __\ /__ _/   .-` /  /
//                =====`-.____`.___ \_____/ ___.`____.-`=====
//                                  `=---=`
// 
// 
//               ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
//                          佛祖保佑         永不宕机/永无bug
// +----------------------------------------------------------------------
// | FileName: config.php
// +----------------------------------------------------------------------
// | CreateDate: 2018年1月16日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
$partnerId = '1815273105016404';
$key =  '-----BEGIN PRIVATE KEY-----
MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAJJLuPWYjQV20U86
fgkpeDcmVENlKraV6pzS2NtBLH6DgkSd9Rf68JicqvGLXXXsQk2BcGpwQF0szrDp
/pqmmY2Cs720T8uC1zUeVzvAvNlN6nIbzrZpqjJPtv0FaoUDeXvs5t5UMpYTqua5
Xha3AOH0Tpt83YqX7JBdlKOJrZczAgMBAAECgYA16waeLb072FDIXIx7H/eYi93e
gPi/Fn3Dksot9NVLQ3jNhVIBppDqDkPKhkzJ22uMWTpvYDO4o30CIxudL+3DdVL7
mLVzbOXeJ6758c8XUc3FusyTmpyApkPrwLL7ZlxIxbeaIiT3uUkMb1o9wTnEjh3C
dGZWt35AV/esJMslYQJBAMLWSDrKdbaLwprIWtXyPFndS2/UPTJsvMO/9v+4Kk2b
6zj9EP9PeeUJpgAvgmpNw3axYP0SQgXQ4V0GQ0Aqk9kCQQDAOINHBInwkiEUHKEF
JmKxkKSZV7zHvbX1DiDfaBPwhZWZbxI5vi+NxsZpHM3gjlgVw+LpAlefY3sLlB6S
DnfrAkAtyT92GWfqOxPDXfwCtQc7swZsRDsRY35EnhMEaxn93Ps9DDN3XAzwJwFM
n92M77NUbEC3mBfN2BVtz+/z302ZAkBrAgrzCi/QYUlQ7O8m8suWljNLunA5sf8N
wSPDuyj+iR9TH2ZCBwJqJvJlqcdi71tSIo67sLYe4T6qn3a1UKCpAkAxWW4nBIoR
czUYPLgqxcRHlLvYLAWhksNjbxAale131ip8u6ClNhTjdJ9uxuIpROVvIwMjQKxK
zFNeI5P7z0Be
-----END PRIVATE KEY-----';
$pub_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDpVTKFavuizwBQEPSLkqpX6seQ
PXFkj9Ry3QCqNn0wy8kijc5tOj0OpThrzyFVIjkBvzwNmZRoNpK2vM7HPWnfkqfF
OFG1y27OoF1VWhyz3EaCUDvFDDqzqhrWqNE7hjsYLrbOl6gzILvI7bql1Gzwq3v2
VdayS2LvkNqsqd/F7QIDAQAB
-----END PUBLIC KEY-----';

$gateWay = 'https://www.ylservice.us/trade/pay/createQrCodePay';
$callback = 'http://payhy.8889s.com/api/ylpay/callback.php';
$hrefback = 'http://payhy.8889s.com/api/ylpay/hrefback.php';

function getISPhone()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    {
        return true;
    }
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
    {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return 1;
        }else{
            return 0;
        }
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT']))
    {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        }
    }
    return false;
}

function create_sign($data,$sign_key){
    $signArr = [];
    foreach ($data as $key=>$value) {
        $signArr[] = $key;
    }
    sort($signArr);
    $signStr = '';
    foreach ($signArr as $i=>$paramKey) {
        if ($paramKey=='signType') { continue ; }
        if ( $i!=0 ) {
            $signStr = $signStr.'&'.$paramKey.'='.$data[$paramKey];
        } else {
            $signStr = $paramKey.'='.$data[$paramKey];
        }
    }
    return get_sign($signStr,$sign_key);
}

function  get_sign($data, $private_key,$code = 'base64'){
    $ret = false;
    if (openssl_sign($data, $ret, $private_key,OPENSSL_ALGO_SHA1)){
        $ret = _encode($ret, $code);
    }
    return $ret;
}

function _encode($data, $code){
    switch (strtolower($code)){
        case 'base64':
            $data = base64_encode(''.$data);
            break;
        case 'hex':
            $data = bin2hex($data);
            break;
        case 'bin':
        default:
    }
    return $data;
}

function check_sign($_public_key)
{
    $param = $_REQUEST;
    unset($param['signType']);
    unset($param['signMsg']);
    if (isset($param['extraCommonParam']) && empty($param['extraCommonParam'])) {
        unset($param['extraCommonParam']) ;
    }
    if (isset($param['s'])) {
        unset($param['s']) ;
    }
    
    $signArr =  [];
    foreach ($param as $key=>$value) {
        $signArr[] = $key;
    }
    sort($signArr);
    $signStr = '';
    
    foreach ($signArr as $i=>$paramKey) {
        if ( $i!=0 ) {
            $signStr = $signStr.'&'.$paramKey.'='.$param[$paramKey];
        } else {
            $signStr = $paramKey.'='.$param[$paramKey];
        }
    }
    
    return _verity($signStr,$_REQUEST['signMsg'],$_public_key) ; //验签
}

function _verity($data, $signature, $publicKey)
{
    $pubKey = $publicKey ;
    $res    = openssl_get_publickey($pubKey) ;
    $result = (bool)openssl_verify($data, base64_decode($signature), $res,OPENSSL_ALGO_SHA1) ;
    openssl_free_key($res) ;
    return $result ;
}
