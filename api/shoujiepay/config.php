<?php
header('Content-Type:text/html;charset=utf8');
date_default_timezone_set('Asia/Shanghai');

$userid='21922';
$userkey='5142d6158b374c9c1accf8f31f8d0f229a60994a';
$returnurl = 'http://'.$_SERVER['HTTP_HOST'].'/';
$notifyurl = 'http://'.$_SERVER['HTTP_HOST'].'/api/shoujiepay/callback.php';

$bankNumArr = array(
    '962' => 'CITIC',	//中信银行
    '963' => 'BOC',	//中国银行
    '964' => 'ABC',	//农业银行
    '965' => 'CCB',	//建设银行
    '967' => 'ICBC',	//工商银行
    '968' => '',	//浙商银行
    '969' => '',	//浙江稠州商业银行
    '970' => 'CMB',	//招商银行
    '971' => 'PSBC',	//邮政储蓄
    '972' => 'CIB',	//兴业银行
    '973' => '',	//顺德农村信用合作社
    '974' => 'SPA',	//深圳发展银行
    '975' => 'BOS',	//上海银行
    '976' => '',	//上海农村商业银行
    '977' => '',	//浦发银行
    '978' => 'PAB',	//平安银行
    '979' => 'NJCB',	//南京银行
    '980' => 'CMBC',	//民生银行
    '981' => 'COMM',	//交通银行
    '982' => 'HXB',	//华夏银行
    '983' => 'HZCB',	//杭州银行
    '984' => '',	//广州市农村信用社
    '985' => 'GDB',	//广发银行
    '986' => 'CEB',	//光大银行
    '987' => '',	//东亚银行
    '988' => '',	//渤海银行
    '989' => 'BCCB',	//北京银行
    '990' => '',	//北京农村商业银行
);

$type = $_REQUEST['type'] ;
$bankcode="";
switch ($type) {

    case 'WECHAT' :
        //$payType = 10000103 ;
        $payType = "weixin" ;
        $words = '微信';
        $isWAP='0';
        $isScan='1';
        $bankcode="";
        break ;
    case 'WAP' :
        $payType = "wxwap" ;
        $words = '微信';
        $isScan='1';
        $isWAP='1';
        $bankcode="";
        break ;

    case 'ALIPAY' :
        //$payType = "20000303" ;
        $payType = "alipay" ;//暂时改为收银台支付
        $words = '支付宝';
        $isScan='1';
        $isWAP='0';
        $bankcode="";
        break ;

    case 'ALIPAYWAP' :
        //$payType = 20000203 ;
        $payType = "alipaywap";
        $words = '支付宝';
        $isScan='1';
        $isWAP='1';
        $bankcode="";
        break ;


    case 'QQPAY' :
//            $payType = "70000203" ;
        $payType = "qq" ;
        $words = 'QQ';
        $isWAP='0';
        $isScan='1';
        $bankcode="";
        break ;
    case 'QQPAYWAP' :
//            $payType = 70000204;
        $payType = "qqwap";
        $words = 'QQ';
        $isScan='1';
        $bankcode="";
        break ;

    case 'JDPAY' :
        $payType = "jd" ;
        $words = '京东';
        $isScan='1';
        $isWAP='0';
        $bankcode="";
        break ;

    case 'BANK' :
        $payType = "bank" ;
        $words = '银联';
        $bankcode=$bankNumArr[$_REQUEST['bank']];
        break ;
    case 'BANKWAP' :
        $payType = 1 ;
        $words = '银联';
        $isWAP='1';
        $bankcode="";
        break ;
    case 'BANKSCAN' :
        $payType = "union" ;
        $words = '银联扫码';
        $isScan='1';
        $isWAP='0';
        $bankcode="";
        break ;
    case 'BANKQUICK' :
        $payType = "kuaijie" ;
        $words = '快捷支付';
        $isWAP='0';
        $bankcode="";
        break ;


    default:
        break ;
}





?>
