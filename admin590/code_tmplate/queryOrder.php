require_once '../../pay_mgr/init.php';
require_once  'config.php' ;

$orderId = $_REQUEST['OrderId'];

if($orderId == ""){
    echo json_encode(array('result' => 0, 'message' => '参数错误'));exit;
}

$postData = Array(
    //支付查询参数

    <?php foreach($global['query_value'] as $key => $value): ?>
    <?php if($global['query_name'][$key] == '') continue; ?>
    '<?= $global['query_name'][$key] ?>' => <?= input_value($global['query_value'][$key]) . ",\t\t\t\t" ?>//<?= $global['query_note'][$key] . PHP_EOL ?>
    <?php endforeach; ?>
);


$sign_key = '<?= $global['sign_str_key'] ?>';

if($sign_key){
    $sign = create_sign($postData, array($sign_key => $app_key));
}else{
    $sign = create_sign($postData, $app_key);
}

$postData['<?= $global['sign_url_key'] ?>'] = $sign;

$result = json_decode(postSend($pay_query_url, $postData), true);

if($result<?= input_value(':' . $global['qu_succeed_key']) ?> === '<?= $global['qu_succeed_value'] ?>'){
    echo json_encode(array('result' => 1, 'message' => '该笔订单支付成功'));
}
else{
    echo json_encode(array('result' => 0, 'message' => '该笔订单未支付成功'));
}

exit;