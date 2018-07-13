<?php
/*
 * 功能：支付回调文件
 * 版本：1.0
 * 日期：2015-03-26
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码。
 */
require_once '../../pay_mgr/init.php';
require_once ("Config.php");
require_once ("lib/Pay.class.php");

// 请求数据赋值
$data = "";
$data['service'] = $_REQUEST["service"];
// 通知时间
$data['merId'] = $_REQUEST["merId"];
// 支付金额(单位元，显示用)
$data['orderNo'] =$orderid= $_REQUEST["orderNo"];
// 商户号
$data['tradeDate'] = $_REQUEST["tradeDate"];
// 商户参数，支付平台返回商户上传的参数，可以为空
$data['opeNo'] = $_REQUEST["opeNo"];
// 订单号
$data['opeDate'] = $_REQUEST["opeDate"];
// 订单日期
$data['amount'] = $ovalue = $_REQUEST["amount"];
// 支付订单号
$data['status'] = $_REQUEST["status"];
// 支付账务日期
$data['extra'] = $_REQUEST["extra"];
// 订单状态，0-未支付，1-支付成功，2-失败，4-部分退款，5-退款，9-退款处理中
$data['payTime'] = $_REQUEST["payTime"];
// 签名数据
$data['sign'] = $_REQUEST["sign"];
$data['notifyType'] = $_REQUEST["notifyType"];
// 初始化
$pPay = new Pay($KEY, $GATEWAY_URL);
// 准备准备验签数据
$str_to_sign = $pPay->prepareSign($data);
// 验证签名
$resultVerify = $pPay->verify($str_to_sign, $data['sign']);
// var_dump($data);
$ip = get_client_ip();
if ($resultVerify) {
    echo "验证签名成功";
    
    /**
     * 验证通过后，请在这里加上商户自己的业务逻辑处理代码.
     * 比如：
     * 1、根据商户订单号取出订单数据
     * 2、根据订单状态判断该订单是否已处理（因为通知会收到多次），避免重复处理
     * 3、比对一下订单数据和通知数据是否一致，例如金额等
     * 4、接下来修改订单状态为已支付或待发货
     * 5、...
     */
    // 判断通知类型，若为后台通知需要回写"SUCCESS"给支付系统表明已收到支付通知
    // 否则支付系统将按一定的时间策略在接下来的24小时内多次发送支付通知。
    if ('1' == $_REQUEST["notifyType"]) {
        $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$orderid));
        if($info){
            if(floatval($info['order_money']) == floatval($ovalue)){
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
                    $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$orderid));
                    
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
    // 把获得的这些数据显示到页面上，这里只是为了演示，实际应用中应该不需要把这些数据显示到页面上。
    echo "验证签名失败";
    echo "接口名称:" . $data['service'] . '<br>';
    echo "商户号:" . $data['merId'] . '<br>';
    echo "商户订单号:" . $data['tradeNo'] . '<br>';
    echo "商户交易日期:" . $data['tradeDate'] . '<br>';
    echo "支付平台订单号:" . $data['opeNo'] . '<br>';
    echo "支付平台订单日期:" . $data['opeDate'] . "元" . '<br>';
    echo "支付金额:" . $data['amount'] . "元" . '<br>';
    echo "订单状态:" . $data['status'] . "元" . '<br>';
    echo "商户参数:" . $data['extra'] . "元" . '<br>';
    echo "支付时间:" . $data['payTime'] . "元" . '<br>';
    // echo "验签结果描述:".$data['verifyStatus']."元".'<br>';
    return false;
}

?>