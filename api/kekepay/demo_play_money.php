<?php
/**
 * ��� demo
 */

include_once realpath( __DIR__ )."/include/kekepay_class.inc.php";
$kekepay_class = new kekepay_class();

$pram = array();
$pram['outTradeNo']       = "20173333333333";//�̻�T0�������
$pram['orderPrice']       = 100.00;//��������λ��Ԫ����С�������λ
$pram['proxyType']        = "T0";//��������
$pram['productType']      = "WEIXIN";//��Ʒ����
$pram['bankAccountType']  = "PUBLIC_ACCOUNT";//�տ����п�����
$pram['phoneNo']          = "18800000000";//�տ����ֻ���
$pram['receiverName']     = "����";//�տ�������
$pram['certType']         = "IDENTITY";//�տ���֤�����ͣ�IDENTITY ���֤
$pram['certNo']           = "44111111111111111";//�տ������֤��
$pram['receiverAccountNo']= "6220020202020202";//�տ������п���
$pram['bankClearNo']      = "3343434343";//�����������к�
$pram['bankBranchNo']     = "2225454343";//������֧���к�
$pram['bankName']         = "�й���������";//����������
$pram['bankCode']         = "ICBC";//���б��룬ICBC �й���������
$pram['bankBranchName']   = "��������ĳĳ֧��";//������֧������


$res = $kekepay_class->bankCardPay($pram);

//���ɹ�
if($res['resultCode'] == "0000"){
	//�������ݿ�����Ĵ���¼
	
	exit("���ɹ�");
}elseif($res['resultCode'] == "9996"){
	//�������ݿ�����Ĵ���¼
	
	exit("�����");
}else{
	//�������ݿ�����Ĵ���¼
	
	exit("���ʧ��");
}







