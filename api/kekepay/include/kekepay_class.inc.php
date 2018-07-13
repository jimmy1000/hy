<?php
/**
 * 
 * �ɿ�֧����
 * @author linzehui
 *
 */
class kekepay_class
{
	private $payKey    = "2e82a5cb05ae4694b7188281e772ace3";//�̻�֧��Key
	private $paySecret = "e4f6f24a92394fbb842b899d7f7240a5";//�̻�֧����Կ
	private $subPayKey = "";//���̻�֧��Key�����̻�ʱ����
	
	
	/**
	 * ֧������
	 * Enter description here ...
	 */
	public function pay($pram=array()){
		
		$returnData = array(
			'status' => 400,//״̬��200�ɹ���400ʧ��
			'msg'    => ''//ʧ���ǵ���ʾ��Ϣ
		);
	
		if(!$pram){
			$returnData['msg'] = '��������Ϊ��';
			return $returnData;
		}
		
		$data = array();
		$data['payKey']           = $this->payKey;//�̻�֧��Key
		$data['orderPrice']       = $pram['orderPrice'];//��������λ��Ԫ����С�������λ
		$data['outTradeNo']       = $pram['outTradeNo'];//�̻�֧��������
		$data['productType']      = $pram['productType'];//��Ʒ����
		$data['orderTime']        = $pram['orderTime'];//�µ�ʱ�䣬��ʽyyyyMMddHHmmss
		$data['payBankAccountNo'] = '123456789';//�ӿ�Ҫ��д��
		$data['productName']      = $pram['productName'];//��Ʒ����
		$data['orderIp']          = $pram['orderIp'];//�µ�IP
		
		$returnUrl = "http://www.baidu.com/other/return.php";//֧���ɹ�ͬ��֪ͨ��ַ
		$notifyUrl = "http://www.baidu.com/other/notify.php";//��̨�첽֪ͨ��ַ
	
		$data['returnUrl'] = $returnUrl;//ͬ��֪ͨ��ַ
		$data['notifyUrl'] = $notifyUrl;//�첽֪ͨ��ַ
		
		$this->subPayKey && $data['subPayKey'] = $this->subPayKey;//���̻�֧��Key�����̻�ʱ����
		$pram['remark']  && $data['remark']    = $pram['remark'];//��ע
		
		$sign = $this->resSign($data);
		$data['sign'] = $sign;

		
		$url_pram = "";
		foreach ($data as $key => $val)
        {
           $url_pram .= "$key=$val&";
        }
		
		$payUrl = "http://gateway.kekepay.com/quickGateWayPay/initPay";//֧����ַ
		
		$btn = $payUrl."?".$url_pram;
		
		return $btn;
	}
	
	
	/**
	 * ��ѯ֧�����
	 * Enter description here ...
	 */
	public function searchPay($pram=array()){
		$returnData = array(
			'status' => 400,//״̬��200�ɹ���400ʧ��
			'msg'    => ''//ʧ���ǵ���ʾ��Ϣ
		);
	
		if(!$pram){
			$returnData['msg'] = '��������Ϊ��';
			return $returnData;
		}
		
		$data = array();
		$data['payKey']           = $this->payKey;//�̻�֧��Key
		$data['outTradeNo']       = $pram['outTradeNo'];//�̻�֧��������
		
		$sign = $this->resSign($data);
		$data['sign'] = $sign;

		include_once realpath( __DIR__ )."/include/Curl.php";
		$curl = new Curl();
		
		//��ѯ��ַ����д��
		$searchUrl = "http://******.com/quickGateWayPay/search";
		
		//����֧��
		$curl->url      = $searchUrl;
		$curl->method   = 'POST';
		$curl->postData = $data;
		$res = $curl->request();
		$res = json_decode($res,true);
		
		//��ȡ�ɹ�
		if($res['resultCode'] == "0000"){
			$returnData['status'] = 200;
			$returnData['data']   = $res;
			return $returnData;
		}
		
		//ʧ��
		$returnData['msg'] = $res['errMsg'];//ʧ��ԭ�� 
		return $returnData;
	}
	
	
	/**
	 * ������
	 * Enter description here ...
	 */
	public function bankCardPay($pram=array()){
		
		if(!$pram){
			return false;
		}
		
		$data = array();
		$data['payKey']           = $this->payKey;//�̻�֧��Key
		$data['outTradeNo']       = $pram['outTradeNo'];//�̻�T0�������
		$data['orderPrice']       = $pram['orderPrice'];//��������λ��Ԫ����С�������λ
		$data['proxyType']        = $pram['proxyType'];//��������
		$data['productType']      = $pram['productType'];//��Ʒ����
		$data['bankAccountType']  = $pram['bankAccountType'];//�տ����п�����
		$data['phoneNo']          = $pram['phoneNo'];//�տ����ֻ���
		$data['receiverName']     = $pram['receiverName'];//�տ�������
		$data['certType']         = $pram['certType'];//�տ���֤�����ͣ�IDENTITY ���֤
		$data['certNo']           = $pram['certNo'];//�տ������֤��
		$data['receiverAccountNo']= $pram['receiverAccountNo'];//�տ������п���
		$data['bankClearNo']      = $pram['bankClearNo'];//�����������к�
		$data['bankBranchNo']     = $pram['bankBranchNo'];//������֧���к�
		$data['bankName']         = $pram['bankName'];//����������
		$data['bankCode']         = $pram['bankCode'];//���б��룬ICBC �й���������
		$data['bankBranchName']   = $pram['bankBranchName'];//������֧������
		
		if(isset($pram['province']) && !empty($pram['province'])){
			$data['province'] = $pram['province'];//���п�����ʡ��
		}
		if(isset($pram['city']) && !empty($pram['city'])){
			$data['city'] = $pram['city'];//���п���������
		}
		
		$sign = $this->resSign($data);
		$data['sign'] = $sign;

		include_once dirname(__FILE__)."/Curl.php";
		$curl = new Curl();
		
		$payUrl = "http://www.baidu.com.com/quickGateWayPay/initPay";//����ַ
		
		//����֧��
		$curl->url      = $payUrl;
		$curl->method   = 'POST';
		$curl->postData = $data;
		$res = $curl->request();
		$res = json_decode($res,true);
		
		return $res;
	}
	
	/**
	 * ��������ѯ�ӿ�
	 * Enter description here ...
	 */
	public function Settlement($pram=array()){
		
		if(!$pram){
			return false;
		}
		
		$data = array();
		$data['payKey']           = $this->payKey;//�̻�֧��Key
		$data['outTradeNo']       = $pram['outTradeNo'];//�̻������ţ�T0/T1����״��̻�������ţ�
		
		$this->subPayKey && $data['subPayKey'] = $this->subPayKey;//���̻�֧��Key�����̻�ʱ����
		
		$sign = $this->resSign($data);
		$data['sign'] = $sign;

		include_once realpath( __DIR__ )."/include/Curl.php";
		$curl = new Curl();
		
		$payUrl = "http://******.com/quickGateWayPay/Settlement";//�����ַ
		
		//����֧��
		$curl->url      = $payUrl;
		$curl->method   = 'POST';
		$curl->postData = $data;
		$res = $curl->request();
		$res = json_decode($res,true);
		
		return $res;
	}
	
	
	/**
	 * ����������
	 * @param $para ����ǰ������
	 * return ����������
	 */
	public function argSort($para) {
		ksort($para);
		reset($para);
		return $para;
	}
	
	/**
	 * ��װ�ص�SIGN��֤�ַ���
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