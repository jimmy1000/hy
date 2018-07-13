<?php

include 'common.php';

/**
 * 配置文件定义
*/

// 本地域名
$local_url = 'http://payhy.8889s.com';

// 支付回调接口
$notify_url = $local_url . '/api/yintongbaopay/callback.php';

// 支付成功跳转地址
$return_url = $local_url . '';

// 支付系统平台号
$app_id = 10062;

// 秘钥
$app_key = 'yjwotzxgwa6o2zjg32ik0lg3y04ag8jq';


// 支付域名
$pay_url = 'http://www.ytbpay.com';

// 支付请求接口
$pay_request_url = $pay_url . '/Pay_Index.html';

// 支付查询接口
$pay_query_url = $pay_url . '/Pay_Trade_query.html';



