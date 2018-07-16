<?php
require_once '../../pay_mgr/init.php';
include 'config.php';
//将异步通知记录下来
$form = '<form action="'.$callbackurl.'" >';
foreach($_REQUEST as $k => $v){
    $form .= "<input type='text' name='$k' value='$v' />";
}
$form .="<input type='submit'></form><br>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);




$Succeed         =     $_REQUEST["Succeed"];
$signature         =     $_REQUEST["MD5info"];
$Result          =     $_REQUEST["Result"];
$MerRemark       =     $_REQUEST['MerRemark'];		//自定义信息返回
$order_no = $_REQUEST['BillNo'];
$order_amount = $_REQUEST["Amount"];


$DataContentParms =array();
$DataContentParms["MerNo"] = $_REQUEST['MerNo'];
$DataContentParms["BillNo"] = $_REQUEST["BillNo"];
$DataContentParms["Amount"] =  $_REQUEST["Amount"];
$DataContentParms["Succeed"] =  $_REQUEST["Succeed"];

ksort($DataContentParms);
$prestr = http_build_query($DataContentParms);     	//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串


$sign = strtoupper(md5($prestr."&". strtoupper(md5($key))));
if($signature == $sign){
    if($Succeed == '88'){
        $info = $database->get(DB_PREFIX . 'preorder', '*', array('order_id' =>$DataContentParms["BillNo"]));

        if ($info) {
            $infos = $database->get(DB_PREFIX . 'order', '*', array(
                'order_id' => $info['order_id'],
            ));

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
                $database->update(DB_PREFIX . 'preorder', $updateArr, array('order_id' => $DataContentParms["BillNo"]));
            }
        } else {
            echo " 状态错误";
        }
        echo 'SUCCESS'; // 数据验证完成必须输出0告诉系统 通知完成。
		exit();
    }
}else{
    echo "签名错误!";
}
