<?php
header('Content-type:text/html;charset=GB2312');
include 'common.php';
include 'config.php';

$form = '<form action="'.$callbackurl.'" >';
foreach($_REQUEST as $k => $v){
    $form .= "<input type='text' name='$k' value='$v' />";
}
$form .="<input type='submit'><form/><br>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);

#	只有支付成功时API支付才会通知商户.
##支付成功回调有两次，都会通知到在线支付请求参数中的p8_Url上：浏览器重定向;服务器点对点通讯.

#	解析返回参数.
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

#	判断返回签名是否正确（True/False）
$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
#	以上代码和变量不需要修改.

#	校验码正确.
if($bRet){
    if($r1_Code=="1") {

        #	需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
        #	并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生.

        if ($r9_BType == "2") {

            #如果需要应答机制则必须回写流,以success开头,大小写不敏感.

            require_once '../../pay_mgr/init.php';
            $info = $database->get(DB_PREFIX . 'preorder', '*', array('order_id' => $_REQUEST['r6_Order']));
            if ($info)
                $infos = $database->get(DB_PREFIX . 'order', '*', array(
                    'order_id' => $info['order_id'],
                ));

            if ($_REQUEST['r3_Amt'] == $info['order_money']) {
                if (!$infos) {
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
                    $database->update(DB_PREFIX . 'preorder', $updateArr, array('order_id' => $_REQUEST['r6_Order']));
                    echo "success";
                } else {
                    file_put_contents(dirname(__FILE__) . '/errlog.html', $_REQUEST['r6_Order'] . "错误" . "\r\n", FILE_APPEND);
                }
            }else{
                echo "交易金额错误";
            }
        }
    }

}else {
    echo "交易信息被篡改";

}

?>