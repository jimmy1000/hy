<?php

include 'common.php';

/**
 * 配置文件定义
*/

// 本地域名
$local_url = 'http://payhy.8889s.com';

// 支付回调接口
$notify_url = $local_url . '/api/yunfumapay/callback.php';

// 支付成功跳转地址
$return_url = $local_url . '';

// 支付系统平台号
$app_id = 10907;

// 秘钥
$app_key = '78f14db54fdbbe6980e4ad6777e13462d3700b64';

// 支付请求接口
$pay_request_url = 'http://pay.7zcyl.com/apisubmit';

// 支付查询接口
$pay_query_url = 'http://pay.7zcyl.com/apiorderquery';


