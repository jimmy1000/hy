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


//////////////////////////	接收智通宝返回通知数据  /////////////////////////////////
/**
获取订单支付成功之后，智通宝通知服务器以post方式返回来的订单通知数据，参数详情请看接口文档,
 */
$merchant_code	= $_POST["merchant_code"];
$notify_type    = $_POST["notify_type"];
$notify_id      = $_POST["notify_id"];
$sign_type      = $_POST["sign_type"];
$dinpaySign     = base64_decode($_POST["sign"]);
$order_no       = $_POST["order_no"];
$order_time     = $_POST["order_time"];
$order_amount   = $_POST["order_amount"];
$trade_no       = $_POST["trade_no"];
$trade_time     = $_POST["trade_time"];
$trade_status   = $_POST["trade_status"];
$bank_seq_no    = $_POST["bank_seq_no"];
$interface_version  = $_POST["interface_version"];
$extra_return_param = $_POST["extra_return_param"];


/////////////////////////////   参数组装  /////////////////////////////////
/**
除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
 */
$signStr = "";

if($bank_seq_no != ""){
    $signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
}

if($extra_return_param != ""){
    $signStr = $signStr."extra_return_param=".$extra_return_param."&";
}

$signStr = $signStr."interface_version=".$interface_version."&";

$signStr = $signStr."merchant_code=".$merchant_code."&";

$signStr = $signStr."notify_id=".$notify_id."&";

$signStr = $signStr."notify_type=".$notify_type."&";

$signStr = $signStr."order_amount=".$order_amount."&";

$signStr = $signStr."order_no=".$order_no."&";

$signStr = $signStr."order_time=".$order_time."&";

$signStr = $signStr."trade_no=".$trade_no."&";

$signStr = $signStr."trade_status=".$trade_status."&";

$signStr = $signStr."trade_time=".$trade_time;

/////////////////////////////   RSA-S验签  /////////////////////////////////


$dinpay_public_key = openssl_get_publickey($dinpay_public_key);

$flag   = openssl_verify($signStr,$dinpaySign,$dinpay_public_key,OPENSSL_ALGO_MD5);

//////////////////////   异步通知必须响应“SUCCESS” /////////////////////////
/**
如果验签返回ture就响应SUCCESS,并处理业务逻辑，如果返回false，则终止业务逻辑。
 */



//比较签名密钥结果是否一致，一致则保证了数据的一致性
if ($flag) {
    echo 'SUCCESS' ;
    //商户自行处理自己的业务逻辑
    $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$order_no));

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