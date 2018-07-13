<?php

include 'common.php';

/**
 * 配置文件定义
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:48
 */
$merchant_id     = '68018027316601' ;//商户ID

$key             = '046809201148GovCFEop' ;//密钥

$url             = 'http://order.paycheng.com/jh-web-order/order/receiveOrder'  ; //PC支付请求地址

$pay_callbackurl = 'http://payhy.8889s.com/api/cftpay/callback.php' ; //异步回调地址

$pay_returnurl   = 'http://payhy.8889s.com' ; //同步回调地址



