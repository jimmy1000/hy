<?php
/**
 * ֧���ɹ����첽ҳ�棨�û���������ҳ�棩
 */

include_once realpath( __DIR__ )."/../include/kekepay_class.inc.php";
$kekepay_class = new kekepay_class();

$pram = array();
$pram['payKey']       = trim($_REQUEST['payKey']);//�̻�֧��Key
$pram['orderPrice']   = $_REQUEST['orderPrice'];//��������λ��Ԫ����С�������λ
$pram['outTradeNo']   = trim($_REQUEST['outTradeNo']);//�̻�������
$pram['productType']  = trim($_REQUEST['productType']);//��Ʒ����
$pram['orderTime']    = trim($_REQUEST['orderTime']);//�µ�ʱ�䣬��ʽyyyyMMddHHmmss
$pram['tradeStatus']  = trim($_REQUEST['tradeStatus']);//����״̬
$pram['successTime']  = trim($_REQUEST['successTime']);//�ɹ�ʱ�䣬��ʽyyyyMMddHHmmss
$pram['trxNo']        = trim($_REQUEST['trxNo']);//������ˮ��

if(isset($_REQUEST['remark']) && !empty($_REQUEST['remark'])){
	$pram['remark'] = trim($_REQUEST['remark']);//������ע
}
if(isset($_REQUEST['productName']) && !empty($_REQUEST['productName'])){
	$pram['productName']  = trim($_REQUEST['productName']);//��Ʒ����
}
//���¼��ܹ���� sign
$_sign = $kekepay_class->resSign($pram);
$sign = $_REQUEST['sign'];

//Ϊ�˰�ȫ������ж�signֵ�Ƿ���ȷ
if($_sign != $sign){
	//У��ʧ��
	exit;
}

//֧���ɹ�
if($pram['tradeStatus'] == "SUCCESS"){
	//�������ݿ�����Ķ��� ֧��״̬
	
	
	//���߽ӿڲ���������������
	echo "SUCCESS";exit;
}











