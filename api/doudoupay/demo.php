<?php
/**
 * @name: 豆豆支付SDK.
 * @Author: Coder.yee <Coder.yee@gmail.com>
 * @Date: 2017/9/24
 * @Version: v1.0
 */

require 'api_sdk/init.php';

$return = $order_sn = $pay_amount = '';
if ((isset($_POST['submit']) && $_POST['submit'] == 'true')) {
    
    $order_sn = $_POST['order_sn'];
    $pay_amount = $_POST['pay_amount'];
    
    $getParams = [
        'pid' => PID,//签约PID
        // 'method' => 'doudoupay.post.merchant.gateway.OrderPay.Create',   //网关支付
        
        //  'method' => 'doudoupay.post.merchant.pay.directPay.trade',    //扫码接口
        // 'method' => 'doudoupay.post.merchant.proxy.pay.proxypay',     //代付接口
        'method' => 'doudoupay.post.merchant.wap.wapPay.create',        //H5支付
        //  'method' => 'doudoupay.post.merchant.gateway.OrderQuery.Item',        //网关查询
        'timestamp' => GETTIME,//时间戳
        'randstr' => func::getRandstr(),//随机32位串
    ];
    
    //代付参数
    /*  $dataArray = [
     'merchant_no' => MERCHANT_NO, //商户号，必传
     'out_pay_no' => $order_sn, //商户订单号，字符串型，长度范围在10到32之间,只能是数字和字符串，必传
     'amount' => $pay_amount, //支付金额，单位为分,无小数点，必传
     'account_type' => 2, //异步通知URL，一定外网地址才能收到回调通知信息，必传
     'receive_name' => '', //收款人姓名
     'receive_account' => '', //银行账号
     'receive_bank' => '', //银行名称 例如  中国民生银行
     'receive_branch_bank' => '',  //支行名称 例如 中国民生银行深圳宝安支行
     'receive_branch_bank_code' => '', //支行联行号  例如 305584018078
     'id_card_no' => '',     //身份证号
     ];*/
    //扫码支付参数
    /*  $dataArray = [
     'merchant_no' => MERCHANT_NO, //商户号，必传
     'order_sn' => $order_sn, //商户订单号，字符串型，长度范围在10到32之间,只能是数字和字符串，必传
     'pay_amount' => $pay_amount, //支付金额，单位为分,无小数点，必传
     'notify_url' => NOTIFY_URL, //异步通知URL，一定外网地址才能收到回调通知信息，必传
     'order_desc' => '扫码支付', //订单描述，必传
     'pmt_tag' => 'qq', //支付类型,目前支持微信和qq，微信传weixin,qq传qq，必传
     'pay_type' => 'swept', //支付场景,swept(用户扫商户的二维)，必传
     ];*/
    //  print_r($dataArray);exit;
    //H5支付参数
    $dataArray = [
        'merchant_no' => MERCHANT_NO, //商户号，必传
        'out_trade_no' => $order_sn, //商户订单号，字符串型，长度范围在10到32之间,只能是数字和字符串，必传
        'trade_amount' => $pay_amount, //支付金额，单位为分,无小数点，必传
        'notify_url' => NOTIFY_URL, //异步通知URL，一定外网地址才能收到回调通知信息，必传
        'sync_url' => 'http://www.baidu.com',
        'body' => 'Wap支付', //订单描述，必传
        'pay_type' => 'weixin', //支付类型,目前支持微信，微信传weixin  必传
        // 'client_ip' => get_client_ip(),
        'client_ip' => '119.23.105.73'
    ];
    
    //网关支付
    /* $dataArray = [
     'merchant_no' => MERCHANT_NO, //商户号，必传
     'order_sn' => date('YmdHis').mt_rand(10,99), //商户订单号，字符串型，长度范围在10到32之间,只能是数字和字符串，必传
     'pay_amount' => 3, //支付金额，单位为分,无小数点，必传
     'notify_url' => NOTIFY_URL, //异步通知URL，一定外网地址才能收到回调通知信息，必传
     'ord_currency' => 'CNY',
     'bank_code' => 'ICBC',
     'bank_card_type' => 0,
     'notify_url' => 'http://www.baidu.com',
     'return_url' => 'http://www.baidu.com',
     'ord_name' => 'test'
     
     ];*/
    
    //网关订单查询
    /*  $dataArray = [
     'merchant_no' => MERCHANT_NO, //商户号，必传
     'order_sn' => date('YmdHis').mt_rand(10,99), //商户订单号，字符串型，长度范围在10到32之间,只能是数字和字符串，必传
     
     ];*/
    
    
    
    $dataArray = func::argSort($dataArray);//sort
    $dataArray = func::array_value_to_string($dataArray);//to string
    
    $str_query = array_merge($getParams, ['data' => $dataArray]);
    $str_query = func::argSort($str_query);
    $str_query = func::array_value_to_string($str_query);
    $str_query = json_encode($str_query, JSON_UNESCAPED_SLASHES);
    
    //生成签名
    $getParams['sign'] = func::md5Sign($str_query, SING_KEY);
    
    $getParams = func::argSort($getParams);
    
    $url = SERVER_URL . '?' . func::http_build_querys($getParams);
    
    $crypt = new CryptRSA();
    $crypt->setParam('_public_key', file_get_contents(PUBLIC_KEY_FILE));//加载公钥
    $crypt->setParam('_private_key', file_get_contents(PRIVATE_KEY_FILE));//加载私钥
    $crypt->setParam('_private_key_password', PRIVATE_KEY_PASSWORD);//设置私钥密码
    
    $encrypted = json_encode($dataArray, JSON_UNESCAPED_SLASHES);//数据转换成JSON
    //  $encrypted = '{"body":"1513845671026","client_ip":"127.0.0.1","merchant_no":"1171106142713447","notify_url":"http://roncoo.iok.la/roncoo-pay-web-gateway/cnpPayNotify/notify/SCANPAY","out_trade_no":"1513845671026","pay_type":"weixin","sync_url":"http://roncoo.iok.la/roncoo-pay-web-gateway/cnpPayNotify/returnNotify/SCANPAY","trade_amount":"300"}';
    $encrypted = $crypt->publicEncrypt($encrypted);//数据加密
    
    //  echo $encrypted;exit;
    //生成post数据
    $post = ['data' => $encrypted];
    //  echo $url;
    // print_r($post);exit;
    $return = func::vCurl($url, $post);//发起API通讯
    // var_dump($return);exit;
    $array = json_decode($return, true);
    echo '<pre>';
    print_r($array);exit;
    //var_dump($return);exit;
    /*if (RETURN_CIPHERTEXT == true) {
     // 如果返回数据为密文，则需要解密数据
     $return = $crypt->privateDecrypt($return);
     }*、
     
     $array = json_decode($return, true);
     
     //返回系统级错误异常处理
     if (is_null($array) || !is_array($array)) {
     echo $return;
     die();
     }
     
     //返回逻辑错误处理
     if ($array['errcode'] !== '0') {
     //返回逻辑错误处理
     }
     /**
     * 接口返回正确后可进行正常订单逻辑操作
     */
    // ...
    // ...
    // ...
    // ...
    // ...
    // ...
    
}

function rands()
{
    $seed = base_convert(md5(mt_srand() . microtime()), 16, 10);
    $seed = str_replace('0', '', $seed) . '012340567890';
    $hash = '';
    $max = strlen($seed) - 1;
    for ($i = 0; $i < 18; $i++) {
        $hash .= $seed[mt_rand(0, $max)];
    }
    return date('YmdHis') . $hash;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>下单接口示例 - 豆豆支付SDK</title>
    <style>
        .span_1 {
            width: 88px;
            display: inline-block
        }

        input {
            width: 300px;
            margin: 20px 0;
        }

        button {
            width: 300px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
<form method="post" action="?">
    <div style="width:700px; margin:0 auto;font-family:Arial, Georgia, Times;">
        <p>&nbsp;</p>

        <div style="text-align: center;width:700px; "><h1>&nbsp;下单接口示例</h1></div>
        <div><span class="span_1">订单号：</span>
            <input type="text" name="order_sn" value="<?= ($order_sn ? $order_sn : rands()) ?>"/>
        </div>
        <div><span class="span_1">支付金额：</span>
            <input type="text" name="pay_amount" value="<?= $pay_amount ?>"/>
        </div>
        <div>
            <button type="submit">提交</button>
            <input type="hidden" name="submit" value="true">
        </div>
        <div>
            <?php if (isset($array) && is_array($array)) { ?>
                <pre>返回数据：<?php print_r($array); ?></pre>
                <?php if (isset($array['data']['code_img_url'])) { ?>
                    <img src="<?= $array['data']['code_img_url'] ?>">
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</form>
</body>
</html>
