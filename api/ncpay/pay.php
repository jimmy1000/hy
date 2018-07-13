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
// | CreateDate: 2018年3月1日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
error_reporting(E_ALL);
ini_set('display_errors',true);

require_once 'common.php';
require_once 'config.php';
require_once './lib/paySubmit.class.php';
require_once '../../pay_mgr/init.php';
$data = array(
    'merOrderNo' =>strtoupper(uniqid('NC')),
    'orderAmt'  => $_REQUEST['coin'],
    'orderDesc' => '会员充值'.$_REQUEST['username'],
    'orderTitle' => '在线支付',
);
switch ($_REQUEST['type']){
    case 'ALIPAY':
        $data['payPlat'] = 'alipay';
        break;
    case 'WECHAT':
        $data['payPlat'] = 'wxpay';
        break;
}

$http_pay_url = getPayHttp();

// 商户后台异步通知url
$data['notifyUrl'] = $callback;

// 支付成功后，从收银台跳到商户的页面
$data['callbackUrl'] = 'https://www.sky1005.com';

$pay = new paySubmit($pay_config);
$order = $pay->scanOrder($data);
if (!$order) {
    die($pay->getErrMsg());
}else{
    //数据入库
    if (!$_SERVER['HTTP_REFERER']) {
        $pay_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
    } else {
        $pay_url = $_SERVER['HTTP_REFERER'];
    }
    $insertArr = [
        'order_id' => $data['merOrderNo'],
        'user_name'=> $_REQUEST['username'],
        'pay_type' => $_REQUEST['type'],
        'pay_ip'   => get_client_ip(),
        'sign'     =>  $order['jumpUrl'] ,
        'order_money' => $_REQUEST['coin'],
        'order_time'  => time(),
        'pay_api'     => '农村支付',
        'pay_url'     => $pay_url
    ] ;
    if(!$database->insert(DB_PREFIX.'preorder',$insertArr)){
        exit("<script>alert('创建订单失败!');history.go(-1);</script>");
    }
    header('location:'. $order['jumpUrl']);
}