<?php
/**
 * 打款 demo
 */

include_once realpath( __DIR__ )."/include/kekepay_class.inc.php";
$kekepay_class = new kekepay_class();

$pram = array();
$pram['outTradeNo']       = "20173333333333";//商户T0出款订单号
$pram['orderPrice']       = 100.00;//订单金额，单位：元保留小数点后两位
$pram['proxyType']        = "T0";//交易类型
$pram['productType']      = "WEIXIN";//产品类型
$pram['bankAccountType']  = "PUBLIC_ACCOUNT";//收款银行卡类型
$pram['phoneNo']          = "18800000000";//收款人手机号
$pram['receiverName']     = "张三";//收款人姓名
$pram['certType']         = "IDENTITY";//收款人证件类型，IDENTITY 身份证
$pram['certNo']           = "44111111111111111";//收款人身份证号
$pram['receiverAccountNo']= "6220020202020202";//收款人银行卡号
$pram['bankClearNo']      = "3343434343";//开户行清算行号
$pram['bankBranchNo']     = "2225454343";//开户行支行行号
$pram['bankName']         = "中国工商银行";//开户行名称
$pram['bankCode']         = "ICBC";//银行编码，ICBC 中国工商银行
$pram['bankBranchName']   = "工商银行某某支行";//开户行支行名称


$res = $kekepay_class->bankCardPay($pram);

//打款成功
if($res['resultCode'] == "0000"){
	//更新数据库里面的打款记录
	
	exit("打款成功");
}elseif($res['resultCode'] == "9996"){
	//更新数据库里面的打款记录
	
	exit("打款中");
}else{
	//更新数据库里面的打款记录
	
	exit("打款失败");
}







