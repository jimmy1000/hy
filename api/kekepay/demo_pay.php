<?php
/**
 * 支付 demo
 */
error_reporting(E_ALL);
ini_set('display_errors', true);
include_once realpath( __DIR__ )."/include/kekepay_class.inc.php";
$kekepay_class = new kekepay_class();

$pram = array();
$pram['orderPrice']    = 200.00;//订单金额，单位：元保留小数点后两位
$pram['outTradeNo']    = "201700000000";//商户支付订单号
$pram['productType']   = "40000301";//产品类型
$pram['orderTime']     = date('Ymdhis',time());//下单时间，格式yyyyMMddHHmmss
$pram['productName']   = "商品名称";//商品名称
$pram['orderIp']       = "198.98.23.1";//下单IP
$pram['remark']        = "这是订单备注";//备注


//返回一个支付按钮的 链接，把按钮放在订单的支付页面，点击之后是跳到 可可支付的收银台，
//支付成功之后，是跳到 同步回调地址return.php
//PS 如果收银台不支持直接参数待在支付地址上面请求过去（也就是不支持get），那么
//先提交到 咱们自己的 一个地址，再接收参数后 通过页面 表单的形式 提交过去
$btn = $kekepay_class->pay($pram);

var_dump($btn);






