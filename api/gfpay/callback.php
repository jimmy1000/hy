<?php

require_once '../../pay_mgr/init.php';
include 'config.php';


//将异步通知记录下来
$form = '<form action="" >';
foreach($_REQUEST as $k => $v){
    $form .= "<input type='' name='$k' value='$v' />";
}
$form .="<input type='submit'/>";
$form .="<form/>";

file_put_contents(dirname(__FILE__).'./log.html', $form."\r\n",FILE_APPEND);

$data['parter'] = $parter;
$data['orderid']= $_REQUEST['orderid'];
$data['opstate']= $_REQUEST['opstate'];
$data['ovalue'] = $_REQUEST['ovalue'];
ksort($data);
$local_sign = md5( urldecode( http_build_query($data).'&key='.$key ) );
$sign = $_REQUEST['sign'];
if( $data['opstate'] == 1 && $sign == $local_sign ){
    echo 'success';

//比较签名密钥结果是否一致，一致则保证了数据的一致性
    //商户自行处理自己的业务逻辑
    $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$data['orderid']));

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
            $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$_REQUEST['orderid']));

        }

    }else{

           file_put_contents(dirname(__FILE__).'/notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t".urldecode($str));
    }
} else {
    echo "error";
}

?>