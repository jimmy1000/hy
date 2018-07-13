<?php



class roncoopay{

    private  $sign = '' ; //签名
	public function __construct()
    {
		$this->payKey    = '2e82a5cb05ae4694b7188281e772ace3'; //支付key
		$this->paySecret = 'e4f6f24a92394fbb842b899d7f7240a5'; //支付密钥
	}

	//扫码支付
	public function pay($data)
    {
        $signPars             = '' ;
		$param['payKey']      = $this->payKey ;
		$param['orderTime']   = date('YmdHis')  ;
		$param['orderIp']     = $_SERVER['REMOTE_ADDR']  ;
		$param['returnUrl']   = 'http://payhy.8889s.com' ;
		$param['notifyUrl']   = 'http://payhy.8889s.com/api/kkpay/callback.php' ;
		$param['productType'] = '10000103'            ; //微信支付ID
		$param['outTradeNo']  = $data['out_trade_no'] ;
		$param['orderPrice']  = $data['total_fee']    ;
		$param['productName'] = $data['name']         ;

		//签名
		ksort($param);
		foreach ($param as $k => $v) {
			if ("" != $v && "sign" != $k) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars      .= "paySecret=".$this->paySecret ;
		$param['sign']  = strtoupper(md5($signPars))  ;
		$this->sign     = $param['sign'] ;
		//请求
		$res = $this->sendRequest('http://gateway.kekepay.com/cnpPay/initPay',$param) ;
        $res = json_decode($res,true) ;

        //var_dump($res);
		//校验签名
		return $res;
		/*
		if ( $this->checkSign($res) ) {
			return $res;
		} else {
			return '签名错误';
		}
		*/
	}

	//回调
	public function notify(){
		$res=$_GET;
		//校验签名
		if($this->checkSign($res)){
			return $res;
		}else{
			return '签名错误';
		}
	}

	public function sendRequest($url,$pay){
	    $query = http_build_query($pay);
	    $option = array(
	        'http'=>array(
	            'method' => 'POST',
	            'header' =>
	            "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\r\n".
	            "Accept-Encoding:gzip, deflate\r\n".
	            "Accept-Language:zh-CN,zh;q=0.8,en;q=0.6\r\n".
	            "Cache-Control:max-age=0\r\n".
	            "Connection:keep-alive\r\n".
	            "Content-Length:".strlen($query)."\r\n".
	            "Content-Type:application/x-www-form-urlencoded\r\n".
	            "Origin:null\r\n".
	            "Upgrade-Insecure-Requests:1\r\n".
	            "User-Agent:Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36\r\n",
	            'content' => $query
	        )
	    );
	    $context = stream_context_create($option);
	    $res = file_get_contents($url,false,$context);
	    return $res;
	}
	
	public function http($url,$param='',$data = '',$method = 'GET'){
    	$opts = array(
    	    CURLOPT_TIMEOUT        => 30,
    	    CURLOPT_RETURNTRANSFER => 1,
    	    CURLOPT_SSL_VERIFYPEER => false,
    	    CURLOPT_SSL_VERIFYHOST => false,
    	);
    	/* 根据请求类型设置特定参数 */
    	$opts[CURLOPT_URL] = $url . '?' . http_build_query($param);

    	if(strtoupper($method) == 'POST'){
    	    $opts[CURLOPT_POST] = 1;
    	    $opts[CURLOPT_POSTFIELDS] = $data;
    	    if(is_string($data)){ //发送JSON数据
    	        $opts[CURLOPT_HTTPHEADER] = array(
    	            'Content-Type: application/json; charset=utf-8',
    	            'Content-Length: ' . strlen($data),
    	        );
    	    }
    	}
    	/* 初始化并执行curl请求 */
    	$ch = curl_init();
    	curl_setopt_array($ch, $opts);
    	$data  = curl_exec($ch);
    	$error = curl_error($ch);
    	curl_close($ch);
    	//发生错误，抛出异常
    	if($error) print_r($error);
    	return  $data;
	}

	//校验签名
	public function checkSign($res){
        $ressignPars = '';
		$ressign=$res['sign'];
		unset($res['sign']);
		ksort($res);
		foreach($res as $k => $v) {
			if("" != $v && "sign" != $k) {
				$ressignPars .= $k . "=" . $v . "&";
			}
		}
		$ressignPars .= "paySecret=".$this->paySecret;
		$myressign=strtoupper(md5($ressignPars));
		return $ressign==$myressign;
	}

    /**
     * 返回签名
     * @return string
     */
	public function getSign()
    {
        return $this->sign ;
    }

    /**
     * 表单方式
     */
    public function submitFormMethod($params, $gateway, $method = 'post', $charset = 'utf-8') {
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        header("Content-type:text/html;charset={$charset}");
        $sHtml = "<form id='paysubmit' name='paysubmit' action='{$gateway}' method='{$method}'>";

        foreach ($params as $k => $v) {
            $sHtml.= "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
        }

        $sHtml = $sHtml . "</form>正在提交支付数据 ...";

        $sHtml = $sHtml . "<script>document.forms['paysubmit'].submit();</script>";

        return $sHtml;
    }

    /**
     * POST访问
     * @param $url
     * @param $data
     * @return mixed|string
     */
     public function submitPostData($url,$data) {
        $ch = curl_init() ;
        curl_setopt($ch, CURLOPT_URL, $url) ;
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST") ;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE) ;
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE) ;
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)') ;
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1) ;
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1) ;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data) ;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;
        $tmpInfo = curl_exec($ch) ;
        if ( curl_errno($ch) ) {
            return curl_error($ch) ;
        }
        return $tmpInfo;
    }


}
