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
// | CreateDate: 2017年12月29日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
include 'config.php';
$pay['merNo'] = $merNo;
$pay['orderNo'] = strtoupper(uniqid('YT'));
$pay['amount'] = $_REQUEST['coin'];
$pay['returnUrl'] = $returnUrl;
$pay['notifyUrl'] = $notifyUrl;
$pay['isDirect'] = '0';
switch ($_REQUEST['type']){
    case 'ALIPAYWAP':
        $pay['payType'] = 'ALIH5';
        break;
    case 'ALIPAY':
        $pay['payType'] = 'ALIPAY';
        break;
    case 'WECHAT':
        $pay['payType'] = 'WEIXIN';
        break;
    case 'WAP':
        $pay['payType'] = 'WXH5';
        break;
    case 'BANK':
        $pay['payType'] = 'WY';
        break;
    case 'BANKWAP':
        $pay['payType'] = 'KUAIJIE';
        break;
    case 'QQPAYWAP':
        $pay['payType'] = 'QQH5';
        break;
    default:
        exit("<script>alert('尚未支持的支付方式!')</script>");
        break;
}

$pay['bankSegment'] = '';

$pay['amount'] = $_REQUEST['coin'];



