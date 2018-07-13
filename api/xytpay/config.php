<?php
include 'common.php';

/**
 * 配置文件定义
* User: Administrator
* Date: 2018/1/2 0002
* Time: 12:48
*/

// 商户在支付系统的平台号
$merchant_id = 2571 ;
//密钥
$appKey      = md5('f8f4aff59aac46a09330723b5e9d29ff');

//测试账号
$merchant_id = 1021 ;
$appKey      = md5('9ddd6087698a41c289b06599dd1cbeb2');


//扫描支付请求
$scanPayRequestUrl = 'https://gateway.ioo8.com/scanPay/initPay';
//网银支付请求
$bankPayRequestUrl = 'https://gateway.ioo8.com/b2cPay/initPay';


//异步回调地址
$pay_callbackurl = 'http://payhy.8889s.com/api/xytpay/callback.php';

//同步回调地址
$hrefbackurl     = 'http://payhy.8889s.com';