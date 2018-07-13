<?php
include 'common.php';

/**
 * 配置文件定义
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:48
 */
$merchant_id     = 'BSS18022417041001' ;//商户ID

$key             = '6e0ece78b05174e8d3ca6e9327c355d0' ;//密钥

$url             = 'https://ebank.baishengpay.com/Payment/Gateway'  ; //PC支付请求地址

$pay_callbackurl = 'http://payhy.8889s.com/api/bspay/callback.php' ; //异步回调地址

$pay_returnurl   = 'http://payhy.8889s.com' ; //同步回调地址
