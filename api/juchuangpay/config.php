<?php

include 'common.php';

/**
 * 配置文件定义
*/

// 支付系统平台号
$app_id = 'jc00000525';

// 秘钥
$app_key = 'j00zcrOaLtB0Nf0u4AA0Rn7e7tdhSrZV';

// 支付请求接口
$pay_request_url = '';

// 支付查询接口
$pay_query_url = '';

// 本地域名
$local_url = 'http://payhy.8889s.com';

// 支付回调接口
$notify_url = $local_url . '/api/juchuangpay/callback.php';

// 支付成功跳转地址
$return_url = $local_url . '';

$pay_query_url_arr = [
	'qq' => 'http://www.mdgctz.com/jczf/public/index.php/yimei/pay/pay', // QQ、银联  请求接口
	'ali' => 'http://www.mdgctz.com/jczf/public/index.php/yimei/pay/alipay', // 支付宝  请求接口
	'kj' => 'http://www.mdgctz.com/jczf/public/index.php/yimei/pay/kjpay',	// 快捷支付  请求接口
];

$coinArr = [
	20.00,
	30.00,
	40.00,
	50.00,
	60.00,
	80.00,
	90.00,
	100.00,
	120.00,
	140.00,
	150.00,
	160.00,
	180.00,
	200.00,
	210.00,
	220.00,
	240.00,
	250.00,
	260.00,
	270.00,
	280.00,
	300.00
];