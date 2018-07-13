<?php

require_once '../../pay_mgr/init.php';
include 'config.php';


//将异步通知记录下来
$form = '<form action="" >';
foreach($_REQUEST as $k => $v){
    $form .= "<input type='' name='$k' value='$v' />";
}
$form .="<form/>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);


$sign = $_REQUEST['sign'] ;
$orderNo =  $_REQUEST['out_trade_no'] ; //订单号

$data = [
    'trade_no' =>  $_REQUEST['trade_no'], //平台订单号
    'trade_type' =>  $_REQUEST['trade_type'], //支付类型
    'time_start' =>  $_REQUEST['time_start'], //发起交易时间
    'pay_time' =>  $_REQUEST['pay_time'], //交易时间
    'goods_name' =>  $_REQUEST['goods_name'], //商品名称
    'mchid' =>  $_REQUEST['mchid'], //平台商户号
    'src_code' =>  $_REQUEST['src_code'], //商户唯一标识
    'total_fee' =>  $_REQUEST['total_fee'], //订单总金额，单位分
    'order_status' =>  $_REQUEST['order_status'], //订单状态 → 1:下单中；2:等待支付；3:支付成功；4:支付失败；6:用户未支付
];
$sig = create_sign($data,$key) ;



//比较签名密钥结果是否一致，一致则保证了数据的一致性
if ($sign == $sig ) {
    echo 'SUCCESS' ;
    //商户自行处理自己的业务逻辑
    $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$orderNo));

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