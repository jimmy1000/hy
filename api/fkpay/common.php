<?php

include './config.php' ;


/*
 * 获取银行简码
 */
function getBankCode()
{
    $bank = $_REQUEST['bank'] ;
    $bankInfo['963'] = 'BOC'  ; //中国银行
    $bankInfo['964'] = 'ABC'  ; //中国农业银行
    $bankInfo['967'] = 'ICBC' ; //中国工商银行
    $bankInfo['965'] = 'CCB' ; //中国建设银行
    $bankInfo['981'] = 'BOCOM' ; //交通银行
    $bankInfo['971'] = 'PSBC' ; //中国邮政储蓄银行
    $bankInfo['970'] = 'CMBC' ; //招商银行
    $bankInfo['980'] = 'CMBCS' ; //中国民生银行
    $bankInfo['962'] = 'ECITIC' ; //中信银行
    $bankInfo['986'] = 'CEBBANK' ; //中国光大银行
    $bankInfo['982'] = 'HXB' ; //华夏银行
    $bankInfo['985'] = 'CGB' ; //广发银行

    if (!isset($bankInfo[$bank])) {
        exit("<script>alert('很抱歉,暂不支持该行...');history.go(-1);</script>");
    }
    return $bankInfo[$bank];
}



/**
 * 获取支付类型
 */
function getPayType()
{
    $pay_type = '' ;
    switch($_REQUEST['type']){
        case 'WECHAT':
            $pay_type = '30';
            break;
        case 'WAP':
            $is_phone = 1;
            $pay_type = '30';
            break;
        case 'ALIPAY':
            $pay_type = 22;
            break;
        case 'ALIPAYWAP':
            $pay_type = '';
            break;
        case 'QQPAY':
            $pay_type = '';
            break;
        case 'QQPAYWAP':
            $pay_type = '';
            break;
        case 'BANKSCAN':
            $pay_type = '';
            break;
    }

    return $pay_type ;
}

//获取版本号
//反扫 3.0
//h5   3.0
//PC   3.1
//wap  3.1
function getVersionNumber($type)
{
    $version = 'V3.1' ;
    if ($type == 'WEIXINCODE') {
        $version = 'V3.0';
    }
    return $version ;
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