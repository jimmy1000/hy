<?php

include 'common.php';

/**
 * 配置文件定义
*/

// 本地域名
$local_url = 'http://payhy.8889s.com';

// 支付回调接口
$notify_url = $local_url . '/api/686pay/callback.php';

// 支付成功跳转地址
$return_url = $local_url . '';

// 支付系统平台号
$app_id = '686cz0641';

// 秘钥
$app_key = 'lLptVDYysDAOFW5jrma5sbakFyyTEj4S';

// 支付请求接口
$pay_request_url = 'http://47.107.21.70/index.php/686cz/trade/';

// 支付查询接口
$pay_query_url = '';


