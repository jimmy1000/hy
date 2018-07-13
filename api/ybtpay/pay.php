<?php
// 亿宝通
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(-1);                    //打印出所有的 错误信息

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;
require_once  'common.php' ;


$orderNo    = date("YmdHis").rand(100000,999999);
$total_fee  = $_REQUEST['coin']*100;
$words      = '' ; //支付类型描述

//下单信息
$order_info = array();
$order_info['version']     = 'V1.0';  //当前接口版本号
$order_info['merchantNum'] = $merchant_id;  //分配给商家的商户号
$order_info['nonce_str']   = date('YmdHis').rand(1000000,9999999);//随机字符串
$order_info['merMark']     = $uniqueness; //分配给商家的商户标识
$order_info['client_ip']   = get_client_ip(); //客户端ip，如127.0.0.1
$order_info['orderTime']   = date('Y-m-d H:i:s');   //订单时间（格式: yyyy-MM-dd HH:mm:ss）
$order_info['payType']     = get_paytype($words); //支付类型
$order_info['orderNum']    = $orderNo; //商户订单号
$order_info['amount']      = $total_fee; //订单金额，单位（分）
$order_info['body']        = 'Online_pay'; //订单描述
if ($order_info['payType'] == 'B2C') {
    $order_info['bank_code'] = getBankCode(); //payType为B2C时必填
}
$order_info['notifyUrl']   = $pay_callbackurl; //异步回调地址
$order_info['sign']        = create_sign($order_info,$pay_key);
$order_info['signType']    = 'MD5'; //签名类型（MD5）不参与签名

//数据入库
$insertArr = [
    'order_id'    => $orderNo ,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $order_info['sign'],
    'order_money' => $_REQUEST['coin'],
    'order_time'  => time() ,
    'pay_api'     => '亿宝通',
    'pay_url'     => $_SERVER['REMOTE_ADDR'] , //获取客户支付地址
] ;
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}

$res = submitFormMethod($order_info,$pay_url);
dd($res) ;
?>
