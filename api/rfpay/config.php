<?php
include 'common.php';

/**
 * 配置文件定义
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:48
 */

$config = array(
    'merNo'=>'SM80324150319HND',
    'signKey'=>'7052A42D9F01863CDD53F153762DB217',
    'PayUrl'=>'http://zq.948pay.com:8777/api/pay.action',
);
//异步回调地址
$pay_callbackurl = 'http://payhy.8889s.com/api/rfpay/callback.php';
//同步回调地址
$hrefbackurl     = 'http://payhy.8889s.com';

