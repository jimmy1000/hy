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
// | FileName: callback.php
// +----------------------------------------------------------------------
// | CreateDate: 2018年1月6日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
require 'api_sdk/init.php';
require_once '../../pay_mgr/init.php';
error_reporting(E_ALL);
ini_set('display_erros',true);
$_REQUEST['user_name'] = 'test001';
$_REQUEST['coin'] = 10;
$_REQUEST['type'] = 'WAP';
function rands()
{
    $seed = base_convert(md5(mt_srand() . microtime()), 16, 10);
    $seed = str_replace('0', '', $seed) . '012340567890';
    $hash = '';
    $max = strlen($seed) - 1;
    for ($i = 0; $i < 18; $i ++) {
        $hash .= $seed[mt_rand(0, $max)];
    }
    return date('YmdHis') . $hash;
}

$return = $order_sn = $pay_amount = '';
$order_sn = strtoupper(uniqid('DD'));
$pay_amount = $_REQUEST['coin'] * 100;

$getParams = [
    'pid' => PID, // 签约PID
    'method' => 'doudoupay.post.merchant.pay.directPay.trade', // 请求接口
    'timestamp' => GETTIME, // 时间戳
    'randstr' => func::getRandstr() // 随机32位串
];

$dataArray = [
    'merchant_no' => MERCHANT_NO, // 商户号，必传
     // 商户订单号，字符串型，长度范围在10到32之间,只能是数字和字符串，必传
     // 支付金额，单位为分,无小数点，必传
    'notify_url' => NOTIFY_URL, // 异步通知URL，一定外网地址才能收到回调通知信息，必传
    
    
    
];
$iswap = 0 ;
switch ($_REQUEST['type']){
    case 'ALIPAY':
        $pmt_tag = '';
       
        $pmt_tag = 'weixin';
        break;
    case 'QQPAY':
        $dataArray['order_sn']  = $order_sn;
        $pmt_tag = 'qq';
        break;
    case 'WECHAT':
        $dataArray['order_sn']  = $order_sn;
        $pmt_tag = 'weixin';
        break;
    case 'QQPAYWAP':
        $pmt_tag = '';
        $iswap = 1;
        break;
    case 'ALIPAYWAP':
        $pmt_tag = '';
        $iswap = 1;
        break;
    case 'WAP':
        $dataArray['pay_type'] = 'weixin';
        //unset($dataArray['order_sn']);
        $iswap = 1;
        break;
}
if($iswap){
    $getParams['method'] = 'doudoupay.post.merchant.wap.wapPay.create';
    $dataArray['trade_amount'] = $pay_amount;
    $dataArray['out_trade_no'] = $order_sn;
    $dataArray['body'] = '在线支付';
    $dataArray['sync_url'] =  'http://www.baidu.com';
    $dataArray['client_ip'] = '118.99.60.16';//get_client_ip();
}else{
    $dataArray['pmt_tag'] = $pmt_tag; // 支付类型,目前支持微信和qq，微信传weixin,qq传qq，必传
    $dataArray['order_desc'] = '在线支付';
    $dataArray['pay_amount'] = $pay_amount;
    $dataArray['order_sn']  = $order_sn;
    $dataArray['pay_type'] = 'swept'; // 支付场景,swept(用户扫商户的二维)，必传]
}

$dataArray = func::argSort($dataArray);//sort
$dataArray = func::array_value_to_string($dataArray);//to string

$str_query = array_merge($getParams, ['data' => $dataArray]);
$str_query = func::argSort($str_query);
$str_query = func::array_value_to_string($str_query);
$str_query = json_encode($str_query, JSON_UNESCAPED_SLASHES);

//生成签名
$getParams['sign'] = func::md5Sign($str_query, SING_KEY);

$getParams = func::argSort($getParams);

$url = SERVER_URL . '?' . func::http_build_querys($getParams);

$crypt = new CryptRSA();
$crypt->setParam('_public_key', file_get_contents(PUBLIC_KEY_FILE));//加载公钥
$crypt->setParam('_private_key', file_get_contents(PRIVATE_KEY_FILE));//加载私钥
$crypt->setParam('_private_key_password', PRIVATE_KEY_PASSWORD);//设置私钥密码

$encrypted = json_encode($dataArray, JSON_UNESCAPED_SLASHES);//数据转换成JSON
$encrypted = $crypt->publicEncrypt($encrypted);//数据加密
$post = ['data' => $encrypted];
$return = func::vCurl($url, $post);//发起API通讯
if (RETURN_CIPHERTEXT == true) {
    // 如果返回数据为密文，则需要解密数据
    //$return = $crypt->privateDecrypt($return);
}

$array = json_decode($return, true);
var_dump($return);

// 返回系统级错误异常处理
if (is_null($array) || ! is_array($array)) {
    echo $return;
    die();
}

// 返回逻辑错误处理
if ($array['errcode'] !== '0') {
    // 返回逻辑错误处理
}
/**
 * 接口返回正确后可进行正常订单逻辑操作
 */
if (! $_SERVER['HTTP_REFERER']) {
    $pay_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
} else {
    $pay_url = $_SERVER['HTTP_REFERER'];
}
$insertArr = array(
    'order_id' => $order_sn,
    'user_name' => $_REQUEST['username'],
    'pay_type' => $_REQUEST['type'],
    'pay_ip' => get_client_ip(),
    'sign' =>$getParams['sign'],
    'order_money' => $_REQUEST['coin'],
    'order_time' => time(),
    'pay_api' => '豆豆支付',
    'pay_url' => $pay_url
);
if (! $database->insert(DB_PREFIX . 'preorder', $insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}
if($iswap){
    header('location:'.$array['data']['out_pay_url']);
}
