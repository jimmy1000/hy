<?php
/**
 * ֧�������ѯ demo
 */

include_once realpath( __DIR__ )."/include/kekepay_class.inc.php";
$kekepay_class = new kekepay_class();

$pram = array();
$pram['outTradeNo']    = "201700000000";//�̻�֧��������

$res = $kekepay_class->searchPay($pram);

if($res['status'] == 200){
	echo "�ɹ������ݣ�<br/>";
	var_dump($res['data']);
	die('<br/>��ȡ�ɹ�');
}else{
	die('��ȡʧ�ܣ�ʧ��ԭ��'.$res['msg']);
}