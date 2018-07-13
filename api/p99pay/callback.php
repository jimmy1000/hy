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

$ReturnArray = array( // 返回字段
    "finish_time"   => $_REQUEST["finish_time"], // 订单支付时间
    "msg"           => $_REQUEST["msg"], //备注信息
    "orderno"       => $_REQUEST["orderno"], // 交易时间
    "trans_amount"  => $_REQUEST["trans_amount"], // 平台订单号
) ;

$Md5key = $key; //MD5密钥

///////////////////////////////////////////////////////
//ksort($ReturnArray);
//reset($ReturnArray);
$md5str = "";
foreach ($ReturnArray as $key => $val) {
    $md5str = $md5str . $key . "=" . $val . "&";
}
$sign = strtoupper(md5($md5str . "key=" . $Md5key)) ;

///////////////////////////////////////////////////////
if ($sign == $_REQUEST["sign"]) {
    echo '0000' ;
    $orderId =  explode('-',$ReturnArray["msg"])[3] ;
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
            $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$orderId)); //确认该订单已完成支付

        }
    }
}