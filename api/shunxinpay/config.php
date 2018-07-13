<?php
include 'common.php';

/**
 * 配置文件定义
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:48
 */

// 商户在支付系统的平台号
$merchant_id = '10000080002701' ;
//密钥
$key      = '4dc2c600e3f163c638b59b0e5e3a3dce';
//支付请求地址
//异步回调地址
$pay_callbackurl = 'http://payhy.8889s.com/api/shunxinpay/callback.php';
//同步回调地址
$hrefbackurl     = 'http://payhy.8889s.com';
$purl="http://trade.fjjxjj.com/cgi-bin/netpayment/pay_gate.cgi";
