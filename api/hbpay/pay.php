<?php

require_once '../../pay_mgr/init.php';
include './config.php';
$url = 'http://api.huobapay.com/api/auth/access-token'; //获取token请求地址

//火把支付宝WAP
$data['appid']        = $mch_id ;
$data['secretid']     = $key ;
$ret                  = xmlToArray(file_get_contents($url.'?'.http_build_query($data))) ;
$token                = $ret['token'] ;
$pay['mch_id']        = $mch_id;
$pay['out_trade_no']  = strtoupper(uniqid('hb'));
$pay['body']          = 'online-pay';
$pay['total_fee']     = $_REQUEST['coin'] * 100; //以分为单位
$pay['mch_create_ip'] = get_client_ip();
$pay['notify_url']    = $callback;
$pay['nonce_str']     = time().rand(10,100);
ksort($pay);
reset($pay);
$signStr = '';
foreach ($pay as $k =>$v){
    $signStr .= "$k=$v&";
}
$signStr .= "key=$key";
$sign        = strtoupper(md5($signStr));
$pay['sign'] = $sign;
$post        = makexml($pay);
$xml         = postxml($gateWay.'?token='.$token, $post);
$arr         = xmlToArray($xml);

if (!$_SERVER['HTTP_REFERER']) {
    $pay_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
} else {
    $pay_url = $_SERVER['HTTP_REFERER'];
}
if($arr['result_code'] == 0 && $arr['redirect_url']){
    $insertArr = [
        'order_id'    => $pay['out_trade_no'],
        'user_name'   => $_REQUEST['username'],
        'pay_type'    => $_REQUEST['type'],
        'pay_ip'      => get_client_ip(),
        'sign'        => $pay['sign'] ,
        'order_money' => $_REQUEST['coin'],
        'order_time'  => time(),
        'pay_api'     => '火把支付',
        'pay_url'     => $pay_url
    ] ;
    if(!$database->insert(DB_PREFIX.'preorder',$insertArr)){
        exit("<script>alert('创建订单失败!');history.go(-1);</script>");
    }
    header('location:'.$arr['redirect_url']);
}

