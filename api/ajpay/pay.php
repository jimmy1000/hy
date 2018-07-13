<?php
// +----------------------------------------------------------------------
// | FileName: pay.php
// +----------------------------------------------------------------------
// | CreateDate: 2017年12月22日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
echo '1111';exit;
error_reporting(E_ALL) ;
ini_set('display_errors',true);
include_once("./config.php");
require_once '../../pay_mgr/init.php';

$username = $_REQUEST['username'];
$coin = $_REQUEST['coin'];
$pay['amount'] = $coin * 100;
$pay['mechno'] = $mechno;
$pay['body'] = '会员充值!';
$pay['notifyurl'] = $callback;
$pay['orderno'] = uniqid('AJ');
switch ($_REQUEST['type']){
    case 'ALIPAYWAP':
        $pay['payway'] = 'ALIPAY';
        $pay['paytype'] = 'ALIPAY_WAP';
        break;
    case 'ALIPAY':
        $pay['payway'] = 'ALIPAY';
        $pay['paytype'] = 'ALIPAY_SCAN_PAY';
        break;
    case 'WECHAT':
        $pay['payway'] = 'WECHAT';
        $pay['paytype'] = 'WECHAT_SCANPAY';
        break; 
    case 'WAP':
        $pay['payway'] = 'ALIPAY';
        $pay['paytype'] = 'ALIPAY_WAP';
        break;
    case 'QQPAY':
        $pay['payway'] = 'QQ';
        $pay['paytype'] = 'QQ_SCANPAY';
        break;
    case 'QQPAYWAP':
        $pay['payway'] = 'QQ';
        $pay['paytype'] = 'QQ_WAP';
        break;
    default:
        exit('<script>alert("尚未支持的支付方式!");history.go(-1)</script>');
        break;
}
ksort($pay);
reset($pay);
$signStr = '';
foreach($pay as $k => $v){
    $signStr .= "$k=$v&";
}
$signStr .= "key=$key";
$pay['sign'] = strtoupper(md5($signStr));

//数据入库
if (!$_SERVER['HTTP_REFERER']) {
    $pay_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
} else {
    $pay_url = $_SERVER['HTTP_REFERER'];
}
$insertArr = [
    'order_id' => $pay['orderno'],
    'user_name'=> $_REQUEST['username'],
    'pay_type' => $_REQUEST['type'],
    'pay_ip'   => get_client_ip(),
    'sign'     => $pay['sign'] ,
    'order_money' => $_REQUEST['coin'],
    'order_time'  => time(),
    'pay_api'     => '艾加支付',
    'pay_url'     => $pay_url
] ;
var_dump($insertArr);exit;
if(!$database->insert(DB_PREFIX.'preorder',$insertArr)){
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>在线支付</title>
</head>
<body onLoad="document.diy.submit();">
<form name='diy' id="diy" action='<?php echo $gateWay; ?>' method='post'>
<?php 
foreach($pay as $k => $v){
?>
<input type="hidden" name="<?php echo $k;?>" value="<?php echo $v;?>" />
<?php 
}
?>
</form>
</body>
</html>
