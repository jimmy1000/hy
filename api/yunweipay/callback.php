<?php
$form = "<form action='http://www.sky1005.com/api/doudoupay/callback.php'>";
foreach($_REQUEST as $k => $v){
    $form .= "<input name='$k' value='$v' />";
}
$form .= "</form>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);
require_once 'config.php';
require_once '../../pay_mgr/init.php';
$status=$_REQUEST['status'];
$customerid=$_REQUEST['customerid'];
$sdorderno=$_REQUEST['sdorderno'];
$total_fee=$_REQUEST['total_fee'];
$paytype=$_REQUEST['paytype'];
$sdpayno=$_REQUEST['sdpayno'];
$remark=$_REQUEST['remark'];
$sign=$_REQUEST['sign'];
$ip = get_client_ip();
$mysign=md5('customerid='.$customerid.'&status='.$status.'&sdpayno='.$sdpayno.'&sdorderno='.$sdorderno.'&total_fee='.$total_fee.'&paytype='.$paytype.'&'.$userkey);
if($sign==$mysign){
    if($status=='1'){
        echo 'success';
        
        $order = $database->get(DB_PREFIX.'preorder', '*' , array('order_id'=>$sdorderno));
        if(empty($order)){
            file_put_contents(dirname(__FILE__).'/notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t". var_export($_REQUEST).PHP_EOL , FILE_APPEND);
            die('success');
        }
        
        if(floatval($order['order_money']) == floatval($total_fee)){
            $order_ = $database->get(DB_PREFIX . 'order', '*', array('order_id' => $sdorderno ));
            if(empty($order_)){
                $insertArr = array(
                    'order_id' => $order['order_id'],
                    'user_name' => $order['user_name'],
                    'order_money' => $order['order_money'],
                    'order_time' => time(),
                    'order_state' => 1,
                    'state' => 0,
                    'pay_type' => $order['pay_type'],
                    'pay_api' => $order['pay_api'],
                    'pay_order' => $order['order_id'],
                );
                $updateArr = array(
                    'notify_ip' => get_client_ip(),
                    'notify_time' => date('Y-m-d H:i:s'),
                );
                $database->insert(DB_PREFIX . 'order', $insertArr);
                $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$sdorderno));
            }
            
        }else{
            file_put_contents(dirname(__FILE__).'/money.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据金额错误\t". var_export($_REQUEST).PHP_EOL , FILE_APPEND);
        }
    } else {
        echo 'fail';
    }
} else {
    echo 'signerr';
}
?>
