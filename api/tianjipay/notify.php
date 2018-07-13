 ﻿
<form id='paysubmit' name='bankPaySubmit'
	action='http://gate.iceuptrade.com/cooperate/gateway.cgi' method='post'>
	<input type='hidden' name='service' value='TRADE.SCANPAY' /> 
	<input type='hidden' name='version' value='1.0.0.0' /> 
	<input type='hidden' name='merId' value='2018031211010519' /> 
	<input type='hidden' name='tradeNo' value='TIANJI5AACFA119C09A' /> 
	<input type='hidden' name='tradeDate' value='2018-03-17' /> <input type='hidden'
		name='amount' value='10.00' /> <input type='hidden' name='notifyUrl'
		value='https://www.sky1005.com/api/tianjipay/callback.php' /> <input
		type='hidden' name='extra' value='test001' /> <input type='hidden'
		name='summary' value='onlinepay' /> <input type='hidden'
		name='expireTime' value='1200' /> <input type='hidden' name='clientIp'
		value='47.52.33.206' /> <input type='hidden' name='sign'
		value='6ea22af27e9f776a14e41416234c3478' /> <Input type='submit' />
</form>
<script>document.forms['bankPaySubmit'].submit1();</script>