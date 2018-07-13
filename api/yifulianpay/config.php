<?php

include 'common.php';

/**
 * 配置文件定义
*/

// 本地域名
$local_url = 'http://payhy.8889s.com';

// 支付回调接口
$notify_url = $local_url . '/api/yifulianpay/callback.php';

// 支付成功跳转地址
$return_url = $local_url . '';

// 支付系统平台号
$app_id = 6608800503;

// 秘钥
$app_key = '08016bda8c23180d71022fd94292ebaf';

// 支付请求接口
$pay_request_url = '';

// 支付查询接口
$pay_query_url = '';

// 
$pay_url = 'http://pay.yflpay.com';

// 
$pay_query_url = $pay_url . '/queryorder';


$pay_query_url_arr = [
            'scan' => $pay_url . '/scanpay', //扫码
            'h5' => $pay_url . '/h5pay', //h5
            'kj' => $pay_url . '/kjpay', //快捷支付
            'wg' => $pay_url . '/b2cpay', //网关
    ];
