<?php

include_once("./config.php");
require_once '../../pay_mgr/init.php';



/*
 *  获 取 表 单 数 据
 *
 */
$order_id   = date('YmdHis').rand(11111,99999); //您的订单Id号，你必须自己保证订单号的唯一性，本平台不会限制该值的唯一性

$payType    = 'bank';  //充值方式：bank为网银，card为卡类支付
$account    = $_REQUEST['username'];  //充值的账号
$amount     = $_REQUEST['coin'];   //充值的金额
$bankType   = getPayType();   //转化为四三方需要的支付类型
$bankTypeOrigin   = $_REQUEST['type'];   //记录支付类型,用于存储在数据库中
$signature        = '' ; //签名

//数据入库
//记录支付处理地址
if (!$_SERVER['HTTP_REFERER']) {
    $pay_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
} else {
    $pay_url = $_SERVER['HTTP_REFERER'];
}

//数据入库
$insertArr = [
    'order_id' => $order_id,
    'user_name'=> $_REQUEST['username'],
    'pay_type' => $bankTypeOrigin,
    'pay_ip'   => get_client_ip(),
    'sign'     => $signature,
    'order_money' => $_REQUEST['coin'],
    'order_time'  => time(),
    'pay_api'     => '旺旺云',
    'pay_url'     => $pay_url
] ;

if(!$database->insert(DB_PREFIX.'preorder',$insertArr)){
     exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}

//网银支付
if ('bank' == $payType) {

    /*
     * 提 交 数 据
     */
    include_once("lib/class.bankpay.php");
    $bankpay = new bankpay();
    $bankpay->parter = $merchant_id;  //商家Id
    $bankpay->key = $merchant_key; //商家密钥
    $bankpay->type = $bankType;   //支付类型
    $bankpay->value = $amount;    //提交金额
    $bankpay->orderid = $order_id;   //订单Id号
    $bankpay->callbackurl = $bank_callback_url; //下行url地址
    $bankpay->hrefbackurl = $bank_hrefbackurl; //下行url地址
    //发送
    $bankpay->send();

}
//卡类支付
else if ('card' == $payType) {
    $cardType = $_POST['cardType'];   //卡类型
    $card_number = $_POST['card_number'];  //卡号
    $card_password = $_POST['card_password'];  //卡密
    /*
     * 提交数据
     * */
    include_once("lib/class.cardpay.php");
    $cardpay = new cardpay();
    $cardpay->type = $cardType;   //卡类型	
    $cardpay->cardno = $card_number;   //卡号
    $cardpay->cardpwd = $card_password;  //卡密
    $cardpay->value = $amount;    //提交金额
    $cardpay->restrict = $restrict;  //地区限制, 0表示全国范围
    $cardpay->orderid = $order_id;   //订单号
    $cardpay->callbackurl = $callback_url; //下行url地址
    $cardpay->parter = $merchant_id;  //商家Id
    $cardpay->key = $merchant_key; //商家密钥
    //发送
    $result = $cardpay->send();

    /*
     * 处理结果
     * */
    switch ($result) {
        case '0':
            header("location: pay_card_finish.php?order_id=$order_id");
            break;
        case '-1':
            header("location: pay_card_finish.php?order_id=$order_id");
            break;
        case '-2':
            print('签名错误');
            break;
        case '-3':
            print('<script language="javascript">alert("对不起，您填写的卡号卡密有误！"); history.go(-1);</script>');
            break;
        case '-999':
            print('<script language="javascript">alert("对不起，接口维护中，请选择其他的充值方式！"); history.go(-1);</script>');
            break;
        default:
            print('未知的返回值, 请联系平台官方！');
            break;
    }
}

/**
 * 获取支付类型
 */
function getPayType()
{
    $type = $_REQUEST['type'] ;

    if ( !empty($type) ) {
        switch ( $type ) {
            //支付宝
            case 'ALIPAY' :
                $type = 1003 ; break ;
            //微信
            case 'WECHAT' :
                $type = 1004 ; break ;
            //QQ扫码
            case 'QQPAY' :
                $type = 1009; break ;
            //银联扫码
            case 'BANKSCAN' :
                $type = 2000; break ;
            //银联
            case 'BANK' :
                $type = $_REQUEST['bank'] ; break ;
            default:
                exit("<script>alert('此种支付方式暂未开启!');history.go(-1);</script>");
        }
    }
    return $type ;
}

?>