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
// | CreateDate: 2018年3月17日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
require_once '../../pay_mgr/init.php';
require_once ("Config.php");
require_once ("lib/Pay.class.php");
$coin = $_REQUEST['coin'];
$username = $_REQUEST['username'];

$ismobile = false;
switch ($_REQUEST['type']){
    case 'WECHAT':
        $words = '微信';
        $data['typeId'] = 2;
        $data['service'] = $APINAME_SCANPAY;
        break;
    case 'ALIPAY':
        $words = '支付宝';
        $data['typeId'] = 1;
        $data['service'] = $APINAME_SCANPAY;
        break;
    case 'QQPAY':
        $words = 'QQ';
        $data['typeId'] = 3;
        $data['service'] = $APINAME_SCANPAY;
        break;
    case 'BANKSCAN':
        $words = '银联扫码';
        $data['typeId'] = 4;
        $data['service'] = $APINAME_SCANPAY;
        break;
    case 'JDPAY':
        $words = '京东';
        $data['typeId'] = 5;
        $data['service'] = $APINAME_SCANPAY;
        break;
    case 'WAP':
        $ismobile = true;
        $data['typeId'] = 2;
        $data['service'] = $APINAME_H5PAY;
        break;
    case 'ALIPAYWAP':
        $ismobile = true;
        $data['typeId'] = 1;
        $data['service'] = $APINAME_H5PAY;
        break;
    case 'QQPAYWAP':
        $ismobile = true;
        $data['typeId'] = 3;
        $data['service'] = $APINAME_H5PAY;
        break;
    case 'BANKQUICK':
        break;
    case 'BANKWAP':
        break;
    case 'BANK':
        $ismobile = true;
        $data['service'] = $APINAME_PAY;
        // 接收银行代码
        $convert  = array(
            '964'=> 'ABC',
            '967'=> 'ICBC',
            '965'=> 'CCB',
            '981'=> 'COMM',
            '963'=> 'BOC',
            '970'=> 'CMB',
            '980'=> 'CMBC',
            '986'=> 'CEB',
            '989'=> 'BOBJ',
            '975'=> 'SHB',
            '982'=> 'HXB',
            '972'=> 'CIB',
            '971'=> 'PSBC',
            '978'=> 'PAB',
            '977'=> 'SPDB',
            '962'=> 'CNCB',
            '983'=> 'BOHZ',
            '985'=> 'CGB',
            '987'=> 'BEA',
            '979'=> 'BONJ',
            '968'=> 'CZSB',
            '990'=> 'BJCRB'
        );
        $data['bankId'] = $convert[$_REQUEST['bank']];
        break;
}

// 商户API版本
$data['version'] = $API_VERSION;
// 商户在支付平台的的平台号
$data['merId'] = $MERCHANT_ID;
// 商户订单号
$data['tradeNo'] = strtoupper(uniqid('tianji'));
// 商户订单日期
$data['tradeDate'] = date('Ymd');
// 商户交易金额
$data['amount'] = sprintf("%.2f", $coin);
// 商户通知地址
$data['notifyUrl'] = $MERCHANT_NOTIFY_URL;
// 商户扩展字段
$data['extra'] = $username;
// 商户交易摘要
$data['summary'] = 'onlinepay';
// 超时时间
$data['expireTime'] = '1200';
// 客户端ip
$data['clientIp'] = get_client_ip();



// 对含有中文的参数进行UTF-8编码
// 将中文转换为UTF-8
if (! preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['notifyUrl'])) {
    $data['notifyUrl'] = iconv("GBK", "UTF-8", $data['notifyUrl']);
}

if (! preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['extra'])) {
    $data['extra'] = iconv("GBK", "UTF-8", $data['extra']);
}

if (! preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['summary'])) {
    $data['summary'] = iconv("GBK", "UTF-8", $data['summary']);
}

// 初始化
$pPay = new Pay($KEY, $GATEWAY_URL);
// 准备待签名数据
$str_to_sign = $pPay->prepareSign($data);
// 数据签名

$signMsg = $pPay->sign($str_to_sign);

$data['sign'] = $signMsg;

if (! $_SERVER['HTTP_REFERER']) {
    $pay_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
} else {
    $pay_url = $_SERVER['HTTP_REFERER'];
}
$insertArr = array(
    'order_id' => $data['orderNo'],
    'user_name' => $_REQUEST['username'],
    'pay_type' => $_REQUEST['type'],
    'pay_ip' => get_client_ip(),
    'sign' => $data['sign'],
    'order_money' => $_REQUEST['coin'],
    'order_time' => time(),
    'pay_api' => '天机支付',
    'pay_url' => $pay_url
);
if (! $database->insert(DB_PREFIX . 'preorder', $insertArr)) {
    // exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}
// 生成表单数据
if(!$ismobile){
    // 准备请求数据
    $to_requset = $pPay->prepareRequest($str_to_sign, $signMsg);
    //请求数据
    $resultData = $pPay->request($to_requset);
    // 响应吗
    preg_match('{<code>(.*?)</code>}', $resultData, $match);
    $respCode = $match[1];
    
    // 响应信息
    preg_match('{<desc>(.*?)</desc>}', $resultData, $match);
    $respDesc = $match[1];
    
    
    preg_match('{<qrCode>(.*?)</qrCode>}', $resultData, $match);
    $respqrCode= $match[1];
    
    
    $base64 =base64_decode($respqrCode);
    include 'scan.php';
}else{
    echo $pPay->buildForm($data, $GATEWAY_URL);
}


?>

