<?php

/**
 * Class SF
 */
class SF{
    private $MemberId;           //商户号
    private $Key;                 //商户秘钥
    public  $PutUrl;              //提交地址
    public  $NotifyUrl;          //异步返回地址（点对点通知地址）
    public  $CallbackUrl;        //同步返回地址（用户支付成功后跳转到的商户地址）
    public  $Reserved = array(); //扩展字段（原字段返回）
    public  $DeBug = false;       // 调试开关

    /**
     * SF constructor.
     * @param $memberid 商户号
     * @param $key 商户秘钥
     */
    public function __construct($memberid,$key)
    {
        $this->MemberId = $memberid;
        $this->Key       = $key;
    }
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }


    /**
     * 直接发起支付请求，跳转到第三方支付页面进行支付
     * @param $productname
     * @param $orderid 订单号
     * @param $amount 订单金额
     * @param $applydate 订单生成时间 （例：2017-01-01 01:01:01）
     * @param $bankcode 银行编码
     * @param $banktype 银行类型 （通常用来选择扫码或者WAP手机支付）
     * @param string $tongdao 通道 （联系客服获取，默认AW）
     */
    public function Put($productname,$orderid,$amount,$applydate,$bankcode,$banktype,$tongdao='AW'){
        $md5sign = $this->KeyMd5($orderid,$amount,$applydate,$bankcode);
        $return = array(
            "pay_memberid" => $this->MemberId,
            "pay_productname" => $productname,
            "pay_orderid" => $orderid,
            "pay_amount" => $amount,
            "pay_applydate" => $applydate,
            "pay_tongdao" => $tongdao,
            "pay_bankcode" => $bankcode,
            "pay_banktype" => $banktype,
            "pay_notifyurl" => $this->NotifyUrl,
            "pay_callbackurl" => $this->CallbackUrl,
            "pay_md5sign" => $md5sign,
            "json" => 0
        );
        if(is_array($this->Reserved)){
            $return = $return + $this->Reserved;
        }

        if($this->DeBug){
            include_once dirname(dirname(__FILE__)).'/view/DeBugPut.tpl';
        }else{
            include_once dirname(dirname(__FILE__)).'/view/Put.tpl';
        }
    }


    /**
     * 将支付二维码和支付链接拉取到
     * @param $productname
     * @param $orderid 订单号
     * @param $amount 订单金额
     * @param $applydate 订单生成时间 （例：2017-01-01 01:01:01）
     * @param $bankcode 银行编码
     * @param $banktype 银行类型 （通常用来选择扫码或者WAP手机支付）
     * @param string $tongdao 通道 （联系客服获取，默认AW）
     *
     * @return 返回json数据
     */
    public function JsonPut($productname,$orderid,$amount,$applydate,$bankcode,$banktype,$tongdao='AW'){
        $md5sign = $this->KeyMd5($orderid,$amount,$applydate,$bankcode);
        $return = array(
            "pay_memberid" => $this->MemberId,
            "pay_productname" => $productname,
            "pay_orderid" => $orderid,
            "pay_amount" => $amount,
            "pay_applydate" => $applydate,
            "pay_tongdao" => $tongdao,
            "pay_bankcode" => $bankcode,
            "pay_banktype" => $banktype,
            "pay_notifyurl" => $this->NotifyUrl,
            "pay_callbackurl" => $this->CallbackUrl,
            "pay_md5sign" => $md5sign,
            "json" => 1
        );
        if(is_array($this->Reserved)){
            $return = $return + $this->Reserved;
        }

        if($this->DeBug){
            echo '<pre>';
            var_dump($return);  //查看报错信息
            echo '</pre><br>';
        }
        if(function_exists('curl_init')){
            return $this->CurlPost($this->PutUrl,$return);
        }else{
            //curl 函数未启用警告
            exit('Fatal error:The curl function doesn\'t exist');
        }
    }


    /**
     * @param $orderid 订单号
     * @param $amount 订单金额
     * @param $applydate 订单生成时间 （例：2017-01-01 01:01:01）
     * @param $bankcode 银行编码
     *
     * @return string 返回计算后的秘钥串
     */
    public function KeyMd5($orderid,$amount,$applydate,$bankcode){
        $parameter = array(
            "pay_memberid" => $this->MemberId,
            "pay_orderid" => $orderid,
            "pay_amount" => $amount,
            "pay_applydate" => $applydate,
            "pay_bankcode" => $bankcode,
            "pay_notifyurl" => $this->NotifyUrl,
            "pay_callbackurl" => $this->CallbackUrl,
        );
        ksort($parameter);
        $md5str = "";
        foreach ($parameter as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $md5str = $md5str."key=".$this->Key;

        $sign = strtoupper(md5($md5str));
        return $sign;
    }


    /**
     * curl POST
     *
     * @param   string  url
     * @param   array   数据
     * @param   int     请求超时时间
     * @param   bool    HTTPS时是否进行严格认证
     * @return  string
     */
    function CurlPost($url, $data = array(), $timeout = 30, $CA = true){

        $cacert = dirname(dirname(__FILE__)) . '/CA/cacert.pem'; //CA根证书
        $SSL = substr($url, 0, 8) == "https://" ? true : false;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout-2);
        if ($SSL && $CA) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);   // 只信任CA颁布的证书
            curl_setopt($ch, CURLOPT_CAINFO, $cacert); // CA根证书（用来验证的网站证书是否是CA颁布）
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名，并且是否与提供的主机名匹配
        } else if ($SSL && !$CA) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); //避免data数据过长问题
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $ret = curl_exec($ch);
        if($this->DeBug){
            echo '打印报错信息：<br><pre>';
            var_dump(curl_error($ch));  //查看报错信息
            echo '</pre><br>打印报错信息结束<br>END<br>';
        }
        curl_close($ch);
        return  $ret;
    }
}