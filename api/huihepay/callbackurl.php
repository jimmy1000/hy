<?php
include 'config.php';
$form = '<form action="'.$callbackurl.'" >';
foreach($_REQUEST as $k => $v){
    $form .= "<input type='text' name='$k' value='$v' />";
}
$form .="<input type='submit'><form/><br>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);
require ("config.php");
//将异步通知记录下来
$request=array();
foreach ($_REQUEST as $k => $v){
	if($k != 'sign'&& $k!=""){
        $request[$k]=$v;
	}
}
//验证签名
if($_REQUEST['tradeStatus']=='SUCCESS')
{
    if($request['remark'] == "123"){
		require_once '../../pay_mgr/init.php';
        $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$request['outTradeNo']));
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
                $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$request['out_order_sn']));
                echo "SUCCESS";
            }
        }else{
            file_put_contents(dirname(__FILE__).'/errlog.html',$request['out_order_sn'] ."错误"."\r\n",FILE_APPEND);
        }
	}else{
        echo "错误";
    }
}else{
	echo "error";
}

?>
