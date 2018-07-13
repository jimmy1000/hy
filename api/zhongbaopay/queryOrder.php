<?php

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;

$orderId = $_REQUEST['OrderId'];

if($orderId == ""){
    echo json_encode(array('result' => 0, 'message' => '参数错误'));exit;
}

$postData = Array(
    //支付查询参数
	'orderid' => $orderId,				//商户订单号
	'merchantid' => $app_id,				//商户号
);


$sign_key = 'key';

if($sign_key){
    $sign = create_sign($postData, array($sign_key => $app_key));
}else{
    $sign = create_sign($postData, $app_key);
}

$postData['sign'] = $sign;

if($postData != ''){
	$pay_query_url .= '?' . http_build_query($postData);
}
$result = json_decode(file_get_contents($pay_query_url), true);

if($result['code'] == 0){
	if($result['obj']['result'] == 1){
		echo json_encode(array('result' => 1, 'message' => '该笔订单支付成功'));exit;
	}
}

echo json_encode(array('result' => 0, 'message' => '该笔订单未支付成功'));exit;