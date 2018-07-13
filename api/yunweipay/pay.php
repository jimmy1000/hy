<?php
require_once '../../pay_mgr/init.php';
require_once 'config.php';
$version='1.0';
$customerid=$userid;
$sdorderno= strtoupper(uniqid('yw'));
$total_fee=sprintf('%0.2f',$_REQUEST['coin']);
$paytype=$_POST['paytype'];
$bankcode=$_POST['bankcode'];
switch ($_REQUEST['type']){
    case 'ALIPAY':
        $paytype='alipay';
        break;
    case 'ALIPAYWAP':
        $paytype='alipaywap';
        break;
    case 'WECHAT':
        $paytype='weixin';
        break;
    case 'WAP':
        $paytype='wxh5';
        break;
    case 'QQPAY':
        $paytype='Qqwallet';
        break;
    case 'QQPAYWAP':
        $paytype='Qqwap';
        break;
    case 'BANKSCAN':
        $paytype='Yinlian';
        break;
    case 'BANKWAP':
        $paytype='Quickwap';
        break;
    case 'JDPAY':
        $paytype='';
        break;
    case 'JDPAYWAP':
        $paytype='';
        break;
        
}
$notifyurl= $hrefback;
$returnurl= $callback;
$remark='';
$get_code= '0';
echo 'version='.$version.'&customerid='.$customerid.'&total_fee='.$total_fee.'&sdorderno='.$sdorderno.'&notifyurl='.$notifyurl.'&returnurl='.$returnurl.'&'.$userkey;
$sign=md5('version='.$version.'&customerid='.$customerid.'&total_fee='.$total_fee.'&sdorderno='.$sdorderno.'&notifyurl='.$notifyurl.'&returnurl='.$returnurl.'&'.$userkey);
if (! $_SERVER['HTTP_REFERER']) {
    $pay_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
} else {
    $pay_url = $_SERVER['HTTP_REFERER'];
}
$insertArr = array(
    'order_id' => $sdorderno,
    'user_name' => $_REQUEST['username'],
    'pay_type' => $_REQUEST['type'],
    'pay_ip' => get_client_ip(),
    'sign' =>$sign,
    'order_money' => $_REQUEST['coin'],
    'order_time' => time(),
    'pay_api' => '云威支付',
    'pay_url' => $pay_url
);
if (! $database->insert(DB_PREFIX . 'preorder', $insertArr)) {
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
    <form name="pay" action="http://pay.yunweipay.com/apisubmit" method="post">
        <input type="hidden" name="version" value="<?php echo $version?>">
        <input type="hidden" name="customerid" value="<?php echo $customerid?>">
        <input type="hidden" name="sdorderno" value="<?php echo $sdorderno?>">
        <input type="hidden" name="total_fee" value="<?php echo $total_fee?>">
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