<?php

include 'common.php';

/**
 * 配置文件定义
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:48
 */
$merchant_id     = '1602' ;//商户ID

$key             = 'CrLU7l8hHkdqYuVTsMfSEiDjmvnJvAta' ;//密钥

$url             = 'http://www.vovpay.com/GateWay/ReceiveBank.aspx'  ; //PC支付请求地址

$pay_callbackurl = 'http://payhy.8889s.com/api/wfbpay/callback.php' ; //异步回调地址

$pay_returnurl   = 'http://payhy.8889s.com' ; //同步回调地址



