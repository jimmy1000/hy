<?php

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;

$orderId = $_REQUEST['OrderId'];

if($orderId == ""){
    echo json_encode(array('result' => 0, 'message' => '参数错误'));exit;
}

$postData = Array(
    //支付查询参数

            'merId' => $app_id,				//商户号
            'outOrderId' => $orderId,				//商户订单号
    );


$sign_key = 'key';

if($sign_key){
    $sign = create_sign($postData, array($sign_key => $app_key));
}else{
    $sign = create_sign($postData, $app_key);
}

$postData['sign'] = $sign;

$result = json_decode(postSend($pay_query_url, $postData), true);

if($result['state'] == '1'){
    echo json_encode(array('result' => 1, 'message' => '该笔订单支付成功'));
}
else{
    echo json_encode(array('result' => 0, 'message' => '该笔订单未支付成功'));
}

exit;