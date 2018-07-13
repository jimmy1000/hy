<?php
error_reporting(E_ALL^E_NOTICE^E_WARNING);
require_once '../../pay_mgr/init.php';
include 'config.php';

//返回数据  form提交
$response = $_REQUEST;

//日志
file_put_contents(dirname(__FILE__).'/log', date('Y-m-d H:i:s'). '   |  ' . var_export($response, true) . PHP_EOL . PHP_EOL,FILE_APPEND);


$code = '';
if($response['ret']){
	if($ret = json_decode($response['ret'], true)){
		$code = $ret['code'];
	}
}

//订单号
$orderId = '';
if($response['msg']){
	if($msg = json_decode($response['msg'], true)){
		$orderId = $msg['no'];
	}
}

if($code !== '1000' || !$orderId){
	exit;
}


$pu_key = '';

$pu_key = openssl_pkey_get_public($public_key);

$datas = stripslashes($response['ret'].'|'.$response['msg']);
//验签
$txt = openssl_verify($datas, base64_decode($response['sign']),$pu_key);
openssl_free_key($pu_key);

if (1==$txt && $code === '1000' && $orderId) {
	echo 'SUCCESS' ;

	$info = $database->get(DB_PREFIX . 'preorder', '*', array('order_id' => $orderId));

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
			$database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id' => $orderId));

		}
	}
}
?>