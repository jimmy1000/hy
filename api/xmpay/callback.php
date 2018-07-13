<?php

require_once '../../pay_mgr/init.php';
include 'config.php';

//由于返回的数据流是json,所以这里特殊处理一下
$data = file_get_contents("php://input");
$data = json_decode($data,true) ;
$data = empty($data) ? $_REQUEST : $data ;

//将异步通知记录下来
$form = '<form action="" >';
foreach($data as $k => $v){
    $form .= "<input type='' name='$k' value='$v' />";
}
$form .="<form/>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);


$sign = $_REQUEST['sign'] ; //签名
$data = [
  'resultCode'  => $_REQUEST['resultCode'],
  'resultDesc'  => $_REQUEST['resultDesc'],
  'resCode'     => $_REQUEST['resCode'],
  'resDesc'     => $_REQUEST['resDesc'],
  'nonceStr'    => $_REQUEST['nonceStr'],
  'branchId'    => $_REQUEST['branchId'],
  'createTime'  => $_REQUEST['createTime'],
  'orderAmt'    => $_REQUEST['orderAmt'],
  'orderNo'     => $_REQUEST['orderNo'],
  'outTradeNo'  => $_REQUEST['outTradeNo'],
  'productDesc' => $_REQUEST['productDesc'],
  'status'      => $_REQUEST['status'],
];

$data  = sign($data, $appKey) ;

//比较签名密钥结果是否一致，一致则保证了数据的一致性
if ($sig == $data['sign'] ) {
    echo 'SUCCESS' ;
    //商户自行处理自己的业务逻辑
    $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$data['outTradeNo']));

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
            $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$_REQUEST['down_sn']));

        }

    }else{
   //     file_put_contents(dirname(__FILE__).'/notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t".urldecode($str));
    }
} else {
    echo "Signature error";
}

?>