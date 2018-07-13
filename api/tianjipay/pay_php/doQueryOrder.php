<?php
/* *
 * 功能：支付订单查询
 * 版本：1.0
 * 日期：2017.1.2
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码。
 */

require_once("lib/pay.Config.php");
require_once("lib/Pay.class.php");

// 请求数据赋值
$data = "";
// 商户APINMAE，WEB渠道一般支付
$data['service'] = $APINAME_QUERY;
// 商户API版本
$data['version'] = $API_VERSION;
// 商户在支付平台的的平台号
$data['merId'] = $MERCHANT_ID;
//商户订单号
$data['tradeNo'] = $_POST["tradeNo"];

// 商户订单日期
$data['tradeDate'] = $_POST["tradeDate"];
// 商户交易金额
$data['amount'] = $_POST["amount"];

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

        //响应描述
        preg_match('{<respCode>(.*?)</respCode>}', $resultData, $match);
        $respCode = $match[1];
        echo "响应描述： ".$respCode.'<br>';
      
        // 支付平台订单日期
        preg_match('{<accDate>(.*?)</accDate>}', $resultData, $match);
        $accDate = $match[1];
        echo "订单日期 ".$accDate.'<br>';
        // 商户订单号
        preg_match('{<orderNo>(.*?)</orderNo>}', $resultData, $match);
        $orderNo = $match[1];
        echo "订单号 ".$orderNo.'<br>';
        // 商户订单状态
        preg_match('{<status>(.*?)</status>}', $resultData, $match);
        $status = $match[1];
        echo "订单号 ".$status.'<br>';
	    //支付订单号
	     preg_match('{<orderNo>(.*?)</orderNo>}', $resultData, $match);
        $orderNo = $match[1];
        echo "订单号 ".$orderNo.'<br>';
	 

?>