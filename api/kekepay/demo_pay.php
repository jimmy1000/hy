<?php
/**
 * ֧�� demo
 */
error_reporting(E_ALL);
ini_set('display_errors', true);
include_once realpath( __DIR__ )."/include/kekepay_class.inc.php";
$kekepay_class = new kekepay_class();

$pram = array();
$pram['orderPrice']    = 200.00;//��������λ��Ԫ����С�������λ
$pram['outTradeNo']    = "201700000000";//�̻�֧��������
$pram['productType']   = "40000301";//��Ʒ����
$pram['orderTime']     = date('Ymdhis',time());//�µ�ʱ�䣬��ʽyyyyMMddHHmmss
$pram['productName']   = "��Ʒ����";//��Ʒ����
$pram['orderIp']       = "198.98.23.1";//�µ�IP
$pram['remark']        = "���Ƕ�����ע";//��ע


//����һ��֧����ť�� ���ӣ��Ѱ�ť���ڶ�����֧��ҳ�棬���֮�������� �ɿ�֧��������̨��
//֧���ɹ�֮�������� ͬ���ص���ַreturn.php
//PS �������̨��֧��ֱ�Ӳ�������֧����ַ���������ȥ��Ҳ���ǲ�֧��get������ô
//���ύ�� �����Լ��� һ����ַ���ٽ��ղ����� ͨ��ҳ�� ������ʽ �ύ��ȥ
$btn = $kekepay_class->pay($pram);

var_dump($btn);






