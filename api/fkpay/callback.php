<?php
// +----------------------------------------------------------------------
// | FileName: callback.php
// +----------------------------------------------------------------------
// | CreateDate: 2017年12月13日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
require_once '../../pay_mgr/init.php';
require_once './common.php' ;
require("./helper.php");

$merchantCode = $_POST["merchant_code"];
$orderNo      = $_POST["order_no"];
$orderTime    = $_POST["order_time"];
$orderAmount  = $_POST["order_amount"];
$trade_status = $_POST["trade_status"];
$tradeNo      = $_POST["trade_no"];
$returnParams = $_POST["return_params"];
$sign         = $_POST["sign"];


$kvs = new KeyValues();
$kvs->add("merchant_code", $merchantCode);
$kvs->add("order_no", $orderNo);
$kvs->add("order_time", $orderTime);
$kvs->add("order_amount", $orderAmount);
$kvs->add("trade_status", $trade_status);
$kvs->add("trade_no", $tradeNo);
$kvs->add("return_params", $returnParams);
$_sign = $kvs->sign();

//解密出金额字段
$aes   = new CryptAES();
$aes->set_key($key);
$aes->require_pkcs5();
$orderMoney  = $aes->encrypt($orderAmount);

if($_sign == $sign){   //比较签名密钥结果是否一致，一致则保证了数据的一致性
    echo 'success';
    //处理自己的业务逻辑
    $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$orderNo));
    if($info){
        if(floatval($info['order_money']) == floatval($orderMoney)){
            $infos = $database->get(DB_PREFIX . 'order', '*', array(
                'order_id' => $info['order_id'],
            ));
            if(!$infos){
                $insertArr = array(
                    'order_id' => $info['order_id'],
                    'user_name' => $info['user_name'],
                    'order_money' => $info['order_money'],
                    'order_time' => time(),
                    'order_state' => 1,
                    'state' => 0,
                    'pay_type' => $info['pay_type'],
                    'pay_api' => $info['pay_api'],
                    'pay_order' => $info['order_id'],
                );
                $updateArr = array(
                    'notify_ip' => get_client_ip(),
                    'notify_time' => date('Y-m-d H:i:s'),
                );
                $database->insert(DB_PREFIX . 'order', $insertArr);
                $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$_REQUEST['mer_order_no']));
                
            }
        }else{
            file_put_contents(dirname(__FILE__).'/money.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据金额{$_REQUEST['trade_amount']},数据库存储金额{$info['pay_money']}错误\t".urldecode($str));
        }
    }else{
        file_put_contents(dirname(__FILE__).'/notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t".urldecode($str));
    }
}
else{
    echo 'error';
    //商户自行处理，可通过查询接口更新订单状态，也可以通过商户后台自行补发通知，或者反馈运营人工补发
}
