<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/4
 * Time: 17:16
 */

$mer_no   = '126071';                              
$callback = 'http://payhy.8889s.com/api/yfbpay/callback.php';
$herfback = 'http://payhy.8889s.com/api/yfbpay/herfback.php';

$pay_url = 'http://api.xueyuplus.com/wbsp/unifiedorder';
$query_url ='http://api.xueyuplus.com/wbsp/querydorder';



$merchant_private_key = '-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAPTfVOwMvegLuwq+
5h+rEEgkEFoJLJ07EULlXDZ6DJvDQrLSsZKzAbMELp2CailSAr0ZhW5+EG7oetFI
v8AF80Fe77nK4DSadaJTsqDCJeL5nzdNQSRFoFZm6HAyFUmtxGRZ9pSN2x+Buywe
sDFUIz/tddpAEZKmjeDlK4OQzKrjAgMBAAECgYEA07Y7oBKsJnHt9y5xnDO0UgsM
MCv6bL5jACl5fogrWoiJpD5R1Pn7oCEfhIL68lq7j27/VDaeUqWhekyhrI34b31u
W+YtXnlqT8CZrluLzqk6wN086Fdd40lEpjfhqgCVBReIx1OSKb95auQp2UqD1gr3
kcjBek/raiUZHktEmyECQQD//kI4Ea3jzE3b+XHj5JRkny0soPkCGz7LObZJYane
ciab4HmgvdB3W5+5bipEQwIodIwUDAxTudJoX/OblYT7AkEA9OD/Vmd1Tns9qmZo
nKlpBEy4DZsXt7H4pTvhZzKbtNT+fy2TsPkReskK4kb2ttRpHJ7Kg/IQtpQSdgV+
wGL9OQJBAL/l8e3mEtx5AsSrsVyMtVDYohyabw/NLL1bffJ0a8p898RR+dstb2CK
Jqnyk2yobq208Gz2uyboXXows4UmCJECQGQTG2aVS1RyeyYLSg9UEI3oSIOO8Wdp
/SKR0TEadpW1Qfh5iNUXRLR4OtF0jNj0/6vpnPvFTl0/MDg5UK6RBckCQAyzv3aK
4z1HuJcdHWIqq0fbZPXZe1haefRVi+h7LmGXdzS8Yn9OMIQtg246PNHIlGvXY4SN
jZtvE8UZpI36QN8=
-----END PRIVATE KEY-----
';

$server_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDJuWvoRtBJ3fLiS2NeYbM7jq9/a
+i/4pcWUAJUFaPWJ6Wy0LO77LDztN23wqm3Wyjh69MxJwKNbHa5ieEjcxjM0AGTaI
jAaWZq+57K9sZXlPmBSRiAyxI03iVtLqB/ZWDNjsMlKHho268PYAgGgtJBZWVWVNv
07Flv3kynJTNkSwIDAQAB
-----END PUBLIC KEY-----
';


/*********************    test ********************************/


//$merchant_private_key = '-----BEGIN PRIVATE KEY-----
//MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAPTfVOwMvegLuwq+
//5h+rEEgkEFoJLJ07EULlXDZ6DJvDQrLSsZKzAbMELp2CailSAr0ZhW5+EG7oetFI
//v8AF80Fe77nK4DSadaJTsqDCJeL5nzdNQSRFoFZm6HAyFUmtxGRZ9pSN2x+Buywe
//sDFUIz/tddpAEZKmjeDlK4OQzKrjAgMBAAECgYEA07Y7oBKsJnHt9y5xnDO0UgsM
//MCv6bL5jACl5fogrWoiJpD5R1Pn7oCEfhIL68lq7j27/VDaeUqWhekyhrI34b31u
//W+YtXnlqT8CZrluLzqk6wN086Fdd40lEpjfhqgCVBReIx1OSKb95auQp2UqD1gr3
//kcjBek/raiUZHktEmyECQQD//kI4Ea3jzE3b+XHj5JRkny0soPkCGz7LObZJYane
//ciab4HmgvdB3W5+5bipEQwIodIwUDAxTudJoX/OblYT7AkEA9OD/Vmd1Tns9qmZo
//nKlpBEy4DZsXt7H4pTvhZzKbtNT+fy2TsPkReskK4kb2ttRpHJ7Kg/IQtpQSdgV+
//wGL9OQJBAL/l8e3mEtx5AsSrsVyMtVDYohyabw/NLL1bffJ0a8p898RR+dstb2CK
//Jqnyk2yobq208Gz2uyboXXows4UmCJECQGQTG2aVS1RyeyYLSg9UEI3oSIOO8Wdp
///SKR0TEadpW1Qfh5iNUXRLR4OtF0jNj0/6vpnPvFTl0/MDg5UK6RBckCQAyzv3aK
//4z1HuJcdHWIqq0fbZPXZe1haefRVi+h7LmGXdzS8Yn9OMIQtg246PNHIlGvXY4SN
//jZtvE8UZpI36QN8=
//-----END PRIVATE KEY-----
//';
//$mer_no = '126071';
//
//$server_public_key = '-----BEGIN PUBLIC KEY-----
//MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDJuWvoRtBJ3fLiS2NeYbM7jq9/a
//+i/4pcWUAJUFaPWJ6Wy0LO77LDztN23wqm3Wyjh69MxJwKNbHa5ieEjcxjM0AGTaI
//jAaWZq+57K9sZXlPmBSRiAyxI03iVtLqB/ZWDNjsMlKHho268PYAgGgtJBZWVWVNv
//07Flv3kynJTNkSwIDAQAB
//-----END PUBLIC KEY-----
//';
//$callback = 'http://payhy.8889s.com/api/yfbpay/callback.php';
//$herfback = 'http://payhy.8889s.com/api/yfbpay/herfback.php';


/*********************         end test      *********************/

/**
 * 查询订单状态
 * @param type $orderid
 * @return boolean
 */
function queryOrder($orderid = ''){
	 $merchant_private_key = file_get_contents('xfpay_private_key'); // 商户私钥
         $web_public_key = file_get_contents('service_public_key'); // 平台公钥
	 $seller_id = $mer_no;
	 $parameter = array(
		'seller_id'         => $seller_id,
		'out_trade_no'      => $orderid
	 );
	$merchant_private_key= openssl_get_privatekey($merchant_private_key);
	openssl_sign(getSignStr($parameter),$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);
	$parameter['sign'] = base64_encode($sign_info);
	$resp = http_poststr("http://api.xueyuplus.com/wbsp/querydorder", json_encode($parameter));
	$res =  decodeUnicode(json_encode($resp));
        if($res['pay_state'] == 1){
            return true;
        }
        return false;       
}


function formatBizQueryParaMap($paraMap)
{
	$buff = "";
	ksort($paraMap);
	foreach ($paraMap as $k => $v)
	{
		if($v != null && $v != ''){
			$buff .= $k . "=" . $v . "&";
		}
	}
	$reqPar = '';
	if (strlen($buff) > 0)
	{
		$reqPar = substr($buff, 0, strlen($buff)-1);
	}
	return urlencode($reqPar);
}

/**
 * 	作用：生成签名
 */
function getSignStr($Obj)
{
	foreach ($Obj as $k => $v)
	{
		if($v != '' && $k != 'sign'){
			$Parameters[$k] = $v;
		}
	}
	ksort($Parameters);
	return urldecode(formatBizQueryParaMap($Parameters));
}

function array_remove($data, $key){
	if(!array_key_exists($key, $data)){
		return $data;
	}
	$keys = array_keys($data);
	$index = array_search($key, $keys);
	if($index !== FALSE){
		array_splice($data, $index, 1);
	}
	return $data;
}
function http_poststr($url, $data_string) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json; charset=utf-8',
		'Content-Length: ' . strlen($data_string))
	);
	ob_start();
	curl_exec($ch);
	$return_content = ob_get_contents();
	ob_end_clean();
	return $return_content;
}
function decodeUnicode($str)
{
    return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
        create_function(
            '$matches',
            'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
        ),
        $str);
}