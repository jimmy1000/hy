<?php
include 'common.php';

/**
 * 配置文件定义
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:48
 */

// 商户号
$merchant_id = 'MERCOONT8967371352925520914' ;

//商户密钥
$pay_key     = "pC7QYrsRFFISnPbi" ;

//签名KEY
$tardeCode     = "qFIYUFEUoOqosHa3" ;

//支付请求地址
$pay_url        = "http://api.easypay188.com/externalSendPay/rechargepay.do";

//异步回调地址
$pay_callbackurl = 'http://pay-900.com/api/ydpay/callback.php';

//同步回调地址
$hrefbackurl     = 'http://pay-900.com';

