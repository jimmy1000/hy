<?php
include 'common.php';

/**
 * 配置文件定义
* User: Administrator
* Date: 2018/1/2 0002
* Time: 12:48
*/

// 商户在支付系统的平台号
$merchant_id = '100120104194656' ;
//秘钥
$key="48ff294b-ee76-459f-a1d6-89e6b4d90732";
$sescrt="d600b3a9-b9c4-4457-9245-dd309f7a5527";



//支付请求
$payRequestUrl = 'http://pay.hezhongpay.com/union/qrcode ';


//异步回调地址
$pay_callbackurl = 'http://payhy.8889s.com/api/hezhongpay/callback.php';

//同步回调地址
$hrefbackurl     = 'http://payhy.8889s.com';