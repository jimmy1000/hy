<?php

include 'common.php';

/**
 * 配置文件定义
*/

// 本地域名
$local_url = 'http://payhy.8889s.com';

// 支付回调接口
$notify_url = $local_url . '/api/xunfeifupay/callback.php';

// 支付成功跳转地址
$return_url = $local_url . '';

// 支付系统平台号
$app_id = '161d071c2';

// 秘钥
$app_key = '38d76ed40d460191b051f83afe732e66';

// 支付请求接口
$pay_request_url = 'http://211.149.181.233/v1/pay/dopay';

// 支付查询接口
$pay_query_url = 'http://211.149.181.233/v1/pay/order/query';


