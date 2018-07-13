<?php

require_once '../../pay_mgr/init.php';
include 'config.php';


//将异步通知记录下来
$form = '<form action="" >';
foreach($_REQUEST as $k => $v){
    $form .= "<input type='' name='$k' value='$v' />";
}
$form .="<form/>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);


$p1_yingyongnum    = $_REQUEST['p1_yingyongnum'];
$p2_ordernumber    = $_REQUEST['p2_ordernumber'];
$p3_money          = $_REQUEST['p3_money'];
$p4_zfstate        = $_REQUEST['p4_zfstate'];
$p5_orderid        = $_REQUEST['p5_orderid'];
$p6_productcode    = $_REQUEST['p6_productcode'];
$p7_bank_card_code = $_REQUEST['p7_bank_card_code'];
$p8_charset        = $_REQUEST['p8_charset'];
$p9_signtype       = $_REQUEST['p9_signtype'];
$p10_sign          = $_REQUEST['p10_sign'];
$p11_pdesc         = $_REQUEST['p11_pdesc'];

$presign = $p1_yingyongnum."&".$p2_ordernumber."&".$p3_money."&".$p4_zfstate."&".$p5_orderid."&".$p6_productcode."&".$p7_bank_card_code."&".$p8_charset."&".$p9_signtype."&".$p11_pdesc."&".$compkey;
// echo $presign."<br/>";
$sign =strtoupper(md5($presign));


//比较签名密钥结果是否一致，一致则保证了数据的一致性
if ($sign == $p10_sign && $p4_zfstate == "1" ) {
    echo 'SUCCESS' ;
    //商户自行处理自己的业务逻辑
    $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$p2_ordernumber));

    if($info){
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
            $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$_REQUEST['down_sn']));

        }

    }else{
   //     file_put_contents(dirname(__FILE__).'/notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t".urldecode($str));
    }
} else {
    echo "Signature error";
}

?>