<?php
require_once '../../pay_mgr/init.php';
require_once  'config.php' ;
//成富通

//由于成富通,规定了充值金额必须为 20 50 100 200 300 400 500 600 700 800 900 1000 2000 3000 5000
//所以这里对支付金额进行判断,如果不为以上整数,则提示用户只能输入以上整数
if (!checkMoney()) {
    exit("<script>alert('支付金额只能为:20元,50元,100元,200元,300元,400元,500元,600元,700元,800元,900元,1000元,2000元,3000元,5000元');history.go(-1);</script>");
}

$terminal = 1 ;  // 1:pc    2:ios     3:android
$compkey 		   = $key;		//商户密钥
$p1_yingyongnum	   = $merchant_id;			//商户应用号
$p2_ordernumber    = date("YmdHis").rand(100000,999999) ; //订单号;
$p3_money 		   = sprintf("%.2f",$_REQUEST['coin']); //支付金额保留两位小数
$p6_ordertime  	   = date("YmdHis", time());			//商户订单时间
$p7_productcode	   =  getPayType($terminal);			  	//产品支付类型编码
$presign 		   = $p1_yingyongnum."&".$p2_ordernumber."&".$p3_money."&".$p6_ordertime."&".$p7_productcode."&".$compkey;
$p8_sign 		   = md5($presign);				//订单签名
$p9_signtype       = 1 ;//签名方式 1代表MD5
$p25_terminal      = $terminal ;

$pay_url = getLocalRequestUrl() ; //获取本地支付请求地址

//数据入库
$insertArr = [
    'order_id'    => $p2_ordernumber,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $p8_sign,
    'order_money' => $p3_money,
    'order_time'  => time(),
    'pay_api'     => '成富通',
    'pay_url'     => $pay_url
];
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Language" content="zh-cn">
</head>
<body>
<form  action='<?=$url?>' method='post' enctype="application/json">
    <input type='hidden' name='p1_yingyongnum'			value='<?php echo $p1_yingyongnum; ?>'>
    <input type='hidden' name='p2_ordernumber'			value='<?php echo $p2_ordernumber; ?>'>
    <input type='hidden' name='p3_money'				value='<?php echo $p3_money; ?>'>
    <input type='hidden' name='p6_ordertime'			 	value='<?php echo $p6_ordertime; ?>'>
    <input type='hidden' name='p7_productcode'			value='<?php echo $p7_productcode; ?>'>
    <input type='hidden' name='p8_sign'				value='<?php echo $p8_sign; ?>'>
    <input type='hidden' name='p9_signtype'				value='<?php echo $p9_signtype; ?>'>
    <input type='hidden' name='p25_terminal'				value='<?php echo $p25_terminal; ?>'>
</form>
<script type="text/javascript">
    document.forms[0].submit();
</script>
</body>
</html>