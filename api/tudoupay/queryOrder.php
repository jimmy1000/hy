<?php
require_once '../../pay_mgr/init.php';
require_once  'config.php' ;
//require_once("./MobaoPay.class.php");


$urlString = "https://www.aloopay.com/v1/api/scancode/query";

$orderId = $_REQUEST['OrderId'];

if($orderId == ""){
	echo json_encode(array('result' => 0, 'message' => '参数错误'));exit;
}

$signStr = "";
$signStr = $signStr . "interfaceVersion=" . $tudoupay_api_version . "&";
$signStr = $signStr . "merchantCode=" . $merchant_id . "&";
$signStr = $signStr . "orderId=" . $orderId . "&";
$signStr = $signStr . "key=" . $appKey ;

$sign = strtoupper(md5($signStr));

$postData = Array(
	"interfaceVersion"=>$tudoupay_api_version,
	"merchantCode"=>$merchant_id,
	"orderId"=>$orderId,
	"sign" => $sign
);
$result = json_decode(postSend($urlString, $postData));

if($result->status == "SUCCESS"){
	echo json_encode(array('result' => 1, 'message' => '该笔订单支付成功'));
}
else{
	echo json_encode(array('result' => 0, 'message' => '该笔订单未支付成功' . $result->message));
}

exit;

