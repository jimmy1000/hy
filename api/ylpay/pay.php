<?php
//
//                                  _oo8oo_
//                                 o8888888o
//                                 88" . "88
//                                 (| -_- |)
//                                 0\  =  /0
//                               ___/'==='\___
//                             .' \\|     |// '.
//                            / \\|||  :  |||// \
//                           / _||||| -:- |||||_ \
//                          |   | \\\  -  /// |   |
//                          | \_|  ''\---/''  |_/ |
//                          \  .-\__  '-'  __/-.  /
//                        ___'. .'  /--.--\  '. .'___
//                     ."" '<  '.___\_<|>_/___.'  >' "".
//                    | | :  `- \`.:`\ _ /`:.`/ -`  : | |
//                    \  \ `-.   \_ __\ /__ _/   .-` /  /
//                =====`-.____`.___ \_____/ ___.`____.-`=====
//                                  `=---=`
//
//
//               ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
//                          佛祖保佑         永不宕机/永无bug
// +----------------------------------------------------------------------
// | FileName: pay.php
// +----------------------------------------------------------------------
// | CreateDate: 2018年1月16日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
require_once '../../pay_mgr/init.php';
//error_reporting(E_ALL);
//ini_set('display_errors', true);
include './config.php';
$pay['inputCharset'] = 1;
$pay['partnerId'] = $partnerId;
$pay['signType'] = 1;
$pay['notifyUrl'] = $callback;
$pay['returnUrl'] = $hrefback;
$pay['orderNo'] = strtoupper(uniqid('yl'));
$pay['orderAmount'] = $_REQUEST['coin'] * 100 + rand(1, 99);
$pay['orderCurrency'] = 156;
$pay['orderDatetime'] = date('YmdHms', time());
switch ($_REQUEST['type']) {
    case 'ALIPAY':
    case 'ALIPAYWAP':
        $pay['payMode'] = 2;
        break;
    case 'WAP':
    case 'WECHAT':
        $pay['payMode'] = 1;
        break;
    case 'QQPAYWAP':
    case 'QQPAY':
        $pay['payMode'] = 5;
        break;
    default:
        exit('<script>alert("尚未支持的支付方式!")</script>');
}
$pay['isPhone'] = getISPhone();
$pay['subject'] = 'online-pay';
$pay['body'] = 'online-pay';
$pay['signMsg'] = create_sign($pay, $key);
if (! $_SERVER['HTTP_REFERER']) {
    $pay_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
} else {
    $pay_url = $_SERVER['HTTP_REFERER'];
}
$insertArr = array(
    'order_id' => $pay['orderNo'],
    'user_name' => $_REQUEST['username'],
    'pay_type' => $_REQUEST['type'],
    'pay_ip' => get_client_ip(),
    'sign' => $pay['signMsg'],
    'order_money' => strval($pay['orderAmount'] /100),
    'order_time' => time(),
    'pay_api' => '亿联支付',
    'pay_url' => $pay_url
);
if (! $database->insert(DB_PREFIX . 'preorder', $insertArr)) {
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
foreach ($pay as $k => $v) {
?>
<input type="hidden" name="<?php echo $k;?>" value="<?php echo $v;?>" />
<?php
}
?>
</form>
</body>
</html>
