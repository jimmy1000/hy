<?php

require_once '../../pay_mgr/init.php';
include 'config.php';

//返回数据  form提交
$response = $_REQUEST;

//日志
file_put_contents(dirname(__FILE__).'/log', date('Y-m-d H:i:s'). '   |  ' . file_get_contents("php://input") . PHP_EOL,FILE_APPEND);
file_put_contents(dirname(__FILE__).'/log', date('Y-m-d H:i:s'). '   |  ' . var_export($response, true) . PHP_EOL . PHP_EOL,FILE_APPEND);


if($response['tradeStatus'] == 'WAITING_PAYMENT'){
	exit;
}

if ($response['tradeStatus'] != 'SUCCESS' && $response['tradeStatus'] != 'FINISH') {
	echo 'ERROR';exit;
}

$responseSign = $response['sign'];
unset($response['sign']);

ksort($response);

$sign = createSign($response + array('paySecret' => $appKey));

if ($responseSign == $sign) {
	echo 'SUCCESS' ;

	$info = $database->get(DB_PREFIX . 'preorder', '*', array('order_id' => $response['outTradeNo']));

	if($info){
		$infos = $database->get(DB_PREFIX . 'order', '*', array(
			'order_id' => $info['order_id'],
		));

		if(!$infos){
			$insertArr = array(
				'order_id' => $info['order_id'],
				'user_name' => $info['user_name'],
				'order_money' => $info['order_money'],
				'order_time' => time(),
				'order_state' => 1,
				'state' => 0,
				'pay_type' => $info['pay_type'],
				'pay_api' => $info['pay_api'],
				'pay_order' => $info['order_id'],
			);
			$updateArr = array(
				'notify_ip' => get_client_ip(),
				'notify_time' => date('Y-m-d H:i:s'),
			);
			$database->insert(DB_PREFIX . 'order', $insertArr);
			$database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id' => $response['outTradeNo']));

		}
	}
}

?>