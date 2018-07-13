<?php

include 'config.php' ;
/**
 * 获取支付类型
 */

    $pay_type = '' ;
    switch($_REQUEST['type']){
        case 'WECHAT':
            $pay_type = 'wxcode';
            break;
        case 'WAP':
            $is_phone = 1;
            $pay_type = 'wxwap ';
            break;
        case 'ALIPAY':
            $pay_type = "alipay";
            break;
        case 'ALIPAYWAP':
            $pay_type = 'alipaywap';
            break;
        case 'QQPAY':
            $pay_type = 'qqpay';
            break;
        case 'QQPAYWAP':
            $pay_type = '';
            break;
        case 'BANKSCAN':
            $pay_type = '';
            break;
        case 'BANK':
            $pay_typeArray = array(
                '962' => 'ECITIC',	//中信银行
                '963' => 'BOC',	//中国银行
                '964' => 'ABC',	//农业银行
                '965' => 'CCB',	//建设银行
                '967' => 'ICBC',	//工商银行
                '968' => '',	//浙商银行
                '969' => '',	//浙江稠州商业银行
                '970' => 'CMB',	//招商银行
                '971' => 'PSBC',	//邮政储蓄
                '972' => 'CIB',	//兴业银行
                '973' => '',	//顺德农村信用合作社
                '974' => 'SPA',	//深圳发展银行
                '975' => 'BOS',	//上海银行
                '976' => '',	//上海农村商业银行
                '977' => '',	//浦发银行
                '978' => 'PAB',	//平安银行
                '979' => 'NJCB',	//南京银行
                '980' => 'CMBC',	//民生银行
                '981' => 'COMM',	//交通银行
                '982' => 'HXB',	//华夏银行
                '983' => 'HZCB',	//杭州银行
                '984' => '',	//广州市农村信用社
                '985' => 'GDB',	//广发银行
                '986' => 'CEB',	//光大银行
                '987' => '',	//东亚银行
                '988' => '',	//渤海银行
                '989' => 'BCCB',	//北京银行
                '990' => '',	//北京农村商业银行
            );
            $pay_type= $pay_typeArray[$_REQUEST['bank']];
            break;

}

//获取版本号
//反扫 3.0
//h5   3.0
//PC   3.1
#	产品通用接口正式请求地址
#	产品通用接口测试请求地址
$reqURL_onLine = "http://kuaifuvip.com/GateWay/ReceiveBank.aspx";

# 业务类型
# 支付请求，固定值"Buy" .
$p0_Cmd = "Buy";

#	送货地址
# 为"1": 需要用户将送货地址留在API支付系统;为"0": 不需要，默认为 "0".
$p9_SAF = "0";



/**
 * 返回本地请求地址

/**
 * POST访问
 * @param $url
 * @param $data
 * @return mixed|string
 */
 function submitPostData($url,$data) {
    $ch = curl_init() ;
    curl_setopt($ch, CURLOPT_URL, $url) ;
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST") ;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE) ;
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE) ;
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)') ;
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1) ;
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1) ;
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data) ;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;
    $tmpInfo = curl_exec($ch) ;
    if ( curl_errno($ch) ) {
        return curl_error($ch) ;
    }
    return $tmpInfo;
}

/**
 * 表单方式
 */
 function submitFormMethod($params, $gateway, $method = 'post', $charset = 'utf-8') {
    header("Cache-Control: no-cache");
    header("Pragma: no-cache");
    header("Content-type:text/html;charset={$charset}");
    $sHtml = "<form id='paysubmit' name='paysubmit' action='{$gateway}' method='{$method}'>";

    foreach ($params as $k => $v) {
        $sHtml.= "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
    }

    $sHtml = $sHtml . "</form>正在提交支付数据 ...";

    $sHtml = $sHtml . "<script>document.forms['paysubmit'].submit();</script>";

    return $sHtml;
}


function isMobile() {
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset($_SERVER['HTTP_VIA'])) {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile','MicroMessenger');
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}


function dd($arr)
{
    echo '<pre>';
    print_r($arr) ;
    die;
}

function getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse)
{
    global $p0_Cmd;
    global $p9_SAF;
    include 'config.php';

    #进行签名处理，一定按照文档中标明的签名顺序进行
    $sbOld = "";
    #加入业务类型
    $sbOld = $sbOld.$p0_Cmd;
    #加入商户编号
    $sbOld = $sbOld.$p1_MerId;
    #加入商户订单号
    $sbOld = $sbOld.$p2_Order;
    #加入支付金额
    $sbOld = $sbOld.$p3_Amt;
    #加入交易币种
    $sbOld = $sbOld.$p4_Cur;
    #加入商品名称
    $sbOld = $sbOld.$p5_Pid;
    #加入商品分类
    $sbOld = $sbOld.$p6_Pcat;
    #加入商品描述
    $sbOld = $sbOld.$p7_Pdesc;
    #加入商户接收支付成功数据的地址
    $sbOld = $sbOld.$p8_Url;
    #加入送货地址标识
    $sbOld = $sbOld.$p9_SAF;
    #加入商户扩展信息
    $sbOld = $sbOld.$pa_MP;
    #加入支付通道编码
    $sbOld = $sbOld.$pd_FrpId;
    #加入是否需要应答机制
    $sbOld = $sbOld.$pr_NeedResponse;
    logstr($p2_Order,$sbOld,HmacMd5($sbOld,$merchantKey));
    return HmacMd5($sbOld,$merchantKey);

}

function getCallbackHmacString($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType)
{

    include 'config.php';

    #取得加密前的字符串
    $sbOld = "";
    #加入商家ID
    $sbOld = $sbOld.$p1_MerId;
    #加入消息类型
    $sbOld = $sbOld.$r0_Cmd;
    #加入业务返回码
    $sbOld = $sbOld.$r1_Code;
    #加入交易ID
    $sbOld = $sbOld.$r2_TrxId;
    #加入交易金额
    $sbOld = $sbOld.$r3_Amt;
    #加入货币单位
    $sbOld = $sbOld.$r4_Cur;
    #加入产品Id
    $sbOld = $sbOld.$r5_Pid;
    #加入订单ID
    $sbOld = $sbOld.$r6_Order;
    #加入用户ID
    $sbOld = $sbOld.$r7_Uid;
    #加入商家扩展信息
    $sbOld = $sbOld.$r8_MP;
    #加入交易结果返回类型
    $sbOld = $sbOld.$r9_BType;

    logstr($r6_Order,$sbOld,HmacMd5($sbOld,$merchantKey));
    return HmacMd5($sbOld,$merchantKey);

}


#	取得返回串中的所有参数
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

//需要配置环境支持iconv，否则中文参数不能正常处理
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
