<?php

include 'common.php';

/**
 * 配置文件定义
*/

// 本地域名
$local_url = 'http://payhy.8889s.com';

// 支付回调接口
$notify_url = $local_url . '/api/zhangtuopay/callback.php';

// 支付成功跳转地址
$return_url = $local_url . '';

// 支付系统平台号
$app_id = '880800206';

// 秘钥
$app_key = 'a496934b6f21a3e445d8ade756946390';

// 支付请求接口
$pay_request_url = 'http://pay.palmrestpay.com/online/gateway.html';

// 支付查询接口
$pay_query_url = 'http://pay.palmrestpay.com/online/gateway.html';


