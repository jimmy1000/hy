<?php
/**
 * 常用函数文件
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:52
 */


/**
 * 创建签名
 * @param $data
 * @param $key
 */
function create_sign($data,$sign_key)
{
    $sign = '' ;
     ksort($data) ;
    foreach ($data as $key=>$val) {
        $sign .= $key.'='.$val.'&';
    }
    $sign = substr($sign,0,-1) ;
    $sign .=$sign_key ;
    return (md5($sign)) ;
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


/**
 * 获取支付方式
 * @param $type
 */
//微信：
//    1=PC 扫码支付，
//    2=手机 WAP 支付,
//    3=手机公众号支付（支付宝网关）
//    4=QQ 钱包
//    5=京东钱包
//支付宝：
//    1=PC 扫码支付
//    2=手机 WAP 支付
//    3=支付宝网关
//网银支付
//*/
function getServiceType($type){
    switch ($type){
		case 'ALIPAY':
			$serviceType = array(
				'urlType' => 'zhifubao',
				'code' => '1',
				'desc' => '支付宝',
				'action' => '',
			);
			break;
		case 'ALIPAYWAP':
			$serviceType = array(
				'urlType' => 'zhifubao',
				'code' => '2',
				'desc' => '支付宝',
				'action' => 'jump',
			);
			break;
		case 'WECHAT':
			$serviceType = array(
				'urlType' => 'weixin',
				'code' => '1',
				'desc' => '微信',
				'action' => '',
			);
			break;
		case 'WAP':
			$serviceType = array(
				'urlType' => 'weixin',
				'code' => '2',
				'desc' => '微信',
				'action' => 'jump',
			);
			break;
        case 'QQPAY':
            $serviceType = array(
                'urlType' => 'weixin',
                'code' => '4',
                'desc' => 'QQ钱包',
				'action' => '',
            );
            break;
        case 'JDPAY':
            $serviceType = array(
                'urlType' => 'weixin',
                'code' => '5',
                'desc' => '京东',
				'action' => '',
            );
            break;
		case 'BANKSCAN':
			//扫码支付
			$serviceType = array(
				'urlType' => 'wangyin',
				'code' => 'YLBILL',
				'desc' => '网银',
				'action' => '',
			);
			break;
		case 'BANKQUICK':
			//快捷支付
			$serviceType = array(
				'urlType' => 'wangyin',
				'code' => 'QUICK',
				'desc' => '网银快捷',
				'action' => 'jump',
			);
			break;
		case 'BANKWAP':
			//wap
			$serviceType = array(
				'urlType' => 'wangyin',
				'code' => 'YLBILLWAP',
				'desc' => '网银',
				'action' => 'jump',
			);
			break;
        case 'BANK':
        	//在线支付
            $serviceType = array(
                'urlType' => 'wangyin',
                'code' => getBankAbbreviation($_REQUEST['bank']),
                'desc' => '网银',
				'action' => 'jump',
            );
            break;
        default:
			returnError();
            break;
    }

    return $serviceType;
}

//$_REQUEST['bank']  银行卡编号

function getBankAbbreviation($bankNum){
	$bankNumArr = array(
		'962' => 'ECITIC',	//中信银行
		'963' => 'BOC',		//中国银行
		'964' => 'ABC',		//农业银行
		'965' => 'CCB',		//建设银行
		'967' => 'ICBC',	//工商银行
		'968' => 'CZB', 	//浙商银行
		'969' => '', 		//浙江稠州商业银行
		'970' => 'CMBCHINA',//招商银行
		'971' => 'POST', 	//邮政储蓄
		'972' => 'CIB', 	//兴业银行
		'973' => '', 		//顺德农村信用合作社
		'974' => '', 		//深圳发展银行
		'975' => 'SHB',		//上海银行
		'976' => '', 		//上海农村商业银行
		'977' => 'SPDB', 	//浦发银行
		'978' => 'PINGAN',	//平安银行
		'979' => '', 		//南京银行
		'980' => 'CMBC',	//民生银行
		'981' => 'BOCO',	//交通银行
		'982' => 'HXB', 	//华夏银行
		'983' => '', 		//杭州银行
		'984' => '', 		//广州市农村信用社
		'985' => 'CGB',		//广东发展银行   广发银行
		'986' => 'CEB',		//光大银行
		'987' => '',		//东亚银行
		'988' => '',		//渤海银行
		'989' => 'BCCB',	//北京银行
		'990' => 'BJRCB',	//北京农村商业银行
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
function createSign($data){
    $sign = '';
    foreach ($data as $key => $val){
//            $sign .= $key . '={' . $val . '}&';
            $sign .= $key . '=' . $val . '&';
    }
    return strtolower(md5(rtrim($sign, '&')));
}

function postSend($urlString, $dataArray){
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