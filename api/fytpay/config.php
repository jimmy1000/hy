<?php
include 'common.php';

/**
 * 配置文件定义
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:48
 */
$merchant_id     = '10206' ;//商户ID

$key             = '8908A780EB7C2372190461EAF8388E8221A8BCCD28927C17' ;//密钥

$url             = 'http://openapi.syshcl.com/api/api_fyt.aspx'  ; //PC支付请求地址

$pay_callbackurl = 'http://payhy.8889s.com/api/fytpay/callback.php' ; //异步回调地址

$pay_returnurl   = 'http://payhy.8889s.com' ; //同步回调地址



