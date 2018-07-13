<?php
// +----------------------------------------------------------------------
// | FileName:金流支付
// +----------------------------------------------------------------------
header("Content-type:text/html;charset=GB2312");
require_once '../../pay_mgr/init.php';
require_once './common.php' ;
$pay_orderid   = date("YmdHis").rand(100000,999999);
$money=sprintf("%.2f", $_REQUEST['coin']);
$type = getServiceType($_REQUEST['type']);
#	商家设置用户购买商品的支付信息.
##API支付平台统一使用GBK/GB2312编码方式,参数如用到中文，请注意转码

#	商户订单号,选填.
##若不为""，提交的订单号必须在自身账户交易中唯一;为""时，API支付会自动生成随机的商户订单号.
$p2_Order					= $pay_orderid;

#	支付金额,必填.
##单位:元，精确到分.
$p3_Amt						= $money;

#	交易币种,固定值"CNY".
$p4_Cur						= "CNY";
##用于支付时显示在API支付网关左侧的订单产品信息.
$p5_Pid						= "line-pay";
#	商品种类
$p6_Pcat					= "Virtual Goods";
#	商品描述
$p7_Pdesc					= "1";



#	商户接收支付成功数据的地址,支付成功后API支付会向该地址发送两次成功通知.
$p8_Url						= $pay_callbackurl;

#	商户扩展信息
##商户可以任意填写1K 的字符串,支付成功时将原样返回.
$pa_MP						= "123";
#	支付通道编码
##默认为""，到API支付网关.若不需显示API支付的页面，直接跳转到各银行、神州行支付、骏网一卡通等支付页面，该字段可依照附录:银行列表设置参数值.
$pd_FrpId					= $pay_type;

#	应答机制
##默认为"1": 需要应答机制;
$pr_NeedResponse	= "1";

#调用签名函数生成签名串
$hmac = getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse);

///数据入库`
$insertArr = array(
    'order_id'=>$pay_orderid,
    'user_name'=>$_REQUEST['username'],
    'pay_type'=>$_REQUEST['type'],
    'pay_ip'=>get_client_ip(),
    'sign'=>$hmac,
    'order_money'=>$_REQUEST['coin'],
    'order_time'=>time(),
    'pay_api'=>'金流宝支付',
    'pay_url'=>$url
);
if(!$database->insert(DB_PREFIX.'preorder',$insertArr)){
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}

?>
<html>
<head>
    <title>To API Page</title>
    <meta http-equiv="Content-Type" content="text/html; charset=GB2312" />
</head>
<body onLoad="document.API.submit();">
<form name='API' action='<?php echo $url; ?>' method='post' charset="GB2312">
    <input type='hidden' name='p0_Cmd'					value='<?php echo $p0_Cmd; ?>'>
    <input type='hidden' name='p1_MerId'				value='<?php echo $p1_MerId; ?>'>
    <input type='hidden' name='p2_Order'				value='<?php echo $p2_Order; ?>'>
    <input type='hidden' name='p3_Amt'					value='<?php echo $p3_Amt; ?>'>
    <input type='hidden' name='p4_Cur'					value='<?php echo $p4_Cur; ?>'>
    <input type='hidden' name='p5_Pid'					value='<?php echo $p5_Pid; ?>'>
    <input type='hidden' name='p6_Pcat'					value='<?php echo $p6_Pcat; ?>'>
    <input type='hidden' name='p7_Pdesc'				value='<?php echo $p7_Pdesc; ?>'>
    <input type='hidden' name='p8_Url'					value='<?php echo $p8_Url; ?>'>
    <input type='hidden' name='p9_SAF'					value='<?php echo $p9_SAF; ?>'>
    <input type='hidden' name='pa_MP'						value='<?php echo $pa_MP; ?>'>
    <input type='hidden' name='pd_FrpId'				value='<?php echo $pd_FrpId; ?>'>
    <input type='hidden' name='pr_NeedResponse'	value='<?php echo $pr_NeedResponse; ?>'>
    <input type='hidden' name='hmac'						value='<?php echo $hmac; ?>'>
</form>
</body>
</html>