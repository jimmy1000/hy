<?php

include 'common.php';
require_once '../../pay_mgr/init.php';
require_once  './merchant.php' ;

$words = '' ; //支付类型描述

$merchant_code      = $merchant_id;//商户号，123001002003是测试商户号，调试时要更换商家自己的商户号
$service_type       = getPayType($_REQUEST['type'],$words);     //微信：weixin_scan 支付宝：alipay_scan qq钱包：tenpay_scan
$notify_url         = $pay_callbackurl;
$interface_version  = 'V3.0'; //版本号 默认V3.0必须大写
$client_ip          = get_client_ip();
$sign_type          = 'RSA-S'; //签名方式
$order_no           = date("YmdHis").rand(100000,999999) ; //订单号;
$order_time         = date('Y-m-d H:i:s') ;
$order_amount       = sprintf("%.2f",$_REQUEST['coin']) ; //支付金额保留两位小数
$product_name       = 'online-pay' ; //商品名称
$extra_return_param = $_REQUEST['username'].'-'.$_REQUEST['type'] ; //备注
$input_charset      = 'UTF-8' ;
$auth_code          = $_REQUEST['payCode'] ; //二维码 授权码

/////////////////////////////   参数组装  /////////////////////////////////
/**
  除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
 */
$signStr = "";

$signStr = $signStr."auth_code=".$auth_code."&";
$signStr = $signStr."client_ip=".$client_ip."&";
if($extend_param != ""){
    $signStr = $signStr."extend_param=".$extend_param."&";
}
if($extra_return_param != ""){
    $signStr = $signStr."extra_return_param=".$extra_return_param."&";
}

$signStr = $signStr."input_charset=".$input_charset."&";
$signStr = $signStr."interface_version=".$interface_version."&";
$signStr = $signStr."merchant_code=".$merchant_code."&";
$signStr = $signStr."notify_url=".$notify_url."&";
$signStr = $signStr."order_amount=".$order_amount."&";
$signStr = $signStr."order_no=".$order_no."&";
$signStr = $signStr."order_time=".$order_time."&";

if($product_code != ""){
    $signStr = $signStr."product_code=".$product_code."&";
}
if($product_desc != ""){
    $signStr = $signStr."product_desc=".$product_desc."&";
}
$signStr = $signStr."product_name=".$product_name."&";
if($product_num != ""){
    $signStr = $signStr."product_num=".$product_num."&";
}
$signStr = $signStr."service_type=".$service_type;


/////////////////////////////   RSA-S签名  /////////////////////////////////
$merchant_private_key= openssl_get_privatekey($merchant_private_key);

openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);

$sign = base64_encode($sign_info);


//数据入库
$pay_url = getLocalRequestUrl(); //获取本地支付请求地址
$insertArr = [
    'order_id'    => $order_no,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $sign,
    'order_money' => $order_amount,
    'order_time'  => time(),
    'pay_api'     => '智通宝支付',
    'pay_url'     => $pay_url
];
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}


/////////////////////////  提交参数到智通宝网关  ////////////////////////
//curl方法提交支付参数到网关https://api.ztbaopay.com/gateway/api/scanpay，并且获取返回值
$postdata = array(
    'auth_code'    => $auth_code,
    'extend_param' => $extend_param,
    'extra_return_param' => $extra_return_param,
    'product_code' => $product_code,
    'product_desc' => $product_desc,
    'product_num'  => $product_num,
    'merchant_code'=> $merchant_code,
    'service_type' => $service_type,
    'notify_url'   => $notify_url,
    'interface_version' => $interface_version,
    'input_charset'     => $input_charset,
    'sign_type'   => $sign_type,
    'order_no'    => $order_no,
    'client_ip'   => $client_ip,
    'sign'        => $sign,
    'order_time'  => $order_time,
    'order_amount'=> $order_amount,
    'product_name'=> $product_name
);

$res = sendDataByCurl($url,$postdata) ; //反扫付款请求

$webSite = getSiteUrl(); //获取当前域名

//返回处理
if ( ($res['response']['resp_code'] =='SUCCESS') && ($res['response']['result_code']==1) ) {
    //查询支付是否成功
    $queryUrl                  = 'https://query.ztbaopay.com/query' ;
    $data['service_type']      = 'single_trade_query' ; //业务类型,固定值
    $data['merchant_code']     =  $merchant_id;         //商家ID
    $data['interface_version'] = 'V3.0'  ;              //版本号
    $data['sign_type']         = 'RSA-S' ;              //签名类型(不参与签名)
    $data['order_no']          = $res['response']['order_no'] ;//订单ID

    //签名处理
    $signStr = create_query_sign($data) ;
    openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5) ;
    $data['sign']             = base64_encode($sign_info);//签名
    $query_res                = sendDataByCurl($queryUrl,$data) ;

    //返回判定
    if ( ($query_res['response']['is_success']=='T') && $query_res['response']['trade']['trade_status']=='SUCCESS') {
        exit ("<script>alert('支付成功...');window.location.href='".$webSite."';</script>");
    } else {
        exit ("<script>alert('支付失败,请检查您的付款码后重试...');window.location.href='".$webSite."';</script>");
    }

} else {

    exit ("<script>alert('支付失败,请检查您的付款码后重试...');window.location.href='".$webSite."';</script>");
}
die;