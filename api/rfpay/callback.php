<?php

require_once '../../pay_mgr/init.php';
require_once("./common.php");
include 'config.php';


//将异步通知记录下来
$form = '<form action="'.$pay_callbackurl.'" >';
foreach($_REQUEST as $k => $v){
    $form .= "<input type='text' name='$k' value='$v' />";
}
$form .="<input type='submit'><form/><br>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);
write_log('接收到后台通知');
$data = $_POST['data'];
$arr = Util::json_decode($data);
if (is_sign($arr,$config['signKey'])){
    write_log('通知签名验证成功');
    $amount = (int) $arr['amount'];
    $amount = $amount / 100;
    $goodsName = $arr['stuffName'];
    $orderNum = $arr['orderNum'];
    $PayDate = $arr['orderDate'];
    $PayResult = $arr['result'];
    if ($PayResult == '00'){
        write_log('支付成功...订单号：' . $orderNum . ' 商品名称:' . $goodsName . ' 支付金额：' . $amount);
        echo '0'; #数据验证完成必须输出0告诉系统 通知完成。
    //商户自行处理自己的业务逻辑
    $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$orderNum));
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
            $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$orderNum));

        }

    }else{
   //     file_put_contents(dirname(__FILE__).'/notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t".urldecode($str));
    }
        exit();
    }

}else{
    write_log('通知签名验证失败');
}

?>