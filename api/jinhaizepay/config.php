<?php

include 'common.php';

/**
 * 配置文件定义
*/

// 支付系统平台号
$app_id = '1371';

// 秘钥
$app_key = 'B33C09C7B55448C9A8065F98B2281BD7';

//测试
//$app_id = '102';
//$app_key = 'D7F7E880EF9E48488F166E9AC5F1BA30';

// 支付请求接口
$pay_request_url = 'http://api.huiyin-pay.com/orderpay/pay';
$pay_request_url = 'http://api.huiyin-pay.com/api/pay/PayOrder';

// 支付查询接口
$pay_query_url = 'http://api.huiyin-pay.com/api/query/querystatus';

// 支付回调接口
$notify_url = 'http://payhy.8889s.com/api/huiyinpay/callback.php';

// 支付成功跳转地址
$return_url = 'http://payhy.8889s.com';