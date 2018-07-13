<?php

include 'config.php';
require_once '../../pay_mgr/init.php';



$words = '' ; //支付类型描述
$merchant_code      = $merchant_id;//商户号，123001002003是测试商户号，调试时要更换商家自己的商户号
$service_type       = getPayType($_REQUEST['type'],$words);
$notify_url         = $pay_callbackurl;
$interface_version  = 'V3.1'; //版本号 默认V3.0必须大写
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
$signStr = "";

$signStr = $signStr."client_ip=".$client_ip."&";

if($extend_param != ""){
    $signStr = $signStr."extend_param=".$extend_param."&";
}

if($extra_return_param != ""){
    $signStr = $signStr."extra_return_param=".$extra_return_param."&";
}

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
    'pay_api'     => '飞秒付',
    'pay_url'     => $pay_url
];
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}


$postdata=[
   'extend_param'=>$extend_param,
    'extra_return_param'=>$extra_return_param,
    'product_code'=>$product_code,
    'product_desc'=>$product_desc,
    'product_num'=>$product_num,
    'merchant_code'=>$merchant_code,
    'service_type'=>$service_type,
    'notify_url'=>$notify_url,
    'interface_version'=>$interface_version,
    'sign_type'=>$sign_type,
    'order_no'=>$order_no,
    'client_ip'=>$client_ip,
    'sign'=>$sign,
    'order_time'=>$order_time,
    'order_amount'=>$order_amount,
    'product_name'=>$product_name
];


$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response=curl_exec($ch);
$res=simplexml_load_string($response);
curl_close($ch);

if ($res->response->resp_code !='SUCCESS') {
    exit("<script>alert('网络错误,请重试...');history.go(-1);</script>");
}

if (($res->response->result_code != 0)) {
    exit("<script>alert('网络错误,请重试...');history.go(-1);</script>");
}

$code_url = $res->response->qrcode ; //二维码地址

?>
