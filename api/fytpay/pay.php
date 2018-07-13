<?php

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;

//付营通
$data['type']          = 'PayData' ; //支付类型
$data['amount']        = sprintf("%.2f",$_REQUEST['coin'])  ; //支付金额保留两位小数
$data['clientorderno'] = date("YmdHis").rand(100000,999999) ; //订单号;
$data['paytype']       = getPayType()      ; //获取支付类型
$data['callbackurl']   = $pay_callbackurl  ; //异步回调地址
$data['shopid']        = $merchant_id      ; //商户ID
$data['key']           = $key ;             //密钥
$data['sig']           = md5(implode('|',$data)) ;//签名

$code  = submitPostData($url,$data) ; //拿到二维码code


$pay_url = getLocalRequestUrl() ; //获取客户支付地址
//数据入库
$insertArr = [
    'order_id'    => $data['clientorderno'] ,
    'user_name'   => $_REQUEST['username'] ,
    'pay_type'    => $_REQUEST['type'] ,
    'pay_ip'      => get_client_ip() ,
    'sign'        => $data['sig'] ,
    'order_money' => $data['amount'] ,
    'order_time'  => time() ,
    'pay_api'     => '付营通' ,
    'pay_url'     => $pay_url
];

if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}

if ( $data['paytype'] == 1 ){
    //微信
    $url =  "http://pay1.cggoon.com/pay/WeChat.aspx?Code=".$code."&SuccessUrl=".$pay_returnurl ;
} elseif ( $data['paytype'] == 2 ) {
    //支付宝
    $url =  "http://pay1.cggoon.com/pay/AliPay.aspx?Code=".$code."&SuccessUrl=".$pay_returnurl ;
}

header('Location:'.$url) ;
die;

?>