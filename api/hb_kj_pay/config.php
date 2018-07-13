<?php
// 
//                                  _oo8oo_
//                                 o8888888o
//                                 88" . "88
//                                 (| -_- |)
//                                 0\  =  /0
//                               ___/'==='\___
//                             .' \\|     |// '.
//                            / \\|||  :  |||// \
//                           / _||||| -:- |||||_ \
//                          |   | \\\  -  /// |   |
//                          | \_|  ''\---/''  |_/ |
//                          \  .-\__  '-'  __/-.  /
//                        ___'. .'  /--.--\  '. .'___
//                     ."" '<  '.___\_<|>_/___.'  >' "".
//                    | | :  `- \`.:`\ _ /`:.`/ -`  : | |
//                    \  \ `-.   \_ __\ /__ _/   .-` /  /
//                =====`-.____`.___ \_____/ ___.`____.-`=====
//                                  `=---=`
// 
// 
//               ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
//                          佛祖保佑         永不宕机/永无bug
// +----------------------------------------------------------------------
// | FileName: config.php
// +----------------------------------------------------------------------
// | CreateDate: 2018年1月13日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
$gateWay = 'http://api.huobapay.com/api/sig/v1/union/quick';
//$gateWay = 'http://api.huobapay.com/api/sig/v1/alipay/wap';
$mch_id = '1000116';
$key = '6a6721f4439c4031abe750706bc7f664';
$callback = 'http://payhy.8889s.com/api/hb_kj_pay/callback.php';
$hrefback = 'http://payhy.8889s.com';

//数组转XML
function arrayToXml($arr)
{
    $xml = "<xml>" ;
    foreach ($arr as $key=>$val)
    {
       // $xml.="<".$key.">".$val."</".$key.">";
        $xml.="<".$key."><![CDATA[".$val."]]></".$key.">" ;
        /*
        if (is_numeric($val)){
            $xml.="<".$key.">".$val."</".$key.">";
        }else{
            $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }*/
    }
    $xml.="</xml>" ;
    return $xml ;
}

function makexml($data){
    $xmlData = '<xml>' ;
    if (!empty($data)) {
        //拼接xml数据
        //            $xmlData .= "<version>1.0</version>" ;
        $xmlData .= '<body>'.$data['body'].'</body>' ;
        $xmlData .= "<mch_id>{$data['mch_id']}</mch_id>" ;
        $xmlData .= "<bank_code>{$data['bank_code']}</bank_code>" ;
        $xmlData .= "<out_trade_no>{$data['out_trade_no']}</out_trade_no>" ;
        $xmlData .= "<total_fee>{$data['total_fee']}</total_fee>" ;
        $xmlData .= "<mch_create_ip>{$data['mch_create_ip']}</mch_create_ip>" ;
        $xmlData .= "<notify_url>{$data['notify_url']}</notify_url>" ;
        $xmlData .= "<nonce_str>{$data['nonce_str']}</nonce_str>" ;
        $xmlData .= "<sign>{$data['sign']}</sign>" ;
    }
    $xmlData .= '</xml>' ;
    return $xmlData;
}

//将XML转为array
function xmlToArray($xml)
{
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);
    $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $values;
}

function postxml($url, $data){
    $ch = curl_init();
    $header[] = "Content-type: text/xml";
    curl_setopt($ch, CURLOPT_HTTPHEADER,$header) ;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($ch) ;
    if (curl_errno($ch)) {
        print_r($ch) ; die ;
    }
    curl_close($ch) ;
    return $response ;
}

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
    $bankInfo['981'] = 'COMM' ; //交通银行
    $bankInfo['971'] = 'PSBC' ; //中国邮政储蓄银行
    $bankInfo['970'] = 'CMB' ; //招商银行
    $bankInfo['980'] = 'CMBC' ; //中国民生银行
    $bankInfo['962'] = 'CITIC' ; //中信银行
    $bankInfo['986'] = 'CEB' ; //中国光大银行
    $bankInfo['982'] = 'HXB' ; //华夏银行
    $bankInfo['985'] = 'GDB' ; //广发银行

    if (!isset($bankInfo[$bank])) {
        exit("<script>alert('很抱歉,暂不支持该行...');history.go(-1);</script>");
    }
    return $bankInfo[$bank];
}

function dd($arr)
{
    echo '<pre>' ;
    print_r($arr);
    die;
}