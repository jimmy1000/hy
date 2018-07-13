<?php
/*
 * 功能：支付回调文件
 * 版本：1.0
 */
require_once '../../pay_mgr/init.php';
require_once (dirname(__FILE__) . '/' . 'config.php');
require_once (dirname(__FILE__) . '/' . 'ccpay.class.php');

// 请求数据赋值
$data = array();

$data['version'] = $_REQUEST['version'];
$data['notifyId'] = $_REQUEST['notifyId'];
$data['merchantId'] = $_REQUEST['merchantId'];
$data['orderId'] = $_REQUEST['orderId'];
$data['orderAmt'] = $_REQUEST['orderAmt'];
$data['orderTime'] = $_REQUEST['orderTime'];
$data['transId'] = $_REQUEST['transId'];
$data['transTime'] = $_REQUEST['transTime'];
$data['transAmt'] = $_REQUEST['transAmt'];
$data['status'] = $_REQUEST['status'];
$data['signType'] = $_REQUEST['signType'];

// 初始化
$ccpay = new CCPay($mer_key);
// 准备待签名数据
$str_to_sign = $ccpay->prepareSign($data);
// 验证签名
$resultVerify = $ccpay->verify($str_to_sign, $_REQUEST['sign']);

if ($resultVerify) {
    // 签名验证通过
    
    /* 商户需要在此处判定通知中的订单状态做后续处理 */
    /* 由于页面跳转同步通知和异步通知均发到当前页面，所以此处还需要判定商户自己系统中的订单状态，避免重复处理。 */
    
    // 根据$data['orderId']商户订单号，判定商户自己系统中的订单是否存在且未支付
    /*
     * if(订单存在且未支付)
     * {
     * 更新商户自己系统中的订单为支付成功，并完成其它业务处理
     * //回写SUCCESS，确认回调成功
     * echo "SUCCESS";
     * }
     */
     if($data['status'] == 'SUCCESS'){
         $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$data['orderId']));
         if($info){
             if(floatval($info['order_money']) == floatval($data['orderAmt'])){
                 $infos = $database->get(DB_PREFIX . 'order', '*', array(
                     'order_id' => $info['order_id'],
                 ));
                 if(!$infos){
                     $insertArr = array(
                         'order_id' => $info['order_id'],
                         'user_name' => $info['user_name'],
                         'order_money' => $info['order_money'],
                         'order_time' => date('Y-m-d H:i:s'),
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
                     $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$data['orderId']));
                     
                 }
                 echo 'SUCCESS';
             }else{
                 file_put_contents(dirname(__FILE__).'/money.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据金额{$_REQUEST['totalfee']},数据库存储金额{$info['pay_money']}错误\t".urldecode($str));
             }
         }else{
             file_put_contents(dirname(__FILE__).'/notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t".urldecode($str));
         }
     }
} else {
    echo "验证签名失败";
}
?>