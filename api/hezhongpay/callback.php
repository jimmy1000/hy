<?php
require_once '../../pay_mgr/init.php';
include 'config.php';
//将异步通知记录下来
$form = '<form action="'.$notify_url.'" >';
foreach($_REQUEST as $k => $v){
    $form .= "<input type='text' name='$k' value='$v' />";
}
$form .="<input type='submit'></form><br>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);

foreach ($_REQUEST as $k=>$v){
    if($k!="sign"&&$v!=""){
        $signstr.=$k."=".$v."&";
    }
}
$signstr=substr($signstr,0,-1);
$signstr=$signstr.$sescrt;
$signstr=md5($signstr);

if($signstr == $_REQUEST['sign']){
    if($_REQUEST["code"] == '0000'){
        $info = $database->get(DB_PREFIX . 'preorder', '*', array('order_id' =>$_REQUEST["orderId"]));

        if ($info) {
            $infos = $database->get(DB_PREFIX . 'order', '*', array(
                'order_id' => $info['order_id'],
            ));

            if (!$infos) {
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
                $database->update(DB_PREFIX . 'preorder', $updateArr, array('order_id' =>$_REQUEST["orderId"]));
            }
        } else {
            echo " 状态错误";
        }
        echo 'true'; // 数据验证完成必须输出0告诉系统 通知完成。
        exit();
    }
}else{
    echo "签名错误!";
}
