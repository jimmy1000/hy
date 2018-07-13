<?php
require_once '../../pay_mgr/init.php';
require_once  'config.php' ;


$urlString = $domain_name . '/Pay/ThridPayQuery.aspx';

$orderId = $_REQUEST['OrderId'];

if($orderId == ""){
    echo json_encode(array('result' => 0, 'message' => '参数错误'));exit;
}

$postData = Array(
    'app_id' => $merchant_id,
    'order_id'=> $orderId,
    'time_stamp' => date('YmdHis')
);

$sign = createSign($postData + array('key' => $appKey));

$postData['sign'] = $sign;

$result = json_decode(postSend($urlString, $postData), true);

if($result['status_code'] === 0 && $result['pay_result'] === 20){
    echo json_encode(array('result' => 1, 'message' => '该笔订单支付成功'));
}
else{
    echo json_encode(array('result' => 0, 'message' => '该笔订单未支付成功' . $result->message));
}

exit;

