<?php

include 'common.php';

/**
 * 配置文件定义
*/

// 支付系统平台号
$app_id = '100120104194656';

// 秘钥
$app_key = '48ff294b-ee76-459f-a1d6-89e6b4d90732';
$sescrt="d600b3a9-b9c4-4457-9245-dd309f7a5527";


// 支付请求接口
$pay_request_url = 'http://pay.hezhongpay.com';

// 支付回调接口
$notify_url = 'http://payhy.8889s.com/api/hezhongpay/callback.php';

// 支付成功跳转地址
$return_url = 'http://payhy.8889s.com';