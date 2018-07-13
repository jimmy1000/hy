<?php
include 'common.php';

/**
 * 配置文件定义
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:48
 */
$merchant_id     = '10645580' ;//商户ID

$key             = 'ccf4bc0e2c57ca511751de60f2be75c1' ;//密钥

$url             = 'http://pay.api.tzinformation.com/api/wypay/createOrder'  ; //PC支付请求地址

$pay_callbackurl = 'http://payhy.8889s.com/api/xzfpay/callback.php' ; //异步回调地址

$pay_returnurl   = 'http://payhy.8889s.com' ; //同步回调地址
