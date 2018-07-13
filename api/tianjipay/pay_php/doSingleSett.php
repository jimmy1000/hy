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
$data['service'] = $APINAME_SETTLE;
// 商户API版本
$data['version'] = $API_VERSION;
// 商户在支付的平台号
$data['merId'] = $MERCHANT_ID;
// 商户订单号
$data['tradeNo']=$_POST["tradeNo"];
// 商户订单日期
$data['tradeDate']=$_POST["tradeDate"];
// 结算通知地址
$data['notifyUrl']=$_POST["notifyUrl"];
// 商户参数
$data['extra']=$_POST["extra"];
// 交易摘要
$data['summary']=$_POST["summary"];
// 银行号
$data['bankCardNo']=$_POST["bankCardNo"];
// 开户姓名
$data['bankCardName']=$_POST["bankCardName"];
// 银行卡代码
$data['bankId']=$_POST["bankId"];
// 银行卡开户行名称
$data['bankName']=$_POST["bankName"];
//结算金额
$data['amount']=$_POST["amount"];
//用途
$data['purpose']=$_POST["purpose"];

// 将中文转换为UTF-8
if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['summary']))
{
    $data['summary'] = iconv("GBK","UTF-8", $data['summary']);
}
if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['extra']))
{
    $data['extra'] = iconv("GBK","UTF-8", $data['extra']);
}

if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['purpose']))
{
    $data['purpose'] = iconv("GBK","UTF-8", $data['purpose']);
}


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