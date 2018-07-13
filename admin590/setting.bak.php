<?php
include 'global.php';

$action = 'edit';

if (isset($_GET['act'])) {
    if ($_GET['act'] == 'edit') {
        
        $id = @$_POST['id'];
        
        $info = $database->get(DB_PREFIX . 'set', '*', array(
            'id' => $id
        ));
        
        $upArr = array(
            'alipay_from' => @$_POST ['alipay_from'],
			'wechat_from' => @$_POST ['wechat_from'],
			'bank_from' => @$_POST ['bank_from'],
			'wap_from' => @$_POST ['wap_from'],
			'alipaywap_from' => @$_POST ['alipaywap_from'],
			'page_size' => @$_POST ['page_size'],
			'alipay_open' => @$_POST ['alipay_open'],
			'alipay_weihu' => @$_POST ['alipay_weihu'],
			'wechat_open' => @$_POST ['wechat_open'],
			'wechat_weihu' => @$_POST ['wechat_weihu'],
			'bank_open' => @$_POST ['bank_open'],
			'bank_weihu' => @$_POST ['bank_weihu'],
			'alipaywap_open' => @$_POST ['alipaywap_open'],
			'alipaywap_weihu' => @$_POST ['alipaywap_weihu'],
			'wap_open' => @$_POST ['wap_open'],
			'wap_weihu' => @$_POST ['wap_weihu'],
			'tenpay' => @$_POST ['tenpay'],
			'tenpay_open' => @$_POST ['tenpay_open'],
			'tenpay_weihu' => @$_POST ['tenpay_weihu'],				
			'qqpay' => @$_POST ['qqpay'],
			'qqpay_open' => @$_POST ['qqpay_open'],
			'qqpay_weihu' => @$_POST ['qqpay_weihu'],
			'qqwappay' => @$_POST ['qqwappay'],
			'qqwappay_open' => @$_POST ['qqwappay_open'],
			'qqwappay_weihu' => @$_POST ['qqwappay_weihu'],
        	'diankapay' => @$_POST ['diankapay'],
        	'diankapay_open' => @$_POST ['diankapay_open'],
        	'diankapay_weihu' => @$_POST ['diankapay_weihu'],
         	'jdpay' => @$_POST ['jdpay'],
        	'jdpay_open' => @$_POST ['jdpay_open'],
        	'jdpay_weihu' => @$_POST ['jdpay_weihu'],
        );
		//var_dump($upArr);exit();
        $database->update(DB_PREFIX . 'set', $upArr, array(
            'id' => $id
        ));
        
        echo json_encode(array(
            'stat' => 0
        ));
        
        return;
    }
}

?>

<?php include 'base.php';?>

<!-- page start -->

<?php
if ($action == 'edit') {
    
    $info = $database->get(DB_PREFIX . 'set', '*', array(
        'id' => 1
    ));
    
    ?>
<div class="content">
	<div class="header">
		<h1 class="page-title">系统设置</h1>
	</div>
	<ul class="breadcrumb">
		<li><a href="index.php">主页</a> <span class="divider">/</span></li>
		<li class="active">系统设置</li>
	</ul>
	<div class="container-fluid">
		<div class="row-fluid" style="padding-top: 20px;">

			<form class="form-horizontal" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label" for="alipay_open">支付宝通道</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="alipay_open" value="0"
							<?php if($info["alipay_open"]==0){echo ' checked="checked" ';}?>>
							开启
						</label> <label class="radio inline"> <input type="radio"
							name="alipay_open" value="1"
							<?php if($info["alipay_open"]==1){echo ' checked="checked" ';}?>>
							关闭
						</label>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="alipay_weihu">支付宝维护</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="alipay_weihu" value="0"
							<?php if($info["alipay_weihu"]==0){echo ' checked="checked" ';}?>>
							关闭
						</label> <label class="radio inline"> <input type="radio"
							name="alipay_weihu" value="1"
							<?php if($info["alipay_weihu"]==1){echo ' checked="checked" ';}?>>
							开启
						</label>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="alipay_from">支付宝方式</label>
					<div class="controls">
						<select name="alipay_from" id="alipay_from" class="span"
							style="width: 220px;">
							<option value='yfpay'
								<?php if($info['alipay_from']=="yfpay"){ echo ' selected ';}?>>优付</option>
							<option value='jypay'
								<?php if($info['alipay_from']=="jypay"){ echo ' selected ';}?>>聚源</option>
							<!-- <option value='qxpay' <?php if($info['alipay_from']=="qxpay"){ echo ' selected ';}?>>启讯</option> -->
							<option value='101pay'
								<?php if($info['alipay_from']=="101pay"){ echo ' selected ';}?>>101卡</option>
							<option value='kdpay'
								<?php if($info['alipay_from']=="kdpay"){ echo ' selected ';}?>>口袋</option>
							<option value='worth'
								<?php if($info['alipay_from']=="worth"){ echo ' selected ';}?>>华势支付</option>
							<option value='yinbao'
								<?php if($info['alipay_from']=="yinbao"){ echo ' selected ';}?>>银宝</option>
							<option value='kxpay'
								<?php if($info['alipay_from']=="kxpay"){ echo ' selected ';}?>>科迅</option>
							<option value='xfpay'
								<?php if($info['alipay_from']=="xfpay"){ echo ' selected ';}?>>迅付支付</option>
							<option value='danbao'
								<?php if($info['alipay_from']=="danbao"){ echo ' selected ';}?>>钱海支付</option>
							<option value='wsfpay'
								<?php if($info['alipay_from']=="wsfpay"){ echo ' selected ';}?>>旺实富支付</option>
							<option value='duobao'
								<?php if($info['alipay_from']=="duobao"){ echo ' selected ';}?>>多宝支付</option>
							<option value='lbkpay'
								<?php if($info['alipay_from']=="lbkpay"){ echo ' selected ';}?>>龙卡宝支付</option>
							<option value='sfpay'
								<?php if($info['alipay_from']=="sfpay"){ echo ' selected ';}?>>顺付支付</option>
                          <option value='tbpay'
								<?php if($info['alipay_from']=="tbpay"){ echo ' selected ';}?>>通宝支付</option>
                            <option value='fxpay'
								<?php if($info['alipay_from']=="fxpay"){ echo ' selected ';}?>>飞迅支付</option>
                          <option value='yspay'
								<?php if($info['alipay_from']=="yspay"){ echo ' selected ';}?>>云盛支付</option>
                          <option value='yunfx'
								<?php if($info['alipay_from']=="yunfx"){ echo ' selected ';}?>>云付盟支付</option>
                          <option value='smy'
								<?php if($info['alipay_from']=="smy"){ echo ' selected ';}?>>收米云支付</option>
                          <option value='qfpay'
								<?php if($info['alipay_from']=="qfpay"){ echo ' selected ';}?>>启付支付</option>
                          <option value='jfkpay'
								<?php if($info['alipay_from']=="jfkpay"){ echo ' selected ';}?>>金付卡支付</option>
                          <option value='ddbpay'
								<?php if($info['alipay_from']=="ddbpay"){ echo ' selected ';}?>>多得宝支付</option>
                          <option value='dypay'
								<?php if($info['alipay_from']=="dypay"){ echo ' selected ';}?>>东翼支付</option>
                          <option value='lkfpay'
								<?php if($info['alipay_from']=="lkfpay"){ echo ' selected ';}?>>立刻付支付</option>
                          <option value='kfpay'
								<?php if($info['alipay_from']=="kfpay"){ echo ' selected ';}?>>快付支付</option>
                           <option value='yyfpay'
								<?php if($info['alipay_from']=="yyfpay"){ echo ' selected ';}?>>优易付支付</option>
                          <option value='xbpay'
								<?php if($info['alipay_from']=="xbpay"){ echo ' selected ';}?>>迅宝支付</option>
                          <option value='wyfpay'
								<?php if($info['alipay_from']=="wyfpay"){ echo ' selected ';}?>>外易付支付</option>
                           <option value='dlfpay'
								<?php if($info['alipay_from']=="dlfpay"){ echo ' selected ';}?>>得力付支付</option>
						</select>
						</select>
					</div>
				</div>

				<hr>

				<div class="control-group">
					<label class="control-label" for="wechat_open">微信通道</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="wechat_open" value="0"
							<?php if($info["wechat_open"]==0){echo ' checked="checked" ';}?>>
							开启
						</label> <label class="radio inline"> <input type="radio"
							name="wechat_open" value="1"
							<?php if($info["wechat_open"]==1){echo ' checked="checked" ';}?>>
							关闭
						</label>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="wechat_weihu">微信维护</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="wechat_weihu" value="0"
							<?php if($info["wechat_weihu"]==0){echo ' checked="checked" ';}?>>
							关闭
						</label> <label class="radio inline"> <input type="radio"
							name="wechat_weihu" value="1"
							<?php if($info["wechat_weihu"]==1){echo ' checked="checked" ';}?>>
							开启
						</label>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="wechat_from">微信方式</label>
					<div class="controls">
						<select name="wechat_from" id="wechat_from" class="span"
							style="width: 220px;">
							<option value='yfpay'
								<?php if($info['wechat_from']=="yfpay"){ echo ' selected ';}?>>优付</option>
							<option value='jypay'
								<?php if($info['wechat_from']=="jypay"){ echo ' selected ';}?>>聚源</option>
							<option value='kdpay'
								<?php if($info['wechat_from']=="kdpay"){ echo ' selected ';}?>>口袋</option>
							<!--<option value='qxpay' <?php if($info['wechat_from']=="qxpay"){ echo ' selected ';}?>>启讯</option> -->
							<option value='worth'
								<?php if($info['wechat_from']=="worth"){ echo ' selected ';}?>>华势支付</option>
							<option value='yinbao'
								<?php if($info['wechat_from']=="yinbao"){ echo ' selected ';}?>>银宝</option>
							<option value='kxpay'
								<?php if($info['wechat_from']=="kxpay"){ echo ' selected ';}?>>科迅</option>
							<option value='xfpay'
								<?php if($info['wechat_from']=="xfpay"){ echo ' selected ';}?>>迅付支付</option>
							<option value='danbao'
								<?php if($info['wechat_from']=="danbao"){ echo ' selected ';}?>>钱海支付</option>
							<option value='wsfpay'
								<?php if($info['wechat_from']=="wsfpay"){ echo ' selected ';}?>>旺实富支付</option>
							<option value='rxpay'
								<?php if($info['wechat_from']=="rxpay"){ echo ' selected ';}?>>仁信支付</option>
							<option value='duobao'
								<?php if($info['wechat_from']=="duobao"){ echo ' selected ';}?>>多宝支付</option>
							<option value='lbkpay'
								<?php if($info['wechat_from']=="lbkpay"){ echo ' selected ';}?>>龙卡宝支付</option>
							<option value='sfpay'
								<?php if($info['wechat_from']=="sfpay"){ echo ' selected ';}?>>顺付支付</option>
                          <option value='tbpay'
								<?php if($info['wechat_from']=="tbpay"){ echo ' selected ';}?>>通宝支付</option>
                          <option value='yspay'
								<?php if($info['wechat_from']=="yspay"){ echo ' selected ';}?>>云盛支付</option>
                          <option value='smy'
								<?php if($info['wechat_from']=="smy"){ echo ' selected ';}?>>收米云支付</option>
                          <option value='yunfx'
								<?php if($info['wechat_from']=="yunfx"){ echo ' selected ';}?>>云付盟支付</option>
                          <option value='xypay'
								<?php if($info['wechat_from']=="xypay"){ echo ' selected ';}?>>信盈支付</option>
                          <option value='qfpay'
								<?php if($info['wechat_from']=="qfpay"){ echo ' selected ';}?>>启付支付</option>
                          <option value='jfkpay'
								<?php if($info['wechat_from']=="jfkpay"){ echo ' selected ';}?>>金付卡支付</option>
                          <option value='ddbpay'
								<?php if($info['wechat_from']=="ddbpay"){ echo ' selected ';}?>>多得宝支付</option>
                              <option value='jhzpay'
								<?php if($info['wechat_from']=="jhzpay"){ echo ' selected ';}?>>金海哲支付</option>
                          <option value='101pay'
								<?php if($info['wechat_from']=="101pay"){ echo ' selected ';}?>>101卡支付</option>
                          <option value='dypay'
								<?php if($info['wechat_from']=="dypay"){ echo ' selected ';}?>>东翼支付</option>
                          <option value='wyfpay'
								<?php if($info['wechat_from']=="wyfpay"){ echo ' selected ';}?>>外易付支付</option>
                          <option value='kfpay'
								<?php if($info['wechat_from']=="kfpay"){ echo ' selected ';}?>>快付支付</option>
                          <option value='lkfpay'
								<?php if($info['wechat_from']=="lkfpay"){ echo ' selected ';}?>>立刻付支付</option>
                         <option value='yyfpay'
								<?php if($info['wechat_from']=="yyfpay"){ echo ' selected ';}?>>优易付支付</option>
                           <option value='shbpay'
								<?php if($info['wechat_from']=="shbpay"){ echo ' selected ';}?>>速汇宝支付</option>
                           <option value='dlfpay'
								<?php if($info['wechat_from']=="dlfpay"){ echo ' selected ';}?>>得力付支付</option>
                          <option value='klpay'
								<?php if($info['wechat_from']=="klpay"){ echo ' selected ';}?>>快联付支付</option>
						</select>
					</div>
				</div>
				<hr>
				<div class="control-group">
					<label class="control-label" for="bank_open">网银通道</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio" name="bank_open"
							value="0"
							<?php if($info["bank_open"]==0){echo ' checked="checked" ';}?>>
							开启
						</label> <label class="radio inline"> <input type="radio"
							name="bank_open" value="1"
							<?php if($info["bank_open"]==1){echo ' checked="checked" ';}?>>
							关闭
						</label>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="bank_weihu">网银维护</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio" name="bank_weihu"
							value="0"
							<?php if($info["bank_weihu"]==0){echo ' checked="checked" ';}?>>
							关闭
						</label> <label class="radio inline"> <input type="radio"
							name="bank_weihu" value="1"
							<?php if($info["bank_weihu"]==1){echo ' checked="checked" ';}?>>
							开启
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="wechat_from">网银方式</label>
					<div class="controls">
						<select name="bank_from" id="bank_from" class="span"
							style="width: 220px;">
							<option value='ybpay'
								<?php if($info['bank_from']=="ybpay"){ echo ' selected ';}?>>银宝</option>
							<option value='qwpay'
								<?php if($info['bank_from']=="qwpay"){ echo ' selected ';}?>>千网</option>
							<option value='okpay'
								<?php if($info['bank_from']=="okpay"){ echo ' selected ';}?>>OK付</option>
							<option value='kxpay'
								<?php if($info['bank_from']=="kxpay"){ echo ' selected ';}?>>科迅</option>
							<option value='akpay'
								<?php if($info['bank_from']=="akpay"){ echo ' selected ';}?>>安快</option>
                          <option value='fxpay'
								<?php if($info['bank_from']=="fxpay"){ echo ' selected ';}?>>飞迅支付</option>
                          <option value='xypay'
								<?php if($info['bank_from']=="xypay"){ echo ' selected ';}?>>信盈支付</option>
                          <option value='tongbao'
								<?php if($info['bank_from']=="tongbao"){ echo ' selected ';}?>>通宝支付</option>
                          <option value='yspay'
								<?php if($info['bank_from']=="yspay"){ echo ' selected ';}?>>云盛支付</option>
                          <option value='smy'
								<?php if($info['bank_from']=="smy"){ echo ' selected ';}?>>收米云支付</option>
                          <option value='jfkpay'
								<?php if($info['bank_from']=="jfkpay"){ echo ' selected ';}?>>金付卡支付</option>
                          <option value='ddbpay'
								<?php if($info['bank_from']=="ddbpay"){ echo ' selected ';}?>>多得宝支付</option>
                          <option value='jhzpay'
								<?php if($info['bank_from']=="jhzpay"){ echo ' selected ';}?>>金海哲支付</option>
                          <option value='kfpay'
								<?php if($info['bank_from']=="kfpay"){ echo ' selected ';}?>>快付支付</option>
                          <option value='yyfpay'
								<?php if($info['bank_from']=="yyfpay"){ echo ' selected ';}?>>优易付支付</option>
                          <option value='xbpay'
								<?php if($info['bank_from']=="xbpay"){ echo ' selected ';}?>>迅宝支付</option>
                          <option value='shbpay'
								<?php if($info['bank_from']=="shbpay"){ echo ' selected ';}?>>速汇宝支付</option>
                       	   <option value='dlfpay'
								<?php if($info['bank_from']=="dlfpay"){ echo ' selected ';}?>>得力付支付</option>
						</select>
					</div>
				</div>

				<hr>

				<div class="control-group">
					<label class="control-label" for="alipaywap_open">支付宝APP通道</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="alipaywap_open" value="0"
							<?php if($info["alipaywap_open"]==0){echo ' checked="checked" ';}?>>
							开启
						</label> <label class="radio inline"> <input type="radio"
							name="alipaywap_open" value="1"
							<?php if($info["alipaywap_open"]==1){echo ' checked="checked" ';}?>>
							关闭
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="alipaywap_weihu">支付宝APP维护</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="alipaywap_weihu" value="0"
							<?php if($info["alipaywap_weihu"]==0){echo ' checked="checked" ';}?>>
							关闭
						</label> <label class="radio inline"> <input type="radio"
							name="alipaywap_weihu" value="1"
							<?php if($info["alipaywap_weihu"]==1){echo ' checked="checked" ';}?>>
							开启
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="alipaywap_from">支付宝APP方式</label>
					<div class="controls">
						<select name="alipaywap_from" id="alipaywap_from" class="span"
							style="width: 220px;">
							<option value='kdpay'
								<?php if($info['alipaywap_from']=="kdpay"){ echo ' selected ';}?>>口袋</option>
							<option value='jypay'
								<?php if($info['alipaywap_from']=="jypay"){ echo ' selected ';}?>>聚源</option>
							<option value='101pay'
								<?php if($info['alipaywap_from']=="101pay"){ echo ' selected ';}?>>101卡</option>

							<option value='yinbao'
								<?php if($info['alipaywap_from']=="yinbao"){ echo ' selected ';}?>>银宝</option>
							<option value='hfbao'
								<?php if($info['alipaywap_from']=="hfbao"){ echo ' selected ';}?>>慧付宝</option>
							<option value='kxpay'
								<?php if($info['alipaywap_from']=="kxpay"){ echo ' selected ';}?>>科迅</option>
							<option value='xfpay'
								<?php if($info['alipaywap_from']=="xfpay"){ echo ' selected ';}?>>迅付支付</option>
							<option value='duobao'
								<?php if($info['alipaywap_from']=="duobao"){ echo ' selected ';}?>>多宝支付</option>
							<option value='lbkpay'
								<?php if($info['alipaywap_from']=="lbkpay"){ echo ' selected ';}?>>龙卡宝支付</option>
							<option value='sfpay'
								<?php if($info['alipaywap_from']=="sfpay"){ echo ' selected ';}?>>顺付支付</option>
                          <option value='wsfpay'
								<?php if($info['alipaywap_from']=="wsfpay"){ echo ' selected ';}?>>旺实富支付</option>
                          <option value='fxpay'
								<?php if($info['alipaywap_from']=="fxpay"){ echo ' selected ';}?>>飞迅支付</option>
                          <option value='smy'
								<?php if($info['alipaywap_from']=="smy"){ echo ' selected ';}?>>收米云支付</option>
                          <option value='jfkpay'
								<?php if($info['alipaywap_from']=="jfkpay"){ echo ' selected ';}?>>金付卡支付</option>
                          <option value='jmypay'
								<?php if($info['alipaywap_from']=="jmypay"){ echo ' selected ';}?>>巨米云支付</option>
                          <option value='shunpay'
								<?php if($info['alipaywap_from']=="shunpay"){ echo ' selected ';}?>>瞬付支付</option>
						</select>
					</div>
				</div>

				<hr>

				<div class="control-group">
					<label class="control-label" for="wap_open">微信APP通道</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio" name="wap_open"
							value="0"
							<?php if($info["wap_open"]==0){echo ' checked="checked" ';}?>> 开启
						</label> <label class="radio inline"> <input type="radio"
							name="wap_open" value="1"
							<?php if($info["wap_open"]==1){echo ' checked="checked" ';}?>> 关闭
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="wap_weihu">微信APP维护</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio" name="wap_weihu"
							value="0"
							<?php if($info["wap_weihu"]==0){echo ' checked="checked" ';}?>>
							关闭
						</label> <label class="radio inline"> <input type="radio"
							name="wap_weihu" value="1"
							<?php if($info["wap_weihu"]==1){echo ' checked="checked" ';}?>>
							开启
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="wap_from">微信APP方式</label>
					<div class="controls">
						<select name="wap_from" id="wap_from" class="span"
							style="width: 220px;">

							<option value='kdpay'
								<?php if($info['wap_from']=="kdpay"){ echo ' selected ';}?>>口袋</option>
							<option value='101pay'
								<?php if($info['wap_from']=="101pay"){ echo ' selected ';}?>>101卡</option>
							<option value='hfbao'
								<?php if($info['wap_from']=="hfbao"){ echo ' selected ';}?>>慧付宝</option>
							<!--<option value='qxpay' <?php if($info['wap_from']=="qxpay"){ echo ' selected ';}?>>启讯</option> -->
							<option value='kxpay'
								<?php if($info['wap_from']=="kxpay"){ echo ' selected ';}?>>科迅</option>
							<option value='miaofupay'
								<?php if($info['wap_from']=="miaofupay"){ echo ' selected ';}?>>秒付支付</option>
							<option value='duobao'
								<?php if($info['wap_from']=="duobao"){ echo ' selected ';}?>>多宝支付</option>
							<option value='lbkpay'
								<?php if($info['wap_from']=="lbkpay"){ echo ' selected ';}?>>龙卡宝支付</option>
							<option value='sfpay'
								<?php if($info['wap_from']=="sfpay"){ echo ' selected ';}?>>顺付支付</option>
                          <option value='smy'
								<?php if($info['wap_from']=="smy"){ echo ' selected ';}?>>收米云支付</option>
                          <option value='jfkpay'
								<?php if($info['wap_from']=="jfkpay"){ echo ' selected ';}?>>金付卡支付</option>
						</select>
					</div>
				</div>

				<hr>

				<div class="control-group">
					<label class="control-label" for="wap_open">财付通</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="tenpay_open" value="0"
							<?php if($info["tenpay_open"]==0){echo ' checked="checked" ';}?>>
							开启
						</label> <label class="radio inline"> <input type="radio"
							name="tenpay_open" value="1"
							<?php if($info["tenpay_open"]==1){echo ' checked="checked" ';}?>>
							关闭
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="tenpay_weihu">财付通维护</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="tenpay_weihu" value="0"
							<?php if($info["tenpay_weihu"]==0){echo ' checked="checked" ';}?>>
							关闭
						</label> <label class="radio inline"> <input type="radio"
							name="tenpay_weihu" value="1"
							<?php if($info["tenpay_weihu"]==1){echo ' checked="checked" ';}?>>
							开启
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="tenpay">财付通方式</label>
					<div class="controls">
						<select name="tenpay" id="tenpay" class="span"
							style="width: 220px;">
							<option value='101pay'
								<?php if($info['tenpay']=="101pay"){ echo ' selected ';}?>>101卡</option>
							<option value='kxpay'
								<?php if($info['tenpay']=="kxpay"){ echo ' selected ';}?>>科迅支付</option>
						</select>
					</div>
				</div>

				<hr>

				<div class="control-group">
					<label class="control-label" for="qqpay_open">QQ扫码</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio" name="qqpay_open"
							value="0"
							<?php if($info["qqpay_open"]==0){echo ' checked="checked" ';}?>>
							开启
						</label> <label class="radio inline"> <input type="radio"
							name="qqpay_open" value="1"
							<?php if($info["qqpay_open"]==1){echo ' checked="checked" ';}?>>
							关闭
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="qqpay_weihu">QQ扫码维护</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="qqpay_weihu" value="0"
							<?php if($info["qqpay_weihu"]==0){echo ' checked="checked" ';}?>>
							关闭
						</label> <label class="radio inline"> <input type="radio"
							name="qqpay_weihu" value="1"
							<?php if($info["qqpay_weihu"]==1){echo ' checked="checked" ';}?>>
							开启
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="qqpay">QQ扫码方式</label>
					<div class="controls">
						<select name="qqpay" id="qqpay" class="span" style="width: 220px;">
                          <option value='lkfpay'
								<?php if($info['qqpay']=="lkfpay"){ echo ' selected ';}?>>立刻付支付</option>
                          <option value='kxpay'
								<?php if($info['qqpay']=="kxpay"){ echo ' selected ';}?>>科迅支付</option>
                          <option value='jbpay'
								<?php if($info['qqpay']=="jbpay"){ echo ' selected ';}?>>捷宝支付</option>
                          <option value='101pay'
								<?php if($info['qqpay']=="101pay"){ echo ' selected ';}?>>101卡支付</option>
                         <option value='wyfpay'
								<?php if($info['qqpay']=="wyfpay"){ echo ' selected ';}?>>外易付支付</option>
                            <option value='xbpay'
								<?php if($info['qqpay']=="xbpay"){ echo ' selected ';}?>>讯宝支付</option>
							
						</select>
					</div>
				</div>

				<hr>

				<div class="control-group">
					<label class="control-label" for="qqwappay_open">手机QQ钱包支付</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="qqwappay_open" value="0"
							<?php if($info["qqwappay_open"]==0){echo ' checked="checked" ';}?>>
							开启
						</label> <label class="radio inline"> <input type="radio"
							name="qqwappay_open" value="1"
							<?php if($info["qqwappay_open"]==1){echo ' checked="checked" ';}?>>
							关闭
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="qqwappay_weihu">手机QQ钱包维护</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="qqwappay_weihu" value="0"
							<?php if($info["qqwappay_weihu"]==0){echo ' checked="checked" ';}?>>
							关闭
						</label> <label class="radio inline"> <input type="radio"
							name="qqwappay_weihu" value="1"
							<?php if($info["qqwappay_weihu"]==1){echo ' checked="checked" ';}?>>
							开启
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="qqwappay">手机QQ钱包方式</label>
					<div class="controls">
						<select name="qqwappay" id="qqwappay" class="span"
							style="width: 220px;">
                          <option value='jbpay'
								<?php if($info['qqwappay']=="jbpay"){ echo ' selected ';}?>>捷宝支付</option>
							<option value='kxpay'
								<?php if($info['qqwappay']=="kxpay"){ echo ' selected ';}?>>科迅支付</option>
                          <option value='lkfpay'
								<?php if($info['qqwappay']=="lkfpay"){ echo ' selected ';}?>>立刻付支付</option>
						</select>
					</div>
				</div>

				<hr>
				
          		<div class="control-group">
					<label class="control-label" for="diankapay_open">点卡支付</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="diankapay_open" value="0"
							<?php if($info["diankapay_open"]==0){echo ' checked="checked" ';}?>>
							开启
						</label> <label class="radio inline"> <input type="radio"
							name="diankapay_open" value="1"
							<?php if($info["diankapay_open"]==1){echo ' checked="checked" ';}?>>
							关闭
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="diankapay_weihu">点卡维护</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="diankapay_weihu" value="0"
							<?php if($info["diankapay_weihu"]==0){echo ' checked="checked" ';}?>>
							关闭
						</label> <label class="radio inline"> <input type="radio"
							name="diankapay_weihu" value="1"
							<?php if($info["diankapay_weihu"]==1){echo ' checked="checked" ';}?>>
							开启
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="diankapay">点卡方式</label>
					<div class="controls">
						<select name="diankapay" id="diankapay" class="span"
							style="width: 220px;">
                          <option value='kxpay'
								<?php if($info['diankapay']=="kxpay"){ echo ' selected ';}?>>科迅支付</option>
						</select>
					</div>
				</div>
				
				<hr>
          		
          		<div class="control-group">
					<label class="control-label" for="jdpay_open">京东支付</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="jdpay_open" value="0"
							<?php if($info["jdpay_open"]==0){echo ' checked="checked" ';}?>>
							开启
						</label> <label class="radio inline"> <input type="radio"
							name="jdpay_open" value="1"
							<?php if($info["jdpay_open"]==1){echo ' checked="checked" ';}?>>
							关闭
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="jdpay_weihu">京东维护</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="jdpay_weihu" value="0"
							<?php if($info["jdpay_weihu"]==0){echo ' checked="checked" ';}?>>
							关闭
						</label> <label class="radio inline"> <input type="radio"
							name="jdpay_weihu" value="1"
							<?php if($info["jdpay_weihu"]==1){echo ' checked="checked" ';}?>>
							开启
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="jdpay">京东方式</label>
					<div class="controls">
						<select name="jdpay" id="jdpay" class="span"
							style="width: 220px;">
                          <option value='jhzpay'
								<?php if($info['jdpay']=="jhzpay"){ echo ' selected ';}?>>金海哲支付</option>
                          <option value='lkfpay'
								<?php if($info['jdpay']=="lkfpay"){ echo ' selected ';}?>>立刻付支付</option>
						</select>
					</div>
				</div>
          		
          		<hr>
          		
				<div class="control-group">
					<label class="control-label" for="page_size">默认分页</label>
					<div class="controls">
						<input type="text" id="page_size"
							value="<?php echo $info['page_size'];?>">
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<input type="hidden" id="hid_id" name="hid_id"
							value="<?php echo $info['id'];?>" />
						<button id="btnSave" class="btn btn-success">提交修改</button>
					</div>
				</div>
			</form>

		</div>

		<footer>
			<hr>
			<p class="pull-right">
				<a href="javascript:;">
                        <?php echo $appSet[ 'app_name'];?>
                    </a>
			</p>
			<p>&copy;
                    <?php echo $appSet[ 'company_year'];?>
                    <a href="<?php echo $appSet['company_url'];?>"
					title="<?php echo $appSet['company'];?>" target="_blank">
                        <?php echo $appSet[ 'company'];?>
                    </a>
			</p>
		</footer>

	</div>
</div>

<script type="text/javascript">
$(function(){
	$("#btnSave").click(function(){
		
		var page_size = $("#page_size").val();
		if(page_size == ""){
			alert("默认分页不能为空");
			return;
		}

		$.post("setting.php?act=edit", {
			id: $("#hid_id").val(),
			wechat_from: $("#wechat_from").val(),
			alipay_from: $("#alipay_from").val(),
			bank_from: $("#bank_from").val(),
			wap_from: $("#wap_from").val(),
			alipaywap_from: $("#alipaywap_from").val(),
         	diankapay_from: $("#diankapay_from").val(),
			tenpay: $("#tenpay").val(),
			qqpay: $("#qqpay").val(),
			qqwappay:$("#qqwappay").val(),
			diankapay:$("#diankapay").val(),
            jdpay:$("#jdpay").val(),
			page_size: page_size,
			wechat_open: $("input[name='wechat_open']:checked").val(),
			alipay_open: $("input[name='alipay_open']:checked").val(),
			bank_open: $("input[name='bank_open']:checked").val(),
			wap_open: $("input[name='wap_open']:checked").val(),
			alipaywap_open: $("input[name='alipaywap_open']:checked").val(),
			tenpay_open: $("input[name='tenpay_open']:checked").val(),
			qqpay_open: $("input[name='qqpay_open']:checked").val(),
			qqwappay_open: $("input[name='qqwappay_open']:checked").val(),
			diankapay_open: $("input[name='diankapay_open']:checked").val(),
            jdpay_open: $("input[name='jdpay_open']:checked").val(),
			wechat_weihu: $("input[name='wechat_weihu']:checked").val(),
			alipay_weihu: $("input[name='alipay_weihu']:checked").val(),
			bank_weihu: $("input[name='bank_weihu']:checked").val(),
			wap_weihu: $("input[name='wap_weihu']:checked").val(),
			alipaywap_weihu: $("input[name='alipaywap_weihu']:checked").val(),
			tenpay_weihu: $("input[name='tenpay_weihu']:checked").val(),
			qqpay_weihu: $("input[name='qqpay_weihu']:checked").val(),
			qqwappay_weihu: $("input[name='qqwappay_weihu']:checked").val(),
			diankapay_weihu: $("input[name='diankapay_weihu']:checked").val(),
            jdpay_weihu: $("input[name='jdpay_weihu']:checked").val(),
		}, function(obj) {
			if (obj.stat == 0) {
				alert('修改成功!');
				window.location.href = 'setting.php';
			} else {
				alert('修改失败!');
			}

		}, "json");
		
	})
})
</script>

<?php
}
?>

<script type="text/javascript">
$(function(){
	$("#legal-menu").addClass('in');
})
</script>


<?php include 'foot.php';?>
