<?php
include 'common.php';

/**
 * 配置文件定义
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:48
 */

// 商户在支付系统的平台号
$merchant_id = '856086110012949' ;
//密钥
$appKey      = 'ba0062b80a3eed6bdec92e05f6f42510';
//支付请求地址
$url         = "http://cashier.youmifu.com/cgi-bin/netpayment/pay_gate.cgi";
//异步回调地址
$pay_callbackurl = 'http://payhy.8889s.com/api/ymfpay/callback.php';
//同步回调地址
$hrefbackurl     = 'http://payhy.8889s.com';
// 商户APINAME，支付系统退款申请
$mobaopay_apiname_refund = "MOBO_TRAN_RETURN";
// 商户API版本
$jiufupay_api_version = "1.0.0.1";

