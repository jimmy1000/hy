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

$ip            = get_client_ip();
$result        = $_GET['result'];
$pay_message   = $_GET['pay_message'];
$agent_id      = $_GET['agent_id'];
$jnet_bill_no  = $_GET['jnet_bill_no'];
$agent_bill_id = $_GET['agent_bill_id'];
$pay_type      = $_GET['pay_type'];
$pay_amt       = $_GET['pay_amt'];
$remark        = $_GET['remark'];
$return_sign   = $_GET['sign'];
$remark        = iconv("GB2312","UTF-8//IGNORE",urldecode($remark));//签名验证中的中文采用UTF-8编码;

$signStr  = '';
$signStr  = $signStr . 'result=' . $result;
$signStr  = $signStr . '&agent_id=' . $agent_id;
$signStr  = $signStr . '&jnet_bill_no=' . $jnet_bill_no;
$signStr  = $signStr . '&agent_bill_id=' . $agent_bill_id;
$signStr  = $signStr . '&pay_type=' . $pay_type;
$signStr  = $signStr . '&pay_amt=' . $pay_amt;
$signStr  = $signStr . '&remark=' . $remark;
$signStr  = $signStr . '&key=' . $key; //商户签名密钥
$sign = '';
$sign = strtolower(md5($signStr));

if($sign==$return_sign){   //比较签名密钥结果是否一致，一致则保证了数据的一致性
    echo 'ok';
    //商户自行处理自己的业务逻辑
    $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$agent_bill_id));
    if($info){
        if(floatval($info['order_money']) == floatval($_REQUEST['trade_amount'])){
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
