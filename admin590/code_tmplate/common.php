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
        <?php foreach($global['type_name'] as $key => $value): ?>
<?php if($value == '') continue; ?>
//<?= $global['type_note'][$key] . PHP_EOL ?>
        case '<?= $value ?>':
			$serviceType = array(
				'urlType' => '<?= $global['type_url'][$key] ?>',
				'code' => '<?= $global['type_value'][$key] ?>',
				'desc' => '<?= $global['type_note'][$key] ?>',
				'action' => '<?= $global['type_action'][$key] ?>',
			);
			break;
        <?php endforeach; ?>
        default:
			returnError();
    }

    return $serviceType;
}

//$_REQUEST['bank']  银行卡编号

function getBankAbbreviation($bankNum){
	$bankNumArr = array(
        <?php foreach($global['bank_value'] as $key => $value): ?>
        <?php if($global['bank_key'][$key] == '') continue; ?>
		'<?= $global['bank_key'][$key] ?>' => '<?= $value ?>', <?= "\t\t\t\t" ?>	//<?= $global['bank_note'][$key] . PHP_EOL ?>
        <?php endforeach; ?>
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
<?= input_value(':' . $sign['function']) ?>

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
