<?php
require_once '../../pay_mgr/init.php';
require_once  'config.php' ;


$urlString = 'https://gateway.ioo8.com/query/singleOrder';

$orderId = $_REQUEST['OrderId'];

if($orderId == ""){
    echo json_encode(array('result' => 0, 'message' => '参数错误'));exit;
}

$postData = Array(
    'payKey' => $merchant_id,
    'outTradeNo'=> $orderId,
);

ksort($postData);
$sign = createSign($postData + array('paySecret' => $appKey));

$postData['sign'] = $sign;

$result = json_decode(postSend($urlString, $postData), true);

if($result['resultCode'] === '0000' && ($result['orderStatus'] == 'SUCCESS' || $result['orderStatus'] == 'FINISH')){
    echo json_encode(array('result' => 1, 'message' => '该笔订单支付成功'));
}
else{
    echo json_encode(array('result' => 0, 'message' => '该笔订单未支付成功' . $result->message));
}

exit;

