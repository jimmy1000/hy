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


//支付请求地址
$domain_name = 'http://pay.vzhipay.com';         //请求地址域名
$url         = [
    'zhifubao' => '/Pay/GateWayAliPay.aspx',
    'weixin' => '/Pay/GateWayTencent.aspx',
    'wangyin' => '/Pay/GateWayUnionPay.aspx'
];


//异步回调地址
$pay_callbackurl = 'http://payhy.8889s.com/api/tianyipay/callback.php';

//同步回调地址
$hrefbackurl     = 'http://payhy.8889s.com';