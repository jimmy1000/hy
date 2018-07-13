<?php

require_once '../../pay_mgr/init.php';
require_once("./function.php");
include 'config.php';


//将异步通知记录下来
$form = '<form action="" >';
foreach($_REQUEST as $k => $v){
    $form .= "<input type='' name='$k' value='$v' />";
}
$form .="<form/>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);


$data = [] ;
// 请求数据赋值
$data['apiName'] = $_REQUEST["apiName"];
// 通知时间
$data['notifyTime'] = $_REQUEST["notifyTime"];
// 支付金额(单位元，显示用)
$data['tradeAmt'] = $_REQUEST["tradeAmt"];
// 商户号
$data['merchNo'] = $_REQUEST["merchNo"];
// 商户参数，支付平台返回商户上传的参数，可以为空
$data['merchParam'] = $_REQUEST["merchParam"];
// 商户订单号
$data['orderNo'] = $_REQUEST["orderNo"];
// 商户订单日期
$data['tradeDate'] = $_REQUEST["tradeDate"];
// 支付系统订单号
$data['accNo'] = $_REQUEST["accNo"];
// 支付系统账务日期
$data['accDate'] = $_REQUEST["accDate"];
// 订单状态，0-未支付，1-支付成功，2-失败，4-部分退款，5-退款，9-退款处理中
$data['orderStatus'] = $_REQUEST["orderStatus"];
// 签名数据
$data['signMsg'] = $_REQUEST["signMsg"];




// 初始化
print_r($_POST);
$cMbPay = new MbPay($appKey, $url);
// 准备验签数据
$str_to_sign = $cMbPay->prepareSign($data);
print_r($str_to_sign);



// 验证签名
$resultVerify = $cMbPay->verify($str_to_sign, $data['signMsg']);
//比较签名密钥结果是否一致，一致则保证了数据的一致性
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