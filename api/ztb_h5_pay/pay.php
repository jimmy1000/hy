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
$order_time         = date('Y-m-d H:i:s');
$order_amount       = sprintf("%.2f",$_REQUEST['coin']); //支付金额保留两位小数
$product_name       = 'online-pay' ; //商品名称
$extra_return_param = $_REQUEST['username'].'-'.$_REQUEST['type']; //备注
$input_charset      ='UTF-8' ;
/////////////////////////////   参数组装  /////////////////////////////////
/**
除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
 */
$signStr= "";

if($client_ip != ""){
    $signStr = $signStr."client_ip=".$client_ip."&";
}
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
if($redo_flag != ""){
    $signStr = $signStr."redo_flag=".$redo_flag."&";
}
if($return_url != ""){
    $signStr = $signStr."return_url=".$return_url."&";
}
$signStr = $signStr."service_type=".$service_type;
if ($show_url != "") {
    $signStr = $signStr."&show_url=".$show_url;
}

/////////////////////////////   RSA-S签名  /////////////////////////////////
$merchant_private_key= openssl_get_privatekey($merchant_private_key);

openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);

$sign = base64_encode($sign_info);

$pay_url = getLocalRequestUrl(); //获取本地支付请求地址

//数据入库
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


?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body onLoad="document.dinpayForm.submit();">
<!--test.wej868.top/pay.php-->
<!--https://pay.ztbaopay.com/gateway?input_charset=UTF-8-->
<form name="dinpayForm" method="post" action="http://test.wej868.top/pay.php" >
    <input type="hidden" name="sign"		     value="<?php echo $sign?>" />
    <input type="hidden" name="merchant_code" value="<?php echo $merchant_code?>" />
    <input type="hidden" name="bank_code"     value="<?php echo $bank_code?>"/>
    <input type="hidden" name="order_no"      value="<?php echo $order_no?>"/>
    <input type="hidden" name="order_amount"  value="<?php echo $order_amount?>"/>
    <input type="hidden" name="service_type"  value="<?php echo $service_type?>"/>
    <input type="hidden" name="input_charset" value="<?php echo $input_charset?>"/>
    <input type="hidden" name="notify_url"    value="<?php echo $notify_url?>">
    <input type="hidden" name="interface_version" value="<?php echo $interface_version?>"/>
    <input type="hidden" name="sign_type"     value="<?php echo $sign_type?>"/>
    <input type="hidden" name="order_time"    value="<?php echo $order_time?>"/>
    <input type="hidden" name="product_name"  value="<?php echo $product_name?>"/>
    <input Type="hidden" Name="client_ip"     value="<?php echo $client_ip?>"/>
    <input Type="hidden" Name="extend_param"  value="<?php echo $extend_param?>"/>
    <input Type="hidden" Name="extra_return_param" value="<?php echo $extra_return_param?>"/>
    <input Type="hidden" Name="pay_type"  value="<?php echo $pay_type?>"/>
    <input Type="hidden" Name="product_code"  value="<?php echo $product_code?>"/>
    <input Type="hidden" Name="product_desc"  value="<?php echo $product_desc?>"/>
    <input Type="hidden" Name="product_num"   value="<?php echo $product_num?>"/>
    <input Type="hidden" Name="return_url"    value="<?php echo $return_url?>"/>
    <input Type="hidden" Name="show_url"      value="<?php echo $show_url?>"/>
    <input Type="hidden" Name="redo_flag"     value="<?php echo $redo_flag?>"/>
    <input Type="hidden" Name="gateWay"     value="https://pay.ztbaopay.com/gateway?input_charset=UTF-8"/>
</form>
</body>
</html>