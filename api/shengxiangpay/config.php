<?php

include 'common.php';

/**
 * 配置文件定义
*/

// 本地域名
$local_url = 'http://payhy.8889s.com';

// 支付回调接口
$notify_url = $local_url . '/api/shengxiangpay/callback.php';

// 支付成功跳转地址
$return_url = $local_url . '';

// 支付系统平台号
$app_id = 3428;

// 秘钥
$app_key = '1cb65bbae5e24fd6afc5f3e4b4121407';

// 支付请求接口
$pay_request_url = 'http://api.shxpac.com/pay.aspx';

// 支付查询接口
$pay_query_url = 'http://api.shxpac.com/query.aspx';


