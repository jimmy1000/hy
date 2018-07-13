<?php

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;

$orderId = $_REQUEST['OrderId'];

if($orderId == ""){
    echo json_encode(array('result' => 0, 'message' => '参数错误'));exit;
}

$infos = $database->get(DB_PREFIX . 'order', '*', array(
	'order_id' => $orderId,
));

if($infos){
	if($infos['order_state'] == 1){
		echo json_encode(array('result' => 1, 'message' => '该笔订单支付成功'));exit;
	}
}

echo json_encode(array('result' => 0, 'message' => '该笔订单未支付成功'));exit;