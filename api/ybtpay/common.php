<?php
/**
 * 常用函数文件
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:52
 */


/**
 * 获取支付类型
 *
 */
function get_paytype(&$words)
{
    $payType = '' ;
    $type    = $_REQUEST['type'] ;
    switch ($type) {

        case 'WAP' :
            $payType = 'wechatH5' ;
            $words = '微信';
            break ;
        case 'ALIPAYWAP' :
            $payType = 'aliH5' ;
            $words = '支付宝';
            break ;
        case 'BANK' :
            $payType = 'B2C' ;
            $words = '网银';
            break ;
        case 'BANKWAP' :
            $payType = 'B2C' ;
            $words = '网银';
            break ;

        default:
            break ;
    }

    return $payType ;
}

/**
 * 创建签名
 * @param $data
 * @param $key
 */
function create_sign($data,$key='')
{
    $str = md5("version={$data['version']}&merchantNum={$data['merchantNum']}&nonce_str={$data['nonce_str']}&merMark={$data['merMark']}&client_ip={$data['client_ip']}&payType={$data['payType']}&orderNum={$data['orderNum']}&amount={$data['amount']}&body={$data['body']}&key={$key}");
    return strtoupper($str) ;
}

/**
 * 验证签名
 */
function verify_key($key='')
{
    $data = $_REQUEST  ;
    $check_sign = strtoupper(md5( "merchantNum={$data['merchantNum']}&orderNum={$data['merchantNum']}&amount={$data['merchantNum']}&nonce_str={$data['merchantNum']}&orderStatus={$data['merchantNum']}&key={$key}" )) ;
    return ($check_sign == $data['sign']) ;
}

/*
 * 获取银行简码
 */
function getBankCode()
{
    $bank = $_REQUEST['bank'] ;
    $bankInfo['962'] = 'CITIC' ; //中信银行
    $bankInfo['963'] = 'BOC'  ; //中国银行
    $bankInfo['964'] = 'ABC'  ; //中国农业银行
    $bankInfo['965'] = 'CCB'  ; //中国建设银行
    $bankInfo['967'] = 'ICBC' ; //中国工商银行
    $bankInfo['968'] = 'CZSB' ; //浙商银行
    $bankInfo['970'] = 'CMB'  ; //招商银行
    $bankInfo['971'] = 'POST' ; //中国邮政储蓄银行
    $bankInfo['972'] = 'CIB'  ; //兴业银行
    $bankInfo['975'] = 'SHB' ; //上海银行
    $bankInfo['977'] = 'SPDB' ; //浦发银行
    $bankInfo['978'] = 'PAB'  ; //平安银行
    $bankInfo['980'] = 'CMBC' ; //中国民生银行
    $bankInfo['981'] = 'BCOM' ; //交通银行
    $bankInfo['982'] = 'HXB'  ; //华夏银行
    $bankInfo['985'] = 'GDB'  ; //广发银行
    $bankInfo['986'] = 'CEB'  ; //中国光大银行
    $bankInfo['988'] = 'CBHB' ; //渤海银行
    $bankInfo['989'] = 'BOB' ; //北京银行

    if (!isset($bankInfo[$bank])) {
        exit("<script>alert('很抱歉,暂不支持该行...');history.go(-1);</script>");
    }
    return $bankInfo[$bank];
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



function dd($arr)
{
    echo '<pre>';
    print_r($arr) ;
    die;
}