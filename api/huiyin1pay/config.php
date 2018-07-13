<?php

include 'common.php';

/**
 * 配置文件定义
*/

// 支付系统平台号
$app_id = '102';

// 秘钥
$app_key = '523AB4F116A74B6D815E72A2647CBC60';

//测试
//$app_id = '102';
//$app_key = 'D7F7E880EF9E48488F166E9AC5F1BA30';

// 支付请求接口
$pay_request_url = 'http://api.huiyin-pay.com/orderpay/pay';
$pay_request_url = 'http://api.huiyin-pay.com/api/pay/PayOrder';

// 支付查询接口
$pay_query_url = 'http://api.huiyin-pay.com/api/query/querystatus';

// 支付回调接口
$notify_url = 'http://45.249.93.135/call/hyhypay.php';

// 支付成功跳转地址
$return_url = 'https://pay1.zf590.com';