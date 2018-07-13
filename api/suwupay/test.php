<?php
header("Content-type: text/html; charset=utf-8");
ini_set('date.timezone','Asia/Shanghai');
include_once 'class/SF.class.php';

$memberid = '10048';//商户号
$key = '99h27nny1ka5r9kskney9a3w28fdrcm2';//商户秘钥
$pay_orderid = $memberid.date("YmdHis").rand(100000,999999); //订单号
$pay_amount = 200;    //交易金额
$pay_applydate = date("Y-m-d H:i:s");  //订单时间
$bankcode = '925';
$banktype = '925';//WAP支付宝为：WAPALIPAY


/**
 * 提交信息时请注意编码，提交编码格式为UTF-8，如果您为其它编码，请转换后提交
 */

//初始化，设置商户号、秘钥
$a = new SF($memberid,$key);
$a->PutUrl      = 'http://s5yh.com/Pay_Index.html'; //设置提交网关
$a->DeBug       =  true; //debug模式，false为关闭
$a->NotifyUrl   = 'http://'.$_SERVER['HTTP_HOST'].'/server.php'; //设置点对点返回地址
$a->CallbackUrl = 'http://'.$_SERVER['HTTP_HOST'].'/page.php'; //设置页面跳转地址
//设置扩展字段（扩展字段必须为数组）
$a->Reserved = array(
    "pay_reserved1" => '扩展1',
    "pay_reserved2" => '扩展2',
    "pay_reserved3" => '扩展3'
);

//直接跳转到第三方页面支付
//Put->商品名称，商品订单号，订单金额，订单生成时间，银行编码，银行类型，通道
$a->Put('会员服务',$pay_orderid,$pay_amount,$pay_applydate,$bankcode,$banktype,'SYT');



//json调用方式
//$json_a = $a->JsonPut('会员服务',$pay_orderid,1,$pay_applydate,$bankcode,$banktype,'AW');
//print_r(json_decode($json_a,true));
