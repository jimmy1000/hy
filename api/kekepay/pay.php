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
// | CreateDate: 2018年1月4日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
include 'config.php';
$_REQUEST['coin'] = '10';
$_REQUEST['username'] = 'test001';
$_REQUEST['type'] = 'WECHAT';
$pay['payKey'] = $payKey;
$pay['orderPrice'] = strval(sprintf('%0.2f',$_REQUEST['coin'])); 
$pay['outTradeNo'] = uniqid('KK'); 
$pay['productType'] = '10000103'; 
$pay['orderTime'] = date('YmdHis'); 
$pay['productName'] = 'online-pay'; 
$pay['orderIp'] = '118.99.60.16'; 
$pay['returnUrl'] = $callback; 
$pay['notifyUrl'] = $callback; 
$pay['remark'] = 'hell,world';
$str = '';
ksort($pay);
reset($pay);
foreach ($pay as $k => $v){
    $str .= "$k=$v&";
}
$str .= "paySecret=$key";
$pay['sign'] = strtoupper(md5($str));
$query = http_build_query($pay);
$option = array(
    'http'=>array(
        'method' => 'POST',
        'header' => 
        "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\r\n".
        "Accept-Encoding:gzip, deflate\r\n".
        "Accept-Language:zh-CN,zh;q=0.8,en;q=0.6\r\n".
        "Cache-Control:max-age=0\r\n".
        "Connection:keep-alive\r\n".
        "Content-Length:".strlen($query)."\r\n".
        "Content-Type:application/x-www-form-urlencoded\r\n".
        "Origin:null\r\n".
        "Upgrade-Insecure-Requests:1\r\n".
        "User-Agent:Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36\r\n",
        'content' => $query
    )
);
$context = stream_context_create($option);
$res = file_get_contents($gateWay,false,$context);
var_dump($res);


