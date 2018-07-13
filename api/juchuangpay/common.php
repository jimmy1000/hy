<?php

/**
 * 常用函数文件
 */


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


/**
 * 获取支付方式
 * @param $type
 */

function getServiceType($type){
    switch ($type){
        //支付宝
        case 'ALIPAY':
			$serviceType = array(
				'urlType' => 'ali',
				'code' => 'alipay',
				'desc' => '支付宝',
				'action' => '',
			);
			break;
        //支付宝app
        case 'ALIPAYWAP':
			$serviceType = array(
				'urlType' => 'ali',
				'code' => 'alipay',
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
				'code' => '',
				'desc' => '微信',
				'action' => '',
			);
			break;
        //微信App
        case 'WAP':
			$serviceType = array(
				'urlType' => '',
				'code' => '',
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
				'urlType' => 'qq',
				'code' => '8',
				'desc' => 'QQ扫码方式',
				'action' => '',
			);
			break;
        //手机QQ钱包方式
        case 'QQWAPPAY':
			$serviceType = array(
				'urlType' => '',
				'code' => '',
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
				'code' => '',
				'desc' => '银行',
				'action' => 'jump',
			);
			break;
        //网银WAP
        case 'BANKWAP':
			$serviceType = array(
				'urlType' => '',
				'code' => '',
				'desc' => '网银WAP',
				'action' => 'jump',
			);
			break;
        //网银扫码
        case 'BANKSCAN':
			$serviceType = array(
				'urlType' => 'qq',
				'code' => '32',
				'desc' => '网银扫码',
				'action' => '',
			);
			break;
        //网银快捷
        case 'BANKQUICK':
			$serviceType = array(
				'urlType' => 'kj',
				'code' => 'ONL0003',
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
                		'962' => '', 					//中信银行
                		'963' => '', 					//中国银行
                		'964' => '', 					//农业银行
                		'965' => '', 					//建设银行
                		'967' => '', 					//工商银行
                		'968' => '', 					//浙商银行
                		'969' => '', 					//浙江稠州商业银行
                		'970' => '', 					//招商银行
                		'971' => '', 					//邮政储蓄
                		'972' => '', 					//兴业银行
                		'973' => '', 					//顺德农村信用合作社
                		'974' => '', 					//深圳发展银行
                		'975' => '', 					//上海银行
                		'976' => '', 					//上海农村商业银行
                		'977' => '', 					//浦发银行
                		'978' => '', 					//平安银行
                		'979' => '', 					//南京银行
                		'980' => '', 					//民生银行
                		'981' => '', 					//交通银行
                		'982' => '', 					//华夏银行
                		'983' => '', 					//杭州银行
                		'984' => '', 					//广州市农村信用社
                		'985' => '', 					//广发银行
                		'986' => '', 					//光大银行
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

function returnError(){
	echo "<p style='text-align:center;color:#ff0000;'>您好，该支付方式已被关闭，请选择其它支付方式，或稍后访问！<a href='/index.html'>【返回首页】</a></p>";
	exit;
}

/**
 * 生成sign
 * @param $data
 * @return string
 */
function create_sign($data, $sign_array = []){
    //前排序
    ksort($data);
    if($sign_array){
		$data += $sign_array;
	}
    //后排序
    //ksort($data);

    $sign_str = '';
    foreach ($data as $key => $val){
		if($val == ''){
			continue;
		}
        $sign_str .= $key . '=' . $val . '&';
    }

//  strtolower小写
//  strtoupper大写
    return md5(rtrim($sign_str, '&'));
}

function postSend($urlString, $dataArray, $isJsonData = false){
	if($isJsonData){
		$dataArray = json_encode($dataArray);
	}
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$urlString);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataArray);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    return $data;
}

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


function ccb_curl_calls($curl, $data, $https = true)
{

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
	curl_setopt($ch, CURLOPT_TIMEOUT, 300);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_ENCODING, FALSE);
	curl_setopt($ch, CURLOPT_HEADER, false);
	if ($https) {
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	}
	$data = json_encode($data, JSON_UNESCAPED_UNICODE);

	//rwlog('ccb_mch_in',$data);
	$httpHeaders = array(
		'Content-Type: application/json; charset=utf-8',
		"Accept: application/json",
		'Content-Length: ' . strlen($data)
	);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_URL, $curl);
	curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
	$str = curl_exec($ch);
	curl_close($ch);
	return $str;
}

//字符串转16进制ascii码
function strToHex($string){
	$hex='';
	for ($i=0; $i < strlen($string); $i++)
	{
		$hex .= dechex(ord($string[$i]));
	}
	return strtoupper($hex);
}

