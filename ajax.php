<?php
require_once 'pay_mgr/init.php'; 
$action = isset($_GET['action'])?$_GET['action']:'';
if($action == 'open'){
//检查是否 关闭 
	$appSet = $database->get(DB_PREFIX.'set','*',array('id'=>1));
	$msg = "您好，该支付方式已被关闭，请选择其它支付方式，或稍后访问！";
	$arr = array(
			'alipay'=>$appSet['alipay_open'],
			'wechat'=>$appSet['wechat_open'],
			'bank'=>$appSet['bank_open'],
			'alipaywap'=>$appSet['alipaywap_open'],
			'wechatwap'=>$appSet['wap_open'],
			'qqpay'=>$appSet['qqpay_open'],
			'qqwappay'=>$appSet['qqwappay_open'],
     		'diankapay'=>$appSet['diankapay_open'],
      		'jdpay'=>$appSet['jdpay_open'],
			'tenpay'=>$appSet['tenpay_open'],
	        'kjzf' => $appSet['kjzf_open'],
            'bankwap' => $appSet['bankwap_open'],
      		'jdpaywap'=>$appSet['jdpaywap_open'],
      		'bankscan' => $appSet['bankscan_open'],
            'bankquick' => $appSet['bankquick_open'],
      		'alipaycode' => $appSet['alipaycode_open'],
      		'weixincode' => $appSet['wechatcode_open'],
			'msg'=>$msg);
	echo json_encode($arr);
}

if($action == 'weihu'){
//检查是否  维护
	$appSet = $database->get(DB_PREFIX.'set','*',array('id'=>1));
	$style = $_POST['bank_name'];
	$msg = "您好，该支付方式正在维护中，请选择其它支付方式，或稍后访问！";
	if($style == 'ALIPAY'){
		if($appSet['alipay_weihu'] == "1"){
			echo json_encode(array("stat"=>1,"msg"=>$msg));
		}else{
			echo '{"stat":0}';
		}
	}
	//支付宝
    if($style == 'ALIPAYCODE'){
        if($appSet['alipaycode_weihu'] == "1"){
            echo json_encode(array("stat"=>1,"msg"=>$msg));
        }else{
            echo '{"stat":0}';
        }
    }


	if($style == 'WECHAT'){
		if($appSet['wechat_weihu'] == "1"){
			echo json_encode(array("stat"=>1,"msg"=>$msg));
		}else{
			echo '{"stat":0}';
		}
	}
    //微信
    if($style == 'WEIXINCODE'){
        if($appSet['wechatcode_weihu'] == "1"){
            echo json_encode(array("stat"=>1,"msg"=>$msg));
        }else{
            echo '{"stat":0}';
        }
    }


	if($style == 'BANK'){
		if($appSet['bank_weihu'] == "1"){
			echo json_encode(array("stat"=>1,"msg"=>$msg));
		}else{
			echo '{"stat":0}';
		}
	}
	if($style == 'ALIPAYWAP'){
		if($appSet['alipaywap_weihu'] == "1"){
			echo json_encode(array("stat"=>1,"msg"=>$msg));
		}else{
			echo '{"stat":0}';
		}
	}
	if($style == 'WAP'){
		if($appSet['wap_weihu'] == "1"){
			echo json_encode(array("stat"=>1,"msg"=>$msg));
		}else{
			echo '{"stat":0}';
		}
	}
  
   if($style == 'QQPAY'){
		if($appSet['qqpay_weihu'] == "1"){
			echo json_encode(array("stat"=>1,"msg"=>$msg));
		}else{
			echo '{"stat":0}';
		}
	}
	
	if($style == 'QQWAPPAY'){
		if($appSet['qqwappay_weihu'] == "1"){
			echo json_encode(array("stat"=>1,"msg"=>$msg));
		}else{
			echo '{"stat":0}';
		}
	}
  
  	if($style == 'DIANKAPAY'){
		if($appSet['diankapay_weihu'] == "1"){
			echo json_encode(array("stat"=>1,"msg"=>$msg));
		}else{
			echo '{"stat":0}';
		}
	}
  
  if($style == 'JDPAY'){
		if($appSet['jdpay_weihu'] == "1"){
			echo json_encode(array("stat"=>1,"msg"=>$msg));
		}else{
			echo '{"stat":0}';
		}
	}
  
  if($style == 'TENPAY'){
		if($appSet['tenpay_weihu'] == "1"){
			echo json_encode(array("stat"=>1,"msg"=>$msg));
		}else{
			echo '{"stat":0}';
		}
	}
  if($style == 'KJZF'){
	    if($appSet['kjzf_weihu'] == "1"){
	        echo json_encode(array("stat"=>1,"msg"=>$msg));
	    }else{
	        echo '{"stat":0}';
	    }
  }
  if($style == 'BANKWAP'){
	    if($appSet['bankwap_weihu'] == "1"){
	        echo json_encode(array("stat"=>1,"msg"=>$msg));
	    }else{
	        echo '{"stat":0}';
	    }
  }
   if($style == 'BANKSCAN'){
	    if($appSet['bankscan_weihu'] == "1"){
	        echo json_encode(array("stat"=>1,"msg"=>$msg));
	    }else{
	        echo '{"stat":0}';
	    }
  }

  //银行快捷
    if($style == 'BANKQUICK'){
        if($appSet['bankquick_weihu'] == "1"){
            echo json_encode(array("stat"=>1,"msg"=>$msg));
        }else{
            echo '{"stat":0}';
        }
    }


    if($style == 'JDPAYWAP'){
	    if($appSet['jdpaywap_weihu'] == "1"){
	        echo json_encode(array("stat"=>1,"msg"=>$msg));
	    }else{
	        echo '{"stat":0}';
	    }
  }
  exit;
}


 ?>