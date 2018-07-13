<?php
require_once 'payBase.class.php';

class payNotify extends payBase {

    public function __construct($config) {
        parent::__construct($config);
    }

    /**
     * 针对notify_url验证消息是否合法消息
     * @return 验证结果
     */
    private function _verifyNotify() {
        // 获取通知的数据
        $json = file_get_contents("php://input");

        if (empty($json)) {
            $this->errMsg = '支付通知回调签名错误';
            $this->logger($this->errMsg);
            return false;
        }

        $result = json_decode($json, true);
        if (!$result) {
            $this->errMsg = "支付通知服务器提供参数解析失败";
            $this->logger($this->errMsg);
            return false;
        }

        // 验证签名
        if (!$this->checkSign($result)) {
            $this->errMsg = '支付通知回调签名错误，数据:' . $json;
            $this->logger($this->errMsg);
            return false;
        }

        return $result;
    }

    /**
     * @param bool $response_code 是否需要返回状态码(报文)给交易平台
     * @return bool|验证结果
     */
    public function notify($return_code = true) {
        global $database;
        // 验证签名
        $result = $this->_verifyNotify();
        if (!$result) {
            return false;
        }

        // 交易支付订单状态 S = 支付成功
        if ($result['payStatus'] == 'S') {
            $ip = get_client_ip();
            // 商户订单号
            $merOrderNo = $result['merOrderNo'];
            $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$merOrderNo));
            if($info){
                if(floatval($info['order_money']) == floatval($result['orderAmt'])){
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
                        $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$result['merOrderNo']));
                        
                    }
                }else{
                    file_put_contents(dirname(__FILE__).'/money.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据金额".($result['orderAmt']).",数据库存储金额{$info['pay_money']}错误\t".urldecode($str));
                }
            }else{
                file_put_contents(dirname(__FILE__).'/notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t".urldecode($str));
            }

            // 更安全的验证 这里可以通过交易订单查询接口, 查询交易订单状态 ，可选

            // 这里处理本地订单逻辑代码
            // ......
            // ......

            //

            $this->logger('支付回调成功, 数据:'.json_encode($result, true));

            // 输出状态码给远程服务器，请不要修改或删除
            if ($return_code) {
                $result['return_code'] = "success";
            }

            return $result;
        }
    }

}