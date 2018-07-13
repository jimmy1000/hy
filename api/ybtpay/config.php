<?php
include 'common.php';

/**
 * 配置文件定义
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:48
 */

// 商户在支付系统的平台号
$merchant_id = '2018050026' ;

//商户唯一标识
$uniqueness  = 'tWfJM' ;

//商户密钥
$pay_key     = "mAB2pTRDqfKkt36" ;

//支付请求地址
$pay_url        = "http://103.84.47.121:8080/YBT/YBTPAY";

//订单查询地址
$pay_query        = "http://103.84.47.121:8080/YBT/YBTQUERY";

//异步回调地址
$pay_callbackurl = 'http://payhy.8889s.com/api/ybtpay/callback.php';

//同步回调地址
$hrefbackurl     = 'http://payhy.8889s.com';

