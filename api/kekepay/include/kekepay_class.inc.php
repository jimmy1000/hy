<?php
/**
 * 
 * 可可支付类
 * @author linzehui
 *
 */
class kekepay_class
{
	private $payKey    = "2e82a5cb05ae4694b7188281e772ace3";//商户支付Key
	private $paySecret = "e4f6f24a92394fbb842b899d7f7240a5";//商户支付密钥
	private $subPayKey = "";//子商户支付Key，大商户时必填
	
	
	/**
	 * 支付方法
	 * Enter description here ...
	 */
	public function pay($pram=array()){
		
		$returnData = array(
			'status' => 400,//状态，200成功，400失败
			'msg'    => ''//失败是的提示信息
		);
	
		if(!$pram){
			$returnData['msg'] = '参数不能为空';
			return $returnData;
		}
		
		$data = array();
		$data['payKey']           = $this->payKey;//商户支付Key
		$data['orderPrice']       = $pram['orderPrice'];//订单金额，单位：元保留小数点后两位
		$data['outTradeNo']       = $pram['outTradeNo'];//商户支付订单号
		$data['productType']      = $pram['productType'];//产品类型
		$data['orderTime']        = $pram['orderTime'];//下单时间，格式yyyyMMddHHmmss
		$data['payBankAccountNo'] = '123456789';//接口要求写死
		$data['productName']      = $pram['productName'];//商品名称
		$data['orderIp']          = $pram['orderIp'];//下单IP
		
		$returnUrl = "http://www.baidu.com/other/return.php";//支付成功同步通知地址
		$notifyUrl = "http://www.baidu.com/other/notify.php";//后台异步通知地址
	
		$data['returnUrl'] = $returnUrl;//同步通知地址
		$data['notifyUrl'] = $notifyUrl;//异步通知地址
		
		$this->subPayKey && $data['subPayKey'] = $this->subPayKey;//子商户支付Key，大商户时必填
		$pram['remark']  && $data['remark']    = $pram['remark'];//备注
		
		$sign = $this->resSign($data);
		$data['sign'] = $sign;

		
		$url_pram = "";
		foreach ($data as $key => $val)
        {
           $url_pram .= "$key=$val&";
        }
		
		$payUrl = "http://gateway.kekepay.com/quickGateWayPay/initPay";//支付地址
		
		$btn = $payUrl."?".$url_pram;
		
		return $btn;
	}
	
	
	/**
	 * 查询支付结果
	 * Enter description here ...
	 */
	public function searchPay($pram=array()){
		$returnData = array(
			'status' => 400,//状态，200成功，400失败
			'msg'    => ''//失败是的提示信息
		);
	
		if(!$pram){
			$returnData['msg'] = '参数不能为空';
			return $returnData;
		}
		
		$data = array();
		$data['payKey']           = $this->payKey;//商户支付Key
		$data['outTradeNo']       = $pram['outTradeNo'];//商户支付订单号
		
		$sign = $this->resSign($data);
		$data['sign'] = $sign;

		include_once realpath( __DIR__ )."/include/Curl.php";
		$curl = new Curl();
		
		//查询地址，乱写的
		$searchUrl = "http://******.com/quickGateWayPay/search";
		
		//请求支付
		$curl->url      = $searchUrl;
		$curl->method   = 'POST';
		$curl->postData = $data;
		$res = $curl->request();
		$res = json_decode($res,true);
		
		//获取成功
		if($res['resultCode'] == "0000"){
			$returnData['status'] = 200;
			$returnData['data']   = $res;
			return $returnData;
		}
		
		//失败
		$returnData['msg'] = $res['errMsg'];//失败原因 
		return $returnData;
	}
	
	
	/**
	 * 打款操作
	 * Enter description here ...
	 */
	public function bankCardPay($pram=array()){
		
		if(!$pram){
			return false;
		}
		
		$data = array();
		$data['payKey']           = $this->payKey;//商户支付Key
		$data['outTradeNo']       = $pram['outTradeNo'];//商户T0出款订单号
		$data['orderPrice']       = $pram['orderPrice'];//订单金额，单位：元保留小数点后两位
		$data['proxyType']        = $pram['proxyType'];//交易类型
		$data['productType']      = $pram['productType'];//产品类型
		$data['bankAccountType']  = $pram['bankAccountType'];//收款银行卡类型
		$data['phoneNo']          = $pram['phoneNo'];//收款人手机号
		$data['receiverName']     = $pram['receiverName'];//收款人姓名
		$data['certType']         = $pram['certType'];//收款人证件类型，IDENTITY 身份证
		$data['certNo']           = $pram['certNo'];//收款人身份证号
		$data['receiverAccountNo']= $pram['receiverAccountNo'];//收款人银行卡号
		$data['bankClearNo']      = $pram['bankClearNo'];//开户行清算行号
		$data['bankBranchNo']     = $pram['bankBranchNo'];//开户行支行行号
		$data['bankName']         = $pram['bankName'];//开户行名称
		$data['bankCode']         = $pram['bankCode'];//银行编码，ICBC 中国工商银行
		$data['bankBranchName']   = $pram['bankBranchName'];//开户行支行名称
		
		if(isset($pram['province']) && !empty($pram['province'])){
			$data['province'] = $pram['province'];//银行卡开户省份
		}
		if(isset($pram['city']) && !empty($pram['city'])){
			$data['city'] = $pram['city'];//银行卡开户城市
		}
		
		$sign = $this->resSign($data);
		$data['sign'] = $sign;

		include_once dirname(__FILE__)."/Curl.php";
		$curl = new Curl();
		
		$payUrl = "http://www.baidu.com.com/quickGateWayPay/initPay";//打款地址
		
		//请求支付
		$curl->url      = $payUrl;
		$curl->method   = 'POST';
		$curl->postData = $data;
		$res = $curl->request();
		$res = json_decode($res,true);
		
		return $res;
	}
	
	/**
	 * 结算结果查询接口
	 * Enter description here ...
	 */
	public function Settlement($pram=array()){
		
		if(!$pram){
			return false;
		}
		
		$data = array();
		$data['payKey']           = $this->payKey;//商户支付Key
		$data['outTradeNo']       = $pram['outTradeNo'];//商户订单号（T0/T1出款交易传商户出款订单号）
		
		$this->subPayKey && $data['subPayKey'] = $this->subPayKey;//子商户支付Key，大商户时必填
		
		$sign = $this->resSign($data);
		$data['sign'] = $sign;

		include_once realpath( __DIR__ )."/include/Curl.php";
		$curl = new Curl();
		
		$payUrl = "http://******.com/quickGateWayPay/Settlement";//结算地址
		
		//请求支付
		$curl->url      = $payUrl;
		$curl->method   = 'POST';
		$curl->postData = $data;
		$res = $curl->request();
		$res = json_decode($res,true);
		
		return $res;
	}
	
	
	/**
	 * 对数组排序
	 * @param $para 排序前的数组
	 * return 排序后的数组
	 */
	public function argSort($para) {
		ksort($para);
		reset($para);
		return $para;
	}
	
	/**
	 * 组装回调SIGN验证字符串
	 * */
	public function resSign ($data) {
		
        $data = $this->argSort($data);
        
        $sign  = '';
        foreach ($data as $key => $val)
        {
           $sign .= "$key=$val&";
        }
        
	    $sign  .= "paySecret=". $this->paySecret;

	    return strtoupper(md5($sign));
	}
	
}



?>