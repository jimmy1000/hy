<?php

include 'common.php';

/**
 * 配置文件定义
*/

// 本地域名
$local_url = 'http://payhy.8889s.com';

// 支付回调接口
$notify_url = $local_url . '/api/zhongbaopay/callback.php';

// 支付成功跳转地址
$return_url = $local_url . '';

// 支付系统平台号
$app_id = 88998717;

// 秘钥
$app_key = 'b2629672d73d4510950ed38436c11af6';

//测试账号
//$app_id = 88997766;
//$app_key = '8105404050fe456d83066cd3c9828b07';


// 支付请求接口
$pay_request_url = '';

// 支付查询接口
$pay_query_url = 'https://gateway.zbpay365.com/GateWay/Query';


$pay_query_url_arr = [
	'wap' => 'https://gateway.zbpay365.com/GateWay/Pay', //(网银和直连支付（Wap）POST表单
	'kj' => 'https://gateway.zbpay365.com/FastPay/Index', //快捷支付
	'wxbarcode' => 'https://gateway.zbpay365.com/WxPay/BarCodePay', //微信条码
	'fix' => 'https://gateway.zbpay365.com/FixPay/Index', //固定码支付
];
