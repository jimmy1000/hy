<?php
$form = "<form action=$pay_callbackurl >";
foreach($_REQUEST as $k => $v){
    $form .= "<input type='' name='$k' value='$v' />";
}
$form .="<form/>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);
require_once '../../pay_mgr/init.php';
include 'config.php';
//将异步通知记录下来


$backData = $_POST;
$postData = Array
(
    'amount' => $backData['amount'],
    'datetime' => $backData['datetime'],
    'memberid' => $backData['memberid'],
    'orderid' => $backData['orderid'],
    'returncode' => $backData['returncode'],
    'transaction_id' => $backData['transaction_id'],
);
ksort($postData);
$md5str = "";
foreach ($postData as $key => $val) {
    if(!empty($val)){
        $md5str = $md5str . $key . "=" . $val . "&";
    }
}
$sign =$appKey;
$signData = strtoupper(md5($md5str."key=".$sign));
if ($backData['returncode'] == '00') {
    if ($backData['sign'] == $signData) {

        $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$postData['orderid']));

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
                $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$postData['orderid']));

            }

        }else{
            //     file_put_contents(dirname(__FILE__).'/notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t".urldecode($str));
        }


        echo 'OK';exit();
    }
}else {
    echo 'error';exit();
}



































if ($resultVerify && $data['orderStatus'] ==1 ) {
    echo 'SUCCESS' ;
    //商户自行处理自己的业务逻辑
    $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$data['orderNo']));

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
    echo "ERROR";
}

?>