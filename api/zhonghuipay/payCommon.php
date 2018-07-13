<?php
include 'config.php';

 	
	$reqURL_onLine = "http://www.vip6229.com/GateWay/ReceiveBank.aspx/ReceiveBank.aspx";
	$p0_Cmd = "Buy";
	$p9_SAF = "0";
	
#ǩ����������ǩ����
function getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse)
{
  global $p0_Cmd;
  global $p9_SAF;
	include 'config.php';
		
	#����ǩ������һ�������ĵ��б�����ǩ��˳�����
  $sbOld = "";
  #����ҵ������
  $sbOld = $sbOld.$p0_Cmd;
  #�����̻����
  $sbOld = $sbOld.$p1_MerId;
  #�����̻�������
  $sbOld = $sbOld.$p2_Order;     
  #����֧�����
  $sbOld = $sbOld.$p3_Amt;
  #���뽻�ױ���
  $sbOld = $sbOld.$p4_Cur;
  #������Ʒ����
  $sbOld = $sbOld.$p5_Pid;
  #������Ʒ����
  $sbOld = $sbOld.$p6_Pcat;
  #������Ʒ����
  $sbOld = $sbOld.$p7_Pdesc;
  #�����̻�����֧���ɹ����ݵĵ�ַ
  $sbOld = $sbOld.$p8_Url;
  #�����ͻ���ַ��ʶ
  $sbOld = $sbOld.$p9_SAF;
  #�����̻���չ��Ϣ
  $sbOld = $sbOld.$pa_MP;
  #����֧��ͨ������
  $sbOld = $sbOld.$pd_FrpId;
  #�����Ƿ���ҪӦ�����
  $sbOld = $sbOld.$pr_NeedResponse;
	logstr($p2_Order,$sbOld,HmacMd5($sbOld,$merchantKey));
  return HmacMd5($sbOld,$merchantKey);
  
} 

function getCallbackHmacString($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType)
{
  
	include 'config.php';
  
	#ȡ�ü���ǰ���ַ���
	$sbOld = "";
	#�����̼�ID
	$sbOld = $sbOld.$p1_MerId;
	#������Ϣ����
	$sbOld = $sbOld.$r0_Cmd;
	#����ҵ�񷵻���
	$sbOld = $sbOld.$r1_Code;
	#���뽻��ID
	$sbOld = $sbOld.$r2_TrxId;
	#���뽻�׽��
	$sbOld = $sbOld.$r3_Amt;
	#������ҵ�λ
	$sbOld = $sbOld.$r4_Cur;
	#�����ƷId
	$sbOld = $sbOld.$r5_Pid;
	#���붩��ID
	$sbOld = $sbOld.$r6_Order;
	#�����û�ID
	$sbOld = $sbOld.$r7_Uid;
	#�����̼���չ��Ϣ
	$sbOld = $sbOld.$r8_MP;
	#���뽻�׽����������
	$sbOld = $sbOld.$r9_BType;

	logstr($r6_Order,$sbOld,HmacMd5($sbOld,$merchantKey));
	return HmacMd5($sbOld,$merchantKey);

}


#	ȡ�÷��ش��е����в���
function getCallBackValue(&$r0_Cmd,&$r1_Code,&$r2_TrxId,&$r3_Amt,&$r4_Cur,&$r5_Pid,&$r6_Order,&$r7_Uid,&$r8_MP,&$r9_BType,&$hmac)
{  
	$r0_Cmd		= $_REQUEST['r0_Cmd'];
	$r1_Code	= $_REQUEST['r1_Code'];
	$r2_TrxId	= $_REQUEST['r2_TrxId'];
	$r3_Amt		= $_REQUEST['r3_Amt'];
	$r4_Cur		= $_REQUEST['r4_Cur'];
	$r5_Pid		= $_REQUEST['r5_Pid'];
	$r6_Order	= $_REQUEST['r6_Order'];
	$r7_Uid		= $_REQUEST['r7_Uid'];
	$r8_MP		= $_REQUEST['r8_MP'];
	$r9_BType	= $_REQUEST['r9_BType']; 
	$hmac			= $_REQUEST['hmac'];
	
	return null;
}

function CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac)
{
	if($hmac==getCallbackHmacString($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType))
		return true;
	else
		return false;
}
		
  
function HmacMd5($data,$key)
{
// RFC 2104 HMAC implementation for php.
// Creates an md5 HMAC.
// Eliminates the need to install mhash to compute a HMAC
// Hacked by Lance Rushing(NOTE: Hacked means written)

//��Ҫ���û���֧��iconv���������Ĳ���������������
$key = iconv("GB2312","UTF-8",$key);
$data = iconv("GB2312","UTF-8",$data);

$b = 64; // byte length for md5
if (strlen($key) > $b) {
$key = pack("H*",md5($key));
}
$key = str_pad($key, $b, chr(0x00));
$ipad = str_pad('', $b, chr(0x36));
$opad = str_pad('', $b, chr(0x5c));
$k_ipad = $key ^ $ipad ;
$k_opad = $key ^ $opad;

return md5($k_opad . pack("H*",md5($k_ipad . $data)));
}

function logstr($orderid,$str,$hmac)
{
include 'config.php';
$james=fopen($logName,"a+");
fwrite($james,"\r\n".date("Y-m-d H:i:s")."|orderid[".$orderid."]|str[".$str."]|hmac[".$hmac."]");
fclose($james);
}



function getServiceType($type){
    switch ($type){
        //支付宝
        case 'ALIPAY':
            $serviceType = array(
                'urlType' => '',
                'code' => 'alipay',
                'desc' => '支付宝',
                'action' => '',
            );
            break;
        //支付宝app
        case 'ALIPAYWAP':
            $serviceType = array(
                'urlType' => '',
                'code' => 'wxwap',
                'desc' => '支付宝app',
                'action' => 'jump',
            );
            break;
        //支付宝付款码
        case 'ALIPAYCODE':
            $serviceType = array(
                'urlType' => '',
                'code' => '',
                'desc' => '支付宝付款码',
                'action' => 'jump',
            );
            break;
        //微信
        case 'WECHAT':
            $serviceType = array(
                'urlType' => '',
                'code' => 'wxcode',
                'desc' => '微信',
                'action' => '',
            );
            break;
        //微信App
        case 'WAP':
            $serviceType = array(
                'urlType' => '',
                'code' => 'wxapp',
                'desc' => '微信App',
                'action' => 'jump',
            );
            break;
        //微信付款码
        case 'WEIXINCODE':
            $serviceType = array(
                'urlType' => '',
                'code' => '',
                'desc' => '微信付款码',
                'action' => 'jump',
            );
            break;
        //财付通
        case 'TENPAY':
            $serviceType = array(
                'urlType' => '',
                'code' => '',
                'desc' => '财付通',
                'action' => '',
            );
            break;
        //QQ扫码方式
        case 'QQPAY':
            $serviceType = array(
                'urlType' => '',
                'code' => '',
                'desc' => 'QQ扫码方式',
                'action' => '',
            );
            break;
        //手机QQ钱包方式
        case 'QQWAPPAY':
            $serviceType = array(
                'urlType' => '',
                'code' => 'tenpay',
                'desc' => '手机QQ钱包方式',
                'action' => 'jump',
            );
            break;
        //京东
        case 'JDPAY':
            $serviceType = array(
                'urlType' => '',
                'code' => '',
                'desc' => '京东',
                'action' => '',
            );
            break;
        //京东app
        case 'JDPAYWAP':
            $serviceType = array(
                'urlType' => '',
                'code' => '',
                'desc' => '京东app',
                'action' => 'jump',
            );
            break;
        //点卡
        case 'DIANKAPAY':
            $serviceType = array(
                'urlType' => '',
                'code' => '',
                'desc' => '点卡',
                'action' => 'jump',
            );
            break;
        //银行
        case 'BANK':
            $serviceType = array(
                'urlType' => '',
                'code' => getBankAbbreviation($_REQUEST['bank']) . '-KJ',
                'desc' => '银行',
                'action' => 'jump',
            );
            break;
        //网银WAP
        case 'BANKWAP':
            $serviceType = array(
                'urlType' => '',
                'code' => 'unionpay',
                'desc' => '网银WAP',
                'action' => 'jump',
            );
            break;
        //网银扫码
        case 'BANKSCAN':
            $serviceType = array(
                'urlType' => '',
                'code' => '',
                'desc' => '网银扫码',
                'action' => '',
            );
            break;
        //网银快捷
        case 'BANKQUICK':
            $serviceType = array(
                'urlType' => '',
                'code' => '',
                'desc' => '网银快捷',
                'action' => 'jump',
            );
            break;
        //美团
        case 'MEITUANPAY':
            $serviceType = array(
                'urlType' => '',
                'code' => '',
                'desc' => '美团',
                'action' => '',
            );
            break;
        default:
            returnError();
    }

    return $serviceType;
}

//$_REQUEST['bank']  银行卡编号

function getBankAbbreviation($bankNum){
    $bankNumArr = array(
        '962' => 'ECITIC', 					//中信银行
        '963' => 'BOC', 					//中国银行
        '964' => 'ABC', 					//农业银行
        '965' => 'CCB', 					//建设银行
        '967' => 'ICBC', 					//工商银行
        '968' => 'CMBCHINA', 					//浙商银行
        '969' => '', 					//浙江稠州商业银行
        '970' => '', 					//招商银行
        '971' => 'POST', 					//邮政储蓄
        '972' => '', 					//兴业银行
        '973' => '', 					//顺德农村信用合作社
        '974' => 'SDB', 					//深圳发展银行
        '975' => '', 					//上海银行
        '976' => '', 					//上海农村商业银行
        '977' => 'SPDB', 					//浦发银行
        '978' => 'PINGANBANK', 					//平安银行
        '979' => '', 					//南京银行
        '980' => 'CMBC', 					//民生银行
        '981' => 'BOCO', 					//交通银行
        '982' => 'HXB', 					//华夏银行
        '983' => '', 					//杭州银行
        '984' => '', 					//广州市农村信用社
        '985' => 'GDB', 					//广发银行
        '986' => 'CEB', 					//光大银行
        '987' => '', 					//东亚银行
        '988' => '', 					//渤海银行
        '989' => '', 					//北京银行
        '990' => '', 					//北京农村商业银行
    );

    if(!$bankNumArr[$bankNum]){
        returnError();
    }

    return $bankNumArr[$bankNum];
}

/**
 * 返回本地请求地址
 */
function getLocalRequestUrl()
{
    $pay_url = '' ;
    //记录支付处理地址
    if ( !$_SERVER['HTTP_REFERER'] ) {
        $pay_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'] ;
    } else {
        $pay_url = $_SERVER['HTTP_REFERER'] ;
    }
    return $pay_url ;
}

function returnError(){
    echo "<p style='text-align:center;color:#ff0000;'>您好，该支付方式已被关闭，请选择其它支付方式，或稍后访问！<a href='/index.html'>【返回首页】</a></p>";
    exit;
}

?> 