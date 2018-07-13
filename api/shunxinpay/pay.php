<?php
require_once '../../pay_mgr/init.php';
require_once  'config.php' ;
require_once  'common.php' ;

$orderNo=date("YmdHis").rand(100000,999999);
$total_fee=$_REQUEST['coin'];

//下单信息
$data=array();
$data["apiName"]="WEB_PAY_B2C";
$data["apiVersion"]="1.0.0.0";
$data["platformID"]=$merchant_id;
$data["merchNo"]=$merchant_id;
$data["orderNo"]=$orderNo;
$data["tradeDate"]=date("Ymd",time());
$data["amt"]=$total_fee;
$data["merchUrl"]=$pay_callbackurl;
$data["merchParam"]="onlinePay";
$data["tradeSummary"]="123";
$data["signMsg"]= md5( urldecode( http_build_query($data) .$key));
$data["bankCode"]=$payType;

$html = submitFormMethod($data, $purl);
//数据入库
$insertArr = [
    'order_id'    => $orderNo ,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $order_info['sign'],
    'order_money' =>$_REQUEST['coin'],
    'order_time'  => time() ,
    'pay_api'     => '顺心付',
    'pay_url'     => $_SERVER['REMOTE_ADDR'] , //获取客户支付地址
] ;
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}
echo    $html;exit;
?>