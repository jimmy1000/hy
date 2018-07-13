<?php

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;

//鑫支付
$data['mchno']      = $merchant_id ; //商户ID
$data['pay_type']   = getPayType() ; //支付类型
$data['price']      = $_REQUEST['coin'] * 100 ; //单位分
$data['bill_title'] = 'online_pay' ; //商品标题
$data['bill_body']  = 'online_pay' ; //商品描述
$data['nonce_str']  = date("YmdHis").rand(100000,999999).'A' ; //随机字符串
$data['notify_url'] =  $pay_callbackurl ;
$data['linkId']     =  date("YmdHis").rand(100000,999999) ; //订单号
$data['sign']       = create_sign($data,$key) ; //签名

$pay_url = getLocalRequestUrl() ; //获取客户支付地址
//数据入库
$insertArr = [
    'order_id'    => $data['linkId'] ,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $data['sig'],
    'order_money' => $_REQUEST['coin'],
    'order_time'  => time() ,
    'pay_api'     => '鑫支付',
    'pay_url'     => $pay_url
] ;

if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}

$result = submitPostData($url,json_encode($data)) ;

$data = json_decode($result,true) ;

if ($data['resultCode'] == 200) {
    header('Location:'.$data['order']['pay_link']) ;
    die;
}
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('网络延迟,请重试...');history.go(-1);</script>") ;
}
?>