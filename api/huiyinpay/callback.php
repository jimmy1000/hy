<?php

require_once '../../pay_mgr/init.php';
include 'config.php';

//返回数据  form提交
$response = $_REQUEST;

//日志
file_put_contents(dirname(__FILE__).'/log', date('Y-m-d H:i:s'). '   |  ' . file_get_contents("php://input") . PHP_EOL,FILE_APPEND);
file_put_contents(dirname(__FILE__).'/log', date('Y-m-d H:i:s'). '   |  ' . var_export($response, true) . PHP_EOL . PHP_EOL,FILE_APPEND);

$response = array_map('urldecode', $response);

$order_id = $response['orderid'];
$response_sign = $response['sign'];

if($response['status'] != '0' || !$order_id || !$response_sign){
	exit;
}

$post_data = array (
	"merchantcode" => $response['merchantcode'],
	"orderid" => $order_id,
	"status" => $response['status'],
	"amount" => $response['amount'],
	"paytime" => $response['paytime']
);

$sign_key = 'key';

$sign = createSign($response + array($sign_key => $app_key));

if ($response_sign == $sign) {
	echo json_encode(["code" => 'success', "msg" => '成功',]);

	$info = $database->get(DB_PREFIX . 'preorder', '*', array('order_id' => $order_id));

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
			$database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id' => $order_id));

		}
	}
}else{
	echo json_encode(["code" => 'failed', "msg" => '签名失败']);
}