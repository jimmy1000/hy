<?php
require_once'config.php';
require_once '../../pay_mgr/init.php';
$Ver='1.0';
$partner=$userid;
$ordernumber=date("YmdHis").rand(100000,999999);
$paymoney=number_format($_REQUEST['coin'],2,'.','');
$paytype=$payType;
$bankcode=$bankcode;
$remark='';
$get_code="0";

$sign=md5('Ver='.$Ver.'&partner='.$partner.'&paymoney='.$paymoney.'&ordernumber='.$ordernumber.'&notifyurl='.$notifyurl.'&returnurl='.$returnurl.'&'.$userkey);

//数据入库
$insertArr = [
    'order_id'    => $ordernumber,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => $_SERVER['REMOTE_ADDR'] ,
    'sign'        => $sign,
    'order_money' => $paymoney,
    'order_time'  => time(),
    'pay_api'     => '首捷支付',
    'pay_url'     => "http://www.cardbankpay.com/payapi"
] ;


if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}





?>
<!doctype html>
<html>
<head>
    <meta charset="utf8">
    <title>正在转到付款页</title>
</head>
<body onLoad="document.pay.submit()">
<form name="pay" action="http://www.cardbankpay.com/payapi" method="post">
    <input type="hidden" name="Ver" value="<?php echo $Ver?>">
    <input type="hidden" name="partner" value="<?php echo $partner?>">
    <input type="hidden" name="ordernumber" value="<?php echo $ordernumber?>">
    <input type="hidden" name="paymoney" value="<?php echo $paymoney?>">
    <input type="hidden" name="paytype" value="<?php echo $paytype?>">
    <input type="hidden" name="notifyurl" value="<?php echo $notifyurl?>">
    <input type="hidden" name="returnurl" value="<?php echo $returnurl?>">
    <input type="hidden" name="remark" value="<?php echo $remark?>">
    <input type="hidden" name="bankcode" value="<?php echo $bankcode?>">
    <input type="hidden" name="sign" value="<?php echo $sign?>">
    <input type="hidden" name="get_code" value="<?php echo $get_code?>">
</form>
</body>
</html>
