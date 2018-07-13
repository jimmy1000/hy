<?php
/* *
 * 功能：退款处理文件
 * 版本：1.0
 * 日期：2012-03-26
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码。
 */

require_once("lib/pay.Config.php");
require_once("lib/Pay.class.php");

// 请求数据赋值
$data = "";
// 商户APINAME，支付退款申请
$data['service'] = $APINAME_SETTLE_QUERY;
// 商户API版本
$data['version'] = $API_VERSION;
// 商户在支付的平台号
$data['merId'] = $MERCHANT_ID;
// 商户订单号
$data['tradeNo']=$_POST["tradeNo"];
// 商户订单日期
$data['tradeDate']=$_POST["tradeDate"];


// 初始化
$pPay = new Pay($KEY,$GATEWAY_URL);
// 准备待签名数据
$str_to_sign = $pPay->prepareSign($data);
// 数据签名
$sign = $pPay->sign($str_to_sign);
// 准备请求数据
$to_requset = $pPay->prepareRequest($str_to_sign, $sign);
//请求数据
$resultData = $pPay->request($to_requset);



    // 响应吗
    preg_match('{<code>(.*?)</code>}', $resultData, $match);
    $respCode = $match[1];
    // 响应信息
    preg_match('{<desc>(.*?)</desc>}', $resultData, $match);
    $respDesc = $match[1];
    //输出
    echo "响应吗 ".$respCode.'<br>';
    echo "响应信息 ".$respDesc.'<br>';




?>