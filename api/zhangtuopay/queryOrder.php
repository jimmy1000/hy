<?php

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;

$orderId = $_REQUEST['OrderId'];

if($orderId == ""){
    echo json_encode(array('result' => 0, 'message' => '参数错误'));exit;
}

$postData = Array(
    //支付查询参数
    'version' => '3.0',				//商户号
    'method' => 'ZT.online.query',				//接口名称
    'partner' => $app_id,				//商户号
    'ordernumber' => $orderId,				//商户订单号
);


$postData['sign'] = md5("version={$postData['version']}method={$postData['method']}&partner={$app_id}&ordernumber={$orderId}&sysnumber=&key={$app_key}");

$result = json_decode(postSend($pay_query_url, $postData), true);
if($result['tradestate'] === '1'){
    echo json_encode(array('result' => 1, 'message' => '该笔订单支付成功'));
}
else{
    echo json_encode(array('result' => 0, 'message' => '该笔订单未支付成功'));
}

exit;