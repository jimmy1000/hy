<?php
/**
 * ��������ѯ demo
 */

include_once realpath( __DIR__ )."/include/kekepay_class.inc.php";
$kekepay_class = new kekepay_class();

$pram = array();
$pram['outTradeNo']    = "201700000000";//�̻������ţ�T0/T1����״��̻�������ţ�

$res = $kekepay_class->Settlement($pram);

//�ɹ�
if($res['resultCode'] == "0000"){
	//�������ݿ�����Ĵ���¼
	
	exit("��ѯ�ɹ�");
}else{
	die('��ѯʧ�ܣ�ʧ��ԭ��'.$res['errMsg']);
}