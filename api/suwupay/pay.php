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
// | CreateDate: 2018年1月7日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
require_once '../../pay_mgr/init.php';
require_once 'config.php';
include_once 'class/SF.class.php';

$coin = sprintf('%0.2f',$_REQUEST['coin']);

$memberid = $pay_memberid;//商户号
$pay_orderid =  strtoupper(uniqid('SW')); //订单号
$pay_amount = $coin;    //交易金额
$pay_applydate = date("Y-m-d H:i:s");  //订单时间
$bankcode = '925';
$banktype = '925';//WAP支付宝为：WAPALIPAY
//初始化，设置商户号、秘钥
$a = new SF($memberid,$key);
$a->PutUrl      = $gateWay; //设置提交网关
$a->DeBug       =  false; //debug模式，false为关闭
$a->NotifyUrl   = 'http://'.$_SERVER['HTTP_HOST'].'/api/suwupay/callback.php'; //设置点对点返回地址
$a->CallbackUrl = 'http://'.$_SERVER['HTTP_HOST'].'/api/suwupay/hrefback.php'; //设置页面跳转地址
//设置扩展字段（扩展字段必须为数组）
$a->Reserved = array(
    "pay_reserved1" => '扩展1',
    "pay_reserved2" => '扩展2',
    "pay_reserved3" => '扩展3'
);
$json_a = $a->JsonPut('会员服务',$pay_orderid,1,$pay_applydate,$bankcode,$banktype);
$info = json_decode($json_a,true);
if($info['code'] == 200 && $info['ysj']['msg'] == 'ok'){
    if (! $_SERVER['HTTP_REFERER']) {
        $pay_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
    } else {
        $pay_url = $_SERVER['HTTP_REFERER'];
    }
    $insertArr = array(
        'order_id' => $pay_orderid,
        'user_name' => $_REQUEST['username'],
        'pay_type' => $_REQUEST['type'],
        'pay_ip' => get_client_ip(),
        'sign' =>$getParams['sign'],
        'order_money' => $coin,
        'order_time' => time(),
        'pay_api' => '速五支付',
        'pay_url' => $pay_url
    );
    if (! $database->insert(DB_PREFIX . 'preorder', $insertArr)) {
        exit("<script>alert('创建订单失败!');history.go(-1);</script>");
    }
    header('location:'.$info['ysj']['data']['out_pay_url']);
    
    
}

?>
