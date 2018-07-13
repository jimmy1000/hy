//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require_once '../../pay_mgr/init.php';
include 'config.php';

//返回数据
<?php if($global['return_data_is_json'] == 0): ?>
$response = $_REQUEST;
<?php else: ?>
$response = array_key_exists('HTTP_RAW_POST_DATA', $GLOBALS) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input") ;
$response = json_decode($response, true);
<?php endif; ?>

//日志
file_put_contents(dirname(__FILE__).'/log', date('Y-m-d H:i:s'). '   |  ' . file_get_contents("php://input") . PHP_EOL,FILE_APPEND);
file_put_contents(dirname(__FILE__).'/log', date('Y-m-d H:i:s'). '   |  ' . var_export($response, true) . PHP_EOL . PHP_EOL,FILE_APPEND);

$order_id = $response<?= input_value(':' . $global['return_order_value']) ?>;
$response_sign = $response['<?= $global['sign_url_key'] ?>'];

if($response<?= input_value(':' . $global['re_succeed_key']) ?> != '<?= $global['re_succeed_value'] ?>' || !$order_id || !$response_sign){
	exit;
}

$post_data = array (
    <?php foreach($global['return_name'] as $key => $val): ?>
    <?php if($val == '') continue; ?>
    '<?= $val ?>' => <?= input_value($global['return_value'][$key]) ?>,     //<?= $global['return_note'][$key] . PHP_EOL ?>
    <?php endforeach; ?>
);

$sign_key = '<?= $global['sign_str_key'] ?>';

if($sign_key){
    $sign = create_sign($post_data, array($sign_key => $app_key));
}else{
    $sign = create_sign($post_data , $app_key);
}

if ($response_sign == $sign) {
	echo <?= input_value($global['return_succeed_value']) ?>;

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
    <?php if($global['return_error_value']): ?>
    echo <?= input_value($global['return_error_value']) ?>;
    <?php else: ?>
    file_put_contents(dirname(__FILE__).'/log', date('Y-m-d H:i:s'). '  sign错误 ' . get_client_ip() . '|  ' . var_export($response, true) . PHP_EOL . PHP_EOL,FILE_APPEND);
    <?php endif; ?>
}