<?php

include 'common.php';

/**
 * 配置文件定义
 */

// 本地域名
$local_url = 'http://payhy.8889s.com';

// 商户在支付系统的平台号
$app_id = '900100093' ;

//密钥
$appKey      = 'B66351F434444967A6ECEECF65393B3F';

// 接口商域名
$agent_url = 'https://www.aloopay.com/v1/api';

// 支付回调接口
$notify_url = $local_url . '/api/tudoupay/callback.php';

// 支付成功跳转地址
$return_url = $local_url . '';

// 支付查询接口
$pay_query_url = $agent_url . '/scancode/query';

$pay_query_url_arr = [
	'scan' => $agent_url . '/scancode/pay', //扫码
	'h5' => $agent_url . '/h5/pay', //h5
	'bank' => $agent_url . '/ebank/pay', //bank
];
