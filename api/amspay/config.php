<?php
include 'common.php';

/**
 * 配置文件定义
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:48
 */
$merchant_id     = '200075' ;//商户ID

$key             = 'MzpL2CmZCs1510470642UZrzz9mUxb2' ;//密钥

$src_code        = 'AMhSwZ1510470642WZPVX' ; //商户唯一标识

$url             = 'http://api.52hrt.com/trade/pay '  ; //PC支付请求地址

$pay_callbackurl = 'http://payhy.8889s.com/api/amspay/callback.php' ; //异步回调地址

$pay_returnurl   = 'http://payhy.8889s.com' ; //同步回调地址
