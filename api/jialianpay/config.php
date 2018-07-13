<?php
include 'common.php';

/**
 * 配置文件定义
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:48
 */

// 商户在支付系统的平台号
$merchant_id = '10058' ;
//密钥
$appKey     = 'dz0tw66jojseou60ew2knvqancnnkodt';
//支付请求地址
$url         = "http://m.jialianjinfu.com/Pay_index";
//异步回调地址
$pay_callbackurl = 'http://payhy.8889s.com/api/jialianpay/callback.php';
//同步回调地址
$hrefbackurl     = 'http://payhy.8889s.com';
