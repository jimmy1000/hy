<?php

include 'common.php';

/**
 * 配置文件定义
*/

// 本地域名
$local_url = 'http://payhy.8889s.com';

// 支付回调接口
$notify_url = $local_url . '/api/zspay/callback.php';

// 支付成功跳转地址
$return_url = $local_url . '';

// 支付系统平台号
$app_id = 1000002483;

// 秘钥
$app_key = '08bd9b70-2a9c-4201-9c0a-9edb8fa18db7';

// b2c接口
$pay_bank_url = 'http://gateway.clpayment.com/ebank/pay.do';

// 扫码接口
$pay_scan_url = 'http://gateway.clpayment.com/scan/entrance.do';


