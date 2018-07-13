<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 15:04
 */
include 'common.php' ;
require_once '../../pay_mgr/init.php';

header('Content-type:text/html;charset=utf-8');

//将异步通知记录下来
$form = '<form action="" >';
foreach($_REQUEST as $k => $v){
    $form .= "<input type='' name='$k' value='$v' />";
}
$form .="<form/>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);

$md5key = $security_key ;//md5key

$returnArray = array( // 返回字段
    "memberid" => $_REQUEST["memberid"], // 商户ID
    "orderid" =>  $_REQUEST["orderid"], // 订单号
    "amount" =>  $_REQUEST["amount"], // 交易金额
    "datetime" =>  $_REQUEST["datetime"], // 交易时间
    "transaction_id" =>  $_REQUEST["transaction_id"], // 支付流水号
    "returncode" => $_REQUEST["returncode"],
);
ksort($returnArray);
reset($returnArray);


$md5str = "";
foreach ($returnArray as $key => $val) {
    $md5str = $md5str . $key . "=" . $val . "&";
}
$sign = strtoupper(md5($md5str . "key=" . $md5key));


if ($sign == $_REQUEST["sign"] && $_REQUEST["returncode"] == "00" ) {
    echo 'ok' ;

    $orderId = $_REQUEST["orderid"] ;
    $info    = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$orderId));

    if ($info) {
        $orderInfo = $database->get(DB_PREFIX . 'order', '*', array(
            'order_id' => $info['order_id'],
        )) ;

        if (!$orderInfo) {
            $insertArr = array(
                'order_id'    => $info['order_id'],
                'user_name'   => $info['user_name'],
                'order_money' => $info['order_money'],
                'order_time'  => time(),
                'order_state' => 1,
                'state'       => 0,
                'pay_type'    => $info['pay_type'],
                'pay_api'     => $info['pay_api'],
                'pay_order'   => $info['order_id'],
            );
            
            $updateArr = array(
                'notify_ip'   => get_client_ip(),
                'notify_time' => date('Y-m-d H:i:s'),
            );
            $database->insert(DB_PREFIX . 'order', $insertArr); //新增订单数据
            var_dump($database->last_query());
            $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$orderId)); //确认该订单已完成支付
            var_dump($database->last_query());
        }
    }
}