<?php
require_once '../../pay_mgr/init.php';
require_once  './common.php' ;
//金阳支付

$data['p1_mchtid']      = $merchant_id  ;
$data['p2_paytype']     = 'FASTPAY'; // 数组添加支付类型
$data['p3_paymoney']    = sprintf("%.2f",$_REQUEST['coin']); //支付金额保留两位小数
$data['p4_orderno']     = date("YmdHis").rand(100000,999999) ; //订单号;
$data['p5_callbackurl'] = $pay_returnurl ;
$data['p6_notifyurl']   = $pay_callbackurl ;
$data['p7_version']     = 'v2.8';
$data['p8_signtype']    = '1';
$data['p9_attach']      = $_REQUEST['username'].'_'.$data['p2_paytype'];
$data['p10_appname']    = '';
$data['p11_isshow']     = '0';
$data['p12_orderip']    = get_client_ip();
$data['p13_memberid']   = $_REQUEST['username'].$merchant_id;

$signStr = '' ;
foreach ($data as $k=>$v) {
    $signStr .= "{$k}={$v}&";
}
$signStr      = trim($signStr,'&').$key;
$data['sign'] = md5($signStr) ;



$pay_url = getLocalRequestUrl() ; //获取本地支付请求地址
//数据入库
$insertArr = [
    'order_id'    => $data['p4_orderno'],
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $data['sign'],
    'order_money' => $data['p3_paymoney'],
    'order_time'  => time(),
    'pay_api'     => '金阳',
    'pay_url'     => $pay_url
];
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}

$data  = submitFormMethod($data,$url);
dd($data) ;

?>
