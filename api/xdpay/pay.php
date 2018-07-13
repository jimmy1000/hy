<?php
// +----------------------------------------------------------------------
// | FileName: pay.php
// +----------------------------------------------------------------------
// | CreateDate: 2017年12月14日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
require_once '../../pay_mgr/init.php';
include './config.php';
$data['mechno'] = $mechno;
$data['mer_order_no'] = strtoupper(uniqid('XD'));
$data['amount'] = $_REQUEST['coin'] * 100;
switch ($_REQUEST['type']) {
    case 'WECHAT':
        $data['paytype'] = 'WECHAT';
        break;
    case 'WAP':
        break;
    case 'ALIPAY':
        $data['paytype'] = 'ALIPAY';
        break;
    case 'ALIPAYWAP':
        break;
    case 'QQPAY':
        $data['paytype'] = 'QQ';
        break;
    case 'QQPAYWAP':
        break;
}
$data['notifyurl'] = $hrefback;
$data['returl'] = $callback;
ksort($data);
reset($data);
$signStr = '';
foreach ($data as $k => $v) {
    $signStr .= "$k=$v&";
}
$signStr .= "key=$key";
$data['sign'] = strtoupper(md5($signStr));
if (! $_SERVER['HTTP_REFERER']) {
    $pay_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
} else {
    $pay_url = $_SERVER['HTTP_REFERER'];
}
$insertArr = array(
    'order_id' => $data['mer_order_no'],
    'user_name' => $_REQUEST['username'],
    'pay_type' => $_REQUEST['type'],
    'pay_ip' => get_client_ip(),
    'sign' => $data['sign'],
    'order_money' => $_REQUEST['coin'],
    'order_time' => time(),
    'pay_api' => '信兑支付',
    'pay_url' => $pay_url
);
if (! $database->insert(DB_PREFIX . 'preorder', $insertArr)) {
    // exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>在线支付</title>
</head>
<body onLoad="document.diy.submit();">
	<form name='diy' id="diy" action='<?php echo $gateWay; ?>'
		method='post'>
<?php
foreach ($data as $k => $v) {
    ?>
<input type="hidden" name="<?php echo $k;?>" value="<?php echo $v;?>" />
<?php
}
?>
</form>
</body>
</html>