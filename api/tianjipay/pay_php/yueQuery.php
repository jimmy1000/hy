<?php
/* *
 * 功能：一般支付处理文件
 * 版本：1.0
 * 日期：2012-03-26
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码。
 */

require_once("lib/pay.Config.php");
require_once("lib/Pay.class.php");

// 请求数据赋值

// 商户APINMAE，WEB渠道一般支付
$data['service'] = $APINAME_BALANCE_QUERY;
// 商户API版本
$data['version'] = $API_VERSION;
// 商户在支付平台的的平台号
$data['merId'] = $MERCHANT_ID;

// 初始化
$pPay = new Pay($KEY,$GATEWAY_URL);
// 准备待签名数据
$str_to_sign = $pPay->prepareSign($data);
// 数据签名

$signMsg = $pPay->sign($str_to_sign);
//var_dump($signMsg);

//return;

//$signMsg='4F0D7B1A8DF615DABE147B1CC112B79C';
$data['sign'] = $signMsg;


// 准备请求数据
$to_requset = $pPay->prepareRequest($str_to_sign, $signMsg);
//请求数据
$resultData = $pPay->request($to_requset);


// 响应吗
preg_match('{<code>(.*?)</code>}', $resultData, $match);
$respCode = $match[1];

// 响应信息
preg_match('{<desc>(.*?)</desc>}', $resultData, $match);
$respDesc = $match[1];


preg_match('{<Amt>(.*?)</Amt>}', $resultData, $match);
$respqrCode= $match[1];



?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>商户支付接口示例 - 查询接口</title>
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
<div class="container">
    <div class="header">
        <h3>支付接口 - 余额请求结果：</h3>
    </div>
    <div class="main">
        <div class="response-info">
            <p>响应码：<?php echo $respCode; ?></p>
            <p>响应描述：<?php echo $respDesc; ?> </p>
            <p>账户余额：<?php echo $respqrCode; ?></p>
        </div>
    </div>
</div>
</body>
</html>
