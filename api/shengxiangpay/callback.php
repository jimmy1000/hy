<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require_once '../../pay_mgr/init.php';
include 'config.php';

//返回数据
$response = $_REQUEST;

//日志
file_put_contents(dirname(__FILE__).'/log', date('Y-m-d H:i:s'). '   |  ' . file_get_contents("php://input") . PHP_EOL,FILE_APPEND);
file_put_contents(dirname(__FILE__).'/log', date('Y-m-d H:i:s'). '   |  ' . var_export($response, true) . PHP_EOL . PHP_EOL,FILE_APPEND);

$order_id = $response['orderid'];
$response_sign = $response['sign'];
$response_sign2 = $response['sign2'];
$money = $response['money'];

if($response['returncode'] != '1' || !$order_id || !$response_sign){
	echo 'ERROR';exit;
}

/*$post_data = array (
	'returncode' => $response['returncode'],     //商户号
	'userid' => $response['userid'],     //订单号
	'orderid' => $order_id,     //状态
);*/

//$sign_key = 'keyvalue';

/*if($sign_key){
    $sign = create_sign($post_data, array($sign_key => $app_key));
}else{
    $sign = create_sign($post_data , $app_key);
}*/

$sign = md5( strtolower("returncode={$response['returncode']}&userid={$app_id}&orderid={$order_id}&keyvalue={$app_key}"));
$sign2 = md5(strtolower("money={$response['money']}&returncode={$response['returncode']}&userid={$app_id}&orderid={$order_id}&keyvalue={$app_key}"));

if ($response_sign == $sign && $response_sign2 == $sign2) {

	$info = $database->get(DB_PREFIX . 'preorder', '*', array('order_id' => $order_id));

	if($info){
		if($response['money'] == $info['order_money']){
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

				echo 'ok';exit;
			}
		}
	}
}

echo 'ERROR';exit;
