<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/4
 * Time: 17:16
 */

$mer_no   = '126072';                              
$callback = 'http://payhy.8889s.com/api/xfpay/callback.php';
$herfback = 'http://payhy.8889s.com/api/xfpay/herfback.php';

$pay_url   = 'http://api.xueyuplus.com/wbsp/unifiedorder';
$query_url ='http://api.xueyuplus.com/wbsp/querydorder';


$merchant_private_key = '-----BEGIN PRIVATE KEY-----
MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAOGgvzIbldg/4Mtp
xiPXUCh/deNtmS+HBQjAbtEjv6ZrnSTGSUYdvjm/2+KhXkqawMqD1ByaKFjfQI0t
qNRxposI0id+FN/AZCbvhK5m4sxtxVg9Qgcb+Q2hNwcJYFMHHuYez4x5BvjwqQZn
IEdx7e4Jysp7umgZjT4eFapkgdQVAgMBAAECgYEAmpACyjGpecVwmggwqtbR25xN
RuoKeUt2QGOKxoxFupyXvtxyz6yKZocu+1ZuAI94qIKcaKeWIiN11gDEWhws6GJr
Lf+SegvWjHD59RWNcS0NQ1X0fy94QlULwSWVjAPghjP6V93VnhQj+ECVeWPQB2ce
8dVnaGCDTqbFHLJI7JUCQQDy7lhFZuMl6AJh4xDDPPaYCzs0K5BrIxzR623zFESr
dgHHK34TGIUNPQX87o/qnxIW4VP7G4QZdGNwT5C5moM7AkEA7cQaVnt3rA+4PLEt
PYqTHFWzhsydHMIzZ2gS2wNccQRfLo2tD4pbW+FlaiT6dZJjl4D55WZds+f60lZv
7w7w7wJBAOzJAsMK6Sa+gOv2jhUvK9DBScCtIcQ74lB+YJoJTHGvwUXoD6f/Q1jG
/TovMZnn2JLyqI/enyDMgEwtUYyHnZcCQQDWM8+OjhkYN+lBNFWPleJGqqbcEEaS
/BoXVNl32iVT5B5j8FszHvCDbCJTaBEEDugOO7Hmizxrsdhz2k9pTUqdAkB1EHeP
yF4XWbOsT4EIc2uH47F4pADgTOj4ERLpYBE2j6U0D8MKV2yDr94xWCT34qwnzK99
JqgxaHL80BpiSK0k
-----END PRIVATE KEY-----
';


$merchant_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDBhwtguE6KyCaioibFFKaUtmzK
JvuMq6Y7B2RJ5RF0nOgatqqq3ooTA6Y+4ElmNQTIn2JoPw85DG6vDWYlO2wgVyBR
WW4QPxh5aw2ippf5xAnZEolGKt+bvF02YMO3oc6+7S7B8n9f0H8q56BcKjYpuWsT
jyh5wMr4BlKa4Oq79wIDAQAB
-----END PUBLIC KEY-----
';

$server_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDJuWvoRtBJ3fLiS2NeYbM7jq9/
a+i/4pcWUAJUFaPWJ6Wy0LO77LDztN23wqm3Wyjh69MxJwKNbHa5ieEjcxjM0AGT
aIjAaWZq+57K9sZXlPmBSRiAyxI03iVtLqB/ZWDNjsMlKHho268PYAgGgtJBZWVW
VNv07Flv3kynJTNkSwIDAQAB
-----END PUBLIC KEY-----
';


/*********************    test ********************************/


//$merchant_private_key = '-----BEGIN PRIVATE KEY-----
//MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBANAqE2Bb5zKFyA/O
//604+fmzgfwm2/EweP+SE0RpfHPXmgo0d39Y0rH53pVRDGp13OApNlmGH18CxRdIM
//z3QSN+loAFMqvp483YHLwf5CDm/UiWAIdrbhahDWgmJsNrHZWkJe1zfyEgNfQCYj
//018ngGOYRfEw4HebC2L+791M8TI7AgMBAAECgYEAmvfH5wNkEaugrYwYhu5lRf62
//9G+CUdRvMltiI+TM9Y8+f3nPCnO6Oogtz5YJOVLoqFrsaf0sNGqElQQuaLWrYsHm
//XYFbfatg8DIisQ7zJjf+KGbvI6hSlAlL+3nxGWqwfNxQLX64AE+MDkpIA8OAqwmz
//sCScFBnB8zFJLXWjAskCQQD5hKXj8xdg9nJV7soG3tI0OXMJ1PdHJOjustwgoz8y
//amK8nZfocvLT3MNFLY1IxnjQ4hkpufM38ehaKTvK7n/9AkEA1ZJqZLbDkYWfz/nL
//mdj5mKukiYTxsFlAFbDotxXw4zdMmy7tZrEHziJsQ/ZLSkNR1byq1U6nUrO+XdVY
//VT7ElwJAX1t4YpNGfgHxVDH794A0aU0DT+CZ2BCdDIxCYB7DSisqLNc1dNppPtqB
//rfBorEVdasbdwvqTnu/OUpariTR4qQJADPaoMpjNYiXkP3GAJESBUf0JLbe+G+Au
///aIRXhuc1Y3jvn+otVUFjkOUosNuaoGPlBOxouT1TxXN9lAe3n3C3QJAStxosFHW
//FZ3U82SJ2oNeol5f4j61bKhCaBy2qdnoFKIOeYzS5NbXjoYQ1Q1Xt41xTatZ1Brz
//Qy5y9OjmcSThpg==
//-----END PRIVATE KEY-----
//';
//$mer_no = '126074';
//
//$server_public_key = '-----BEGIN PUBLIC KEY-----
//MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDJuWvoRtBJ3fLiS2NeYbM7jq9/
//a+i/4pcWUAJUFaPWJ6Wy0LO77LDztN23wqm3Wyjh69MxJwKNbHa5ieEjcxjM0AGT
//aIjAaWZq+57K9sZXlPmBSRiAyxI03iVtLqB/ZWDNjsMlKHho268PYAgGgtJBZWVW
//VNv07Flv3kynJTNkSwIDAQAB
//-----END PUBLIC KEY-----
//';



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