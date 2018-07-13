<?php

/*
 * @Description API֧��B2C����֧���ӿڷ��� 
 */
require_once '../../pay_mgr/init.php';
include 'payCommon.php';	
	
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

$order_id = $r6_Order;

$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

if($bRet){
	if($r1_Code=="1"){

        echo 'success';

        $info = $database->get(DB_PREFIX . 'preorder', '*', array('order_id' => $order_id));

        if($info){
            $infos = $database->get(DB_PREFIX . 'order', '*', array(
                'order_id' => $info['order_id'],
            ));

            if(!$infos){
                $insertArr = array(
                    'order_id' => $info['order_id'],
                    'user_name' => $info['user_name'],
                    'order_money' => $amount,
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
                $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id' => $order_id));

            }
        }
	}
	
}else{
	echo "error";
}
   
?>
<html>
<head>
<title>Return from API Page</title>
</head>
<body>
</body>
</html>