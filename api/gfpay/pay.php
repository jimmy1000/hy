<?php
// +----------------------------------------------------------------------
// | FileName: pay.php
// +----------------------------------------------------------------------
// | CreateDate: 2017年12月14日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
require_once '../../pay_mgr/init.php';
include './common.php';
include './config.php';
header("Content-type: text/html; charset=utf-8");
$data['parter'] = $parter;
$data['orderid'] =date("YmdHis").rand(100000,999999);
$data['value'] = sprintf("%.2f",$_REQUEST['coin']); //支付金额保留两位小数
switch ($_REQUEST['type']) {
    case 'WECHAT':
        $data['type'] = 'wx';
        $data['getcode']=0;
        $isPc="pc";
        $words = '微信';
        break;
    case 'WAP':
        $data['type'] = 'wxwap';
        $isPc="WAP";
        break;
    case 'ALIPAY':
        $data['type'] = 'ali';
        $data['getcode']=0;
        $isPc="pc";
        $words = '支付宝';
        break;
    case 'ALIPAYWAP':
        $data['type'] = 'aliwap';
        $isPc="WAP";
        break;
    case 'QQPAY':
        $data['type'] = 'QQ';
        $isPc="pc";
       $data['getcode']=0;
        $words = 'QQ钱包';
        break;
    case 'QQPAYWAP':
        $data['type'] = 'qqwap';
        $isPc="WAP";
        break;
}
$data['notifyurl'] = $hrefback;
$data['callbackurl'] = $callback;

ksort($data);
reset($data);

$data['sign'] = md5( urldecode( http_build_query( $data ) .'&key='. $key ) );
$pay_url=getLocalRequestUrl();
$insertArr = array(
    'order_id' => $data['orderid'],
    'user_name' => $_REQUEST['username'],
    'pay_type' => $_REQUEST['type'],
    'pay_ip' => get_client_ip(),
    'sign' => $data['sign'],
    'order_money' => $_REQUEST['coin'],
    'order_time' => time(),
    'pay_api' => '个付支付',
    'pay_url' => $pay_url
);

if (! $database->insert(DB_PREFIX . 'preorder', $insertArr)) {
   echo "失败";
}
sendForm($gateWay,$data);
function sendForm( $url, $data, $method='get')
{
    $form = "<form action='{$url}' id='sendForm' name='sendForm' method='{$method}'>\r\n";
    foreach ($data as $key => $value) {
        $form .= "<input type='hidden' name='".$key."' value='".$value."'>\r\n";
    }
    $form.= "</form><script>document.sendForm.submit();</script>";

    echo $form;
}
