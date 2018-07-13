<?php

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;
require_once("./MobaoPay.class.php");

// 优 米 付
$words = '' ;
$data  = array(
    'apiName'     => get_api_name(), //WAP方式：“WAP_PAY_B2C”（手机支付） WEB方式：“WEB_PAY_B2C”（pc浏览器
    'apiVersion'  => $jiufupay_api_version, //版本号
    'platformID'  => $merchant_id, //商户ID
    'merchNo'     => $merchant_id, //商户账号
    'orderNo'     => date("YmdHis").rand(100000,999999), //订单号
    'tradeDate'   => date("Ymd"), //交易日期
    'amt'         => sprintf('%.2f',$_REQUEST['coin']), //交易金额
    'merchUrl'    => $pay_callbackurl, //回调地址
    'merchParam'  => $_REQUEST['username'].'_'.$_REQUEST['type'], //参数
    'tradeSummary'=> 'pay', //交易说明
    'customerIP'  => get_client_ip(), //客户端IP地址
   'choosePayType' => getPayType() ,//支付类型
) ;

$cMbPay = new MbPay($appKey,$url) ;
// 准备待签名数据
$str_to_sign = $cMbPay->prepareSign($data);
// 数据签名
$sign = $cMbPay->sign($str_to_sign);
$data['signMsg'] = $sign ;

//银联支付需要补充银行类型对应的编码
if($data['choosePayType']==1 && $_REQUEST['type'] !='BANKWAP') {
    $data['bankCode'] = getBankCode() ;
}


//数据入库
$insertArr = [
    'order_id'    => $data['orderNo'] ,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $data['sig'],
    'order_money' => $data['amt'],
    'order_time'  => time() ,
    'pay_api'     => '优米付',
    'pay_url'     => getLocalRequestUrl() , //获取客户支付地址
] ;
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}
// 提交请求
$html = submitFormMethod($data,$url);
dd($html) ;
?>

