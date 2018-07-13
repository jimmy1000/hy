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
            'alipaycode_from' => @$_POST ['alipaycode_from'],
            'wechatcode_from' => @$_POST ['wechatcode_from'],
            'page_size' => @$_POST ['page_size'],
            'alipay_open' => @$_POST ['alipay_open'],
            'alipay_weihu' => @$_POST ['alipay_weihu'],
            'alipaycode_open' => @$_POST ['alipaycode_open'],
            'alipaycode_weihu' => @$_POST ['alipaycode_weihu'],
            'wechat_open' => @$_POST ['wechat_open'],
            'wechat_weihu' => @$_POST ['wechat_weihu'],
            'wechatcode_open' => @$_POST ['wechatcode_open'],
            'wechatcode_weihu' => @$_POST ['wechatcode_weihu'],
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
            'kjzf_form' => @$_POST ['kjzf_form'],
            'kjzf_open' => @$_POST ['kjzf_open'],
            'kjzf_weihu' => @$_POST ['kjzf_weihu'],
            'bankwap_form' => @$_POST ['bankwap_form'],
            'bankwap_open' => @$_POST ['bankwap_open'],
            'bankwap_weihu' => @$_POST ['bankwap_weihu'],
            'jdpaywap_form' => @$_POST ['jdpaywap'],
        	'jdpaywap_open' => @$_POST ['jdpaywap_open'],
        	'jdpaywap_weihu' => @$_POST ['jdpaywap_weihu'],
          	'bankscan_form' => @$_POST ['bankscan_form'],
            'bankscan_open' => @$_POST ['bankscan_open'],
            'bankscan_weihu' => @$_POST ['bankscan_weihu'],
            'bankquick_form' => @$_POST ['bankquick_form'],
            'bankquick_open' => @$_POST ['bankquick_open'],
            'bankquick_weihu' => @$_POST ['bankquick_weihu'],
            //美团
			'meituanpay_form' => @$_POST ['meituanpay_form'],
			'meituanpay_open' => @$_POST ['meituanpay_open'],
			'meituanpay_weihu' => @$_POST ['meituanpay_weihu'],
        );
        $database->update(DB_PREFIX . 'set', $upArr, array(
            'id' => $id
        ));
        //var_dump($database->error());
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
							<option value='yinbao'
								<?php if($info['alipay_from']=="yinbao"){ echo ' selected ';}?>>银宝支付</option>
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
                          <option value='lkfpay'
								<?php if($info['alipay_from']=="lkfpay"){ echo ' selected ';}?>>立刻付支付</option>
                          <option value='kfpay'
								<?php if($info['alipay_from']=="kfpay"){ echo ' selected ';}?>>快付支付</option>
                           <option value='yyfpay'
								<?php if($info['alipay_from']=="yyfpay"){ echo ' selected ';}?>>优易付支付</option>
                          <option value='xbpay'
								<?php if($info['alipay_from']=="xbpay"){ echo ' selected ';}?>>迅宝支付</option>
                           <option value='dlfpay'
								<?php if($info['alipay_from']=="dlfpay"){ echo ' selected ';}?>>得力付支付</option>
                          <!-- <option value='rxpay'
								<?php if($info['alipay_from']=="rxpay"){ echo ' selected ';}?>>仁信支付</option>-->
                          <!-- <option value='shunpay'
								<?php if($info['alipay_from']=="shunpay"){ echo ' selected ';}?>>瞬付支付</option>-->
                          <option value='qypay'
								<?php if($info['alipay_from']=="qypay"){ echo ' selected ';}?>>轻易支付</option>
                          <option value='xhpay'
								<?php if($info['alipay_from']=="xhpay"){ echo ' selected ';}?>>信汇支付</option>
                          <option value='xfbpay'
								<?php if($info['alipay_from']=="xfbpay"){ echo ' selected ';}?>>迅付宝支付</option>
                          <option value='yintai'
								<?php if($info['alipay_from']=="yintai"){ echo ' selected ';}?>>银泰支付</option>
                            <option value='lbpay'
								<?php if($info['alipay_from']=="lbpay"){ echo ' selected ';}?>>萝卜支付</option>
                          <option value='sufupay'
								<?php if($info['alipay_from']=="sufupay"){ echo ' selected ';}?>>速付支付</option>
                           <option value='wfpay'
								<?php if($info['alipay_from']=="wfpay"){ echo ' selected ';}?>>微付支付</option>
                           <option value='gtpay'
								<?php if($info['alipay_from']=="gtpay"){ echo ' selected ';}?>>高通支付</option>
                          <option value='jbpay'
								<?php if($info['alipay_from']=="jbpay"){ echo ' selected ';}?>>捷宝支付</option>
                          <option value='xefpay'
								<?php if($info['alipay_from']=="xefpay"){ echo ' selected ';}?>>新e付支付</option>
                                        <option value='wftpay'
								<?php if($info['alipay_from']=="wftpay"){ echo ' selected ';}?>>网富通支付</option>
                                          <option value='dinyipay'
								<?php if($info['alipay_from']=="dinyipay"){ echo ' selected ';}?>>鼎易支付</option>
                                       <option value='xhytpay'
								<?php if($info['alipay_from']=="xhytpay"){ echo ' selected ';}?>>星和易通支付</option>
                                        <option value='zktpay'
								<?php if($info['alipay_from']=="zktpay"){ echo ' selected ';}?>>紫控支付</option>
                                      <option value='ybypay'
								<?php if($info['alipay_from']=="ybypay"){ echo ' selected ';}?>>易佰易支付</option>
                               <option value='ymspay'
								<?php if($info['alipay_from']=="ymspay"){ echo ' selected ';}?>>一码刷支付</option>
                           <option value='lspay'
								<?php if($info['alipay_from']=="lspay"){ echo ' selected ';}?>>联盛支付</option>
                            <option value='ampay'
								<?php if($info['alipay_from']=="ampay"){ echo ' selected ';}?>>安满支付</option>
                        <option value='benfupay'
								<?php if($info['alipay_from']=="benfupay"){ echo ' selected ';}?>>犇付支付</option>
                             <option value='alipay'
								<?php if($info['alipay_from']=="alipay"){ echo ' selected ';}?>>企业支付</option>
                            <option value='lypay'
								<?php if($info['alipay_from']=="lypay"){ echo ' selected ';}?>>蓝叶支付</option>
                            <option value='wwypay'
                                <?php if($info['alipay_from']=="wwypay"){ echo ' selected ';}?>>旺旺云支付</option>
                            <option value='tudoupay'
                                <?php if($info['alipay_from']=="tudoupay"){ echo ' selected ';}?>>土豆支付</option>
								 <option value='gfpay'
                                <?php if($info['alipay_from']=="gfpay"){ echo ' selected ';}?>>个付支付</option>
                            <option value='tianyipay'
								<?php if($info['alipay_from']=="tianyipay"){ echo ' selected ';}?>>天奕支付</option>
                            <option value='xytpay'
								<?php if($info['alipay_from']=="xytpay"){ echo ' selected ';}?>>迅游通支付</option>
                            <option value='jhzpay'
								<?php if($info['alipay_from']=="jhzpay"){ echo ' selected ';}?>>金海哲支付</option>
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
								<?php if($info['wechat_from']=="yinbao"){ echo ' selected ';}?>>银宝支付</option>
							<option value='kxpay'
								<?php if($info['wechat_from']=="kxpay"){ echo ' selected ';}?>>科迅支付</option>
							<option value='xfpay'
								<?php if($info['wechat_from']=="xfpay"){ echo ' selected ';}?>>迅付支付</option>
							<option value='danbao'
								<?php if($info['wechat_from']=="danbao"){ echo ' selected ';}?>>钱海支付</option>
							<option value='wsfpay'
								<?php if($info['wechat_from']=="wsfpay"){ echo ' selected ';}?>>旺实富支付</option>
							<!--<option value='rxpay'
								<?php if($info['wechat_from']=="rxpay"){ echo ' selected ';}?>>仁信支付</option>-->
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
                          <option value='qypay'
								<?php if($info['wechat_from']=="qypay"){ echo ' selected ';}?>>轻易付</option>
                          <option value='xhpay'
								<?php if($info['wechat_from']=="xhpay"){ echo ' selected ';}?>>信汇支付</option>
                           <option value='skbpay'
								<?php if($info['wechat_from']=="skbpay"){ echo ' selected ';}?>>时刻宝支付</option>
                             <option value='wftpay'
								<?php if($info['wechat_from']=="wftpay"){ echo ' selected ';}?>>网富通支付</option>
                          <option value='zcmpay'
								<?php if($info['wechat_from']=="zcmpay"){ echo ' selected ';}?>>招财猫支付</option>
                           <option value='gtpay'
								<?php if($info['wechat_from']=="gtpay"){ echo ' selected ';}?>>高通支付</option>
                          <option value='klpay'
								<?php if($info['wechat_from']=="klpay"){ echo ' selected ';}?>>快联支付</option>
                           <option value='qspay'
								<?php if($info['wechat_from']=="qspay"){ echo ' selected ';}?>>轻松支付</option>
                            <option value='wwypay'
                                <?php if($info['wechat_from']=="wwypay"){ echo ' selected ';}?>>旺旺云支付</option>
                            <option value='tudoupay'
                                <?php if($info['wechat_from']=="tudoupay"){ echo ' selected ';}?>>土豆支付</option>
								 <option value='gfpay'
                                <?php if($info['wechat_from']=="gfpay"){ echo ' selected ';}?>>个付支付</option>
                            <option value='tianyipay'
								<?php if($info['wechat_from']=="tianyipay"){ echo ' selected ';}?>>天奕支付</option>
                            <option value='xytpay'
								<?php if($info['wechat_from']=="xytpay"){ echo ' selected ';}?>>迅游通支付</option>
                            <option value='jhzpay'
								<?php if($info['wechat_from']=="jhzpay"){ echo ' selected ';}?>>金海哲支付</option>
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
                             <option value='lbpay'
								<?php if($info['bank_from']=="lbpay"){ echo ' selected ';}?>>萝卜支付</option>
                           <option value='xpay'
								<?php if($info['bank_from']=="xpay"){ echo ' selected ';}?>>星付支付</option>
                            <option value='wwypay'
                                <?php if($info['bank_from']=="wwypay"){ echo ' selected ';}?>>旺旺云支付</option>
                            <option value='tianyipay'
								<?php if($info['bank_from']=="tianyipay"){ echo ' selected ';}?>>天奕支付</option>
                            <option value='xytpay'
								<?php if($info['bank_from']=="xytpay"){ echo ' selected ';}?>>迅游通支付</option>
                            <option value='jhzpay'
								<?php if($info['bank_from']=="jhzpay"){ echo ' selected ';}?>>金海哲支付</option>
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
								<?php if($info['alipaywap_from']=="yinbao"){ echo ' selected ';}?>>银宝支付</option>
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
                         <!-- <option value='shunpay'
								<?php if($info['alipaywap_from']=="shunpay"){ echo ' selected ';}?>>瞬付支付</option>-->
                           <!--<option value='rxpay'
								<?php if($info['alipaywap_from']=="rxpay"){ echo ' selected ';}?>>仁信支付</option>-->
                        <!--  <option value='qypay'
								<?php if($info['alipaywap_from']=="qypay"){ echo ' selected ';}?>>轻易付</option>-->
                          <option value='xhpay'
								<?php if($info['alipaywap_from']=="xhpay"){ echo ' selected ';}?>>信汇支付</option>
                          <option value='gtpay'
								<?php if($info['alipaywap_from']=="gtpay"){ echo ' selected ';}?>>高通支付</option>
                          <option value='yunfx'
								<?php if($info['alipaywap_from']=="yunfx"){ echo ' selected ';}?>>云付盟支付</option>
                             <option value='wftpay'
								<?php if($info['alipaywap_from']=="wftpay"){ echo ' selected ';}?>>网富通支付</option>
                                  <option value='dinyipay'
								<?php if($info['alipaywap_from']=="dinyipay"){ echo ' selected ';}?>>鼎易支付</option>
                          <option value='xhytpay'
								<?php if($info['alipaywap_from']=="xhytpay"){ echo ' selected ';}?>>星和易通支付</option>
                            <option value='ydfpay'
								<?php if($info['alipaywap_from']=="ydfpay"){ echo ' selected ';}?>>易代付支付</option>
                            <option value='zkpay'
								<?php if($info['alipaywap_from']=="zkpay"){ echo ' selected ';}?>>紫控支付</option>
                           <option value='ybypay'
								<?php if($info['alipaywap_from']=="ybypay"){ echo ' selected ';}?>>易佰易支付</option>
                           <option value='fbpay'
								<?php if($info['alipaywap_from']=="fbpay"){ echo ' selected ';}?>>付呗支付</option>
                                <option value='ccpay'
								<?php if($info['alipaywap_from']=="ccpay"){ echo ' selected ';}?>>长城支付</option>
                             <option value='wcpay'
								<?php if($info['alipaywap_from']=="wcpay"){ echo ' selected ';}?>>沃城支付</option>
                          <option value='jinyuanpay'
								<?php if($info['alipaywap_from']=="jinyuanpay"){ echo ' selected ';}?>>景源支付</option>
                          <option value='lypay'
								<?php if($info['alipaywap_from']=="lypay"){ echo ' selected ';}?>>蓝叶支付</option>
                            <option value='zcmpay'
								<?php if($info['alipaywap_from']=="zcmpay"){ echo ' selected ';}?>>招财猫支付</option>
                            <option value='tianyipay'
								<?php if($info['alipaywap_from']=="tianyipay"){ echo ' selected ';}?>>天奕支付</option>
                            <option value='xytpay'
								<?php if($info['alipaywap_from']=="xytpay"){ echo ' selected ';}?>>迅游通支付</option>
                            <option value='jhzpay'
								<?php if($info['alipaywap_from']=="jhzpay"){ echo ' selected ';}?>>金海哲支付</option>
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
                          <option value='xhpay'
								<?php if($info['wap_from']=="xhpay"){ echo ' selected ';}?>>信汇支付</option>
                              <option value='haioupay	'
								<?php if($info['wap_from']=="haioupay"){ echo ' selected ';}?>>海鸥支付</option>
                          <option value='lbpay	'
								<?php if($info['wap_from']=="lbpay"){ echo ' selected ';}?>>萝卜支付</option>
                          <option value='xhytpay'
								<?php if($info['wap_from']=="xhytpay"){ echo ' selected ';}?>>星和易通支付</option>
                            <option value='xpay'
								<?php if($info['wap_from']=="xpay"){ echo ' selected ';}?>>星付支付</option>
                            <option value='32pay'
								<?php if($info['wap_from']=="32pay"){ echo ' selected ';}?>>32支付</option>
                            <option value='tianyipay'
								<?php if($info['wap_from']=="tianyipay"){ echo ' selected ';}?>>天奕支付</option>
                            <option value='xytpay'
								<?php if($info['wap_from']=="xytpay"){ echo ' selected ';}?>>迅游通支付</option>
                            <option value='jhzpay'
								<?php if($info['wap_from']=="jhzpay"){ echo ' selected ';}?>>金海哲支付</option>
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
                          <option value='shunpay'
								<?php if($info['tenpay']=="shunpay"){ echo ' selected ';}?>>瞬付支付</option>
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
                          <!--<option value='jbpay'
								<?php if($info['qqpay']=="jbpay"){ echo ' selected ';}?>>捷宝支付</option>-->
                          <option value='101pay'
								<?php if($info['qqpay']=="101pay"){ echo ' selected ';}?>>101卡支付</option>
                         <option value='qspay'
								<?php if($info['qqpay']=="qspay"){ echo ' selected ';}?>>轻松支付</option>
                            <option value='xbpay'
								<?php if($info['qqpay']=="xbpay"){ echo ' selected ';}?>>讯宝支付</option>
							<option value='xhpay'
								<?php if($info['qqpay']=="xhpay"){ echo ' selected ';}?>>信汇支付</option>
                          <option value='shunpay'
								<?php if($info['qqpay']=="shunpay"){ echo ' selected ';}?>>瞬付支付</option>
                             <option value='wftpay'
								<?php if($info['qqpay']=="wftpay"){ echo ' selected ';}?>>网富通支付</option>
                          <option value='gtpay'
								<?php if($info['qqpay']=="gtpay"){ echo ' selected ';}?>>高通支付</option>
                          <option value='zcmpay'
								<?php if($info['qqpay']=="zcmpay"){ echo ' selected ';}?>>招财猫支付</option>
                            <option value='wwypay'
                                <?php if($info['qqpay']=="wwypay"){ echo ' selected ';}?>>旺旺云支付</option>
                            <option value='tudoupay'
                                <?php if($info['qqpay']=="tudoupay"){ echo ' selected ';}?>>土豆支付</option>
                            <option value='tianyipay'
								<?php if($info['qqpay']=="tianyipay"){ echo ' selected ';}?>>天奕支付</option>
                            <option value='xytpay'
								<?php if($info['qqpay']=="xytpay"){ echo ' selected ';}?>>迅游通支付</option>
                            <option value='jhzpay'
								<?php if($info['qqpay']=="jhzpay"){ echo ' selected ';}?>>金海哲支付</option>
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
                          <!--<option value='jbpay'
								<?php if($info['qqwappay']=="jbpay"){ echo ' selected ';}?>>捷宝支付</option>-->
							<option value='kxpay'
								<?php if($info['qqwappay']=="kxpay"){ echo ' selected ';}?>>科迅支付</option>
                          <option value='lkfpay'
								<?php if($info['qqwappay']=="lkfpay"){ echo ' selected ';}?>>立刻付支付</option>
                          <option value='xhpay'
								<?php if($info['qqwappay']=="xhpay"){ echo ' selected ';}?>>信汇支付</option>
                          <option value='shunpay'
								<?php if($info['qqwappay']=="shunpay"){ echo ' selected ';}?>>瞬付支付</option>
                            <option value='wftpay'
								<?php if($info['qqwappay']=="wftpay"){ echo ' selected ';}?>>网富通支付</option>
                          <option value='xhytpay'
								<?php if($info['qqwappay']=="xhytpay"){ echo ' selected ';}?>>星和易通支付</option>
                            <option value='gtpay'
								<?php if($info['qqwappay']=="gtpay"){ echo ' selected ';}?>>高通通支付</option>
                             <option value='zcmpay'
								<?php if($info['qqwappay']=="zcmpay"){ echo ' selected ';}?>>招财猫支付</option>
                            <option value='qspay'
								<?php if($info['qqwappay']=="qspay"){ echo ' selected ';}?>>轻松支付</option>
                            <option value='xytpay'
								<?php if($info['qqwappay']=="xytpay"){ echo ' selected ';}?>>迅游通支付</option>
                            <option value='jhzpay'
								<?php if($info['qqwappay']=="jhzpay"){ echo ' selected ';}?>>金海哲支付</option>
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
                            <option value='jhzpay'
								<?php if($info['diankapay']=="jhzpay"){ echo ' selected ';}?>>金海哲支付</option>
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
                           <option value='kxpay'
								<?php if($info['jdpay']=="kxpay"){ echo ' selected ';}?>>科迅支付</option>
                           <option value='gtpay'
								<?php if($info['jdpay']=="gtpay"){ echo ' selected ';}?>>高通支付</option>
                              <option value='lbpay'
								<?php if($info['jdpay']=="lbpay"){ echo ' selected ';}?>>萝卜支付</option>
                            <option value='tudoupay'
                                <?php if($info['jdpay']=="tudoupay"){ echo ' selected ';}?>>土豆支付</option>
                            <option value='tianyipay'
								<?php if($info['jdpay']=="tianyipay"){ echo ' selected ';}?>>天奕支付</option>
                            <option value='xytpay'
								<?php if($info['jdpay']=="xytpay"){ echo ' selected ';}?>>迅游通支付</option>
                            <option value='jhzpay'
								<?php if($info['jdpay']=="jhzpay"){ echo ' selected ';}?>>金海哲支付</option>
						</select>
					</div>
				</div>
          		
          		<hr>
              
              	<div class="control-group">
					<label class="control-label" for="jdpaywap_open">京东APP</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="jdpaywap_open" value="0"
							<?php if($info["jdpaywap_open"]==0){echo ' checked="checked" ';}?>>
							开启
						</label> <label class="radio inline"> <input type="radio"
							name="jdpaywap_open" value="1"
							<?php if($info["jdpaywap_open"]==1){echo ' checked="checked" ';}?>>
							关闭
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="jdpaywap_weihu">京东APP维护</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="jdpaywap_weihu" value="0"
							<?php if($info["jdpaywap_weihu"]==0){echo ' checked="checked" ';}?>>
							关闭
						</label> <label class="radio inline"> <input type="radio"
							name="jdpaywap_weihu" value="1"
							<?php if($info["jdpaywap_weihu"]==1){echo ' checked="checked" ';}?>>
							开启
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="jdpaywap">京东APP方式</label>
					<div class="controls">
						<select name="jdpaywap" id="jdpaywap" class="span"
							style="width: 220px;">
                          <option value='kxpay'
								<?php if($info['jdpaywap_form']=="kxpay"){ echo ' selected ';}?>>科迅支付</option>
                          <option value='gtpay'
								<?php if($info['jdpaywap_form']=="gtpay"){ echo ' selected ';}?>>高通支付</option>
                            <option value='dscdfdf'
								<?php if($info['jdpaywap_form']=="dscdfdf"){ echo ' selected ';}?>>神奇的支付方式</option>
                            <option value='jhzpay'
								<?php if($info['jdpaywap_form']=="jhzpay"){ echo ' selected ';}?>>金海哲支付方式</option>
						</select>
					</div>
				</div>
          		<hr>
          		<div class="control-group">
					<label class="control-label" for="bankwap_open">网银WAP</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="bankwap_open" value="0"
							<?php if($info["bankwap_open"]==0){echo ' checked="checked" ';}?>>
							开启
						</label> <label class="radio inline"> <input type="radio"
							name="bankwap_open" value="1"
							<?php if($info["bankwap_open"]==1){echo ' checked="checked" ';}?>>
							关闭
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="bankwap_weihu">网银WAP维护</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="bankwap_weihu" value="0"
							<?php if($info["bankwap_weihu"]==0){echo ' checked="checked" ';}?>>
							关闭
						</label> <label class="radio inline"> <input type="radio"
							name="bankwap_weihu" value="1"
							<?php if($info["bankwap_weihu"]==1){echo ' checked="checked" ';}?>>
							开启
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="bankwap_form">网银WAP方式</label>
					<div class="controls">
						<select name="bankwap_form" id="bankwap_form" class="span"
							style="width: 220px;">
                          <option value='xbpay'
								<?php if($info['bankwap_form']=="xbpay"){ echo ' selected ';}?>>迅宝支付</option>
                          <option value='yunfx'
								<?php if($info['bankwap_form']=="yunfx"){ echo ' selected ';}?>>云付盟支付</option>
                           <option value='lbpay'
								<?php if($info['bankwap_form']=="lbpay"){ echo ' selected ';}?>>萝卜支付</option>
                          <option value='ybpay'
								<?php if($info['bankwap_form']=="ybpay"){ echo ' selected ';}?>>月宝支付</option>
                           <option value='benfupay'
								<?php if($info['bankwap_form']=="benfupay"){ echo ' selected ';}?>>犇付支付</option>
                            <option value='qqfpay'
								<?php if($info['bankwap_form']=="qqfpay"){ echo ' selected ';}?>>全球付支付</option>
                            <option value='tianyipay'
								<?php if($info['bankwap_form']=="tianyipay"){ echo ' selected ';}?>>天奕付支付</option>
						</select>
					</div>
				</div>
          		
          	  <hr>
              <div class="control-group">
					<label class="control-label" for="bankscan_open">网银扫码</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="bankscan_open" value="0"
							<?php if($info["bankscan_open"]==0){echo ' checked="checked" ';}?>>
							开启
						</label> <label class="radio inline"> <input type="radio"
							name="bankscan_open" value="1"
							<?php if($info["bankscan_open"]==1){echo ' checked="checked" ';}?>>
							关闭
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="bankscan_weihu">网银扫码维护</label>
					<div class="controls">
						<label class="radio inline"> <input type="radio"
							name="bankscan_weihu" value="0"
							<?php if($info["bankscan_weihu"]==0){echo ' checked="checked" ';}?>>
							关闭
						</label> <label class="radio inline"> <input type="radio"
							name="bankscan_weihu" value="1"
							<?php if($info["bankscan_weihu"]==1){echo ' checked="checked" ';}?>>
							开启
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="bankscan_form">网银扫码方式</label>
					<div class="controls">
						<select name="bankscan_form" id="bankscan_form" class="span"
							style="width: 220px;">
                          <option value='xhytpay'
								<?php if($info['bankscan_form']=="xhytpay"){ echo ' selected ';}?>>星和易通</option>
                            <option value='gtpay'
								<?php if($info['bankscan_form']=="gtpay"){ echo ' selected ';}?>>高通支付</option>
                            <option value='lbpay'
								<?php if($info['bankscan_form']=="lbpay"){ echo ' selected ';}?>>萝卜支付</option>
                            <option value='kxpay'
								<?php if($info['bankscan_form']=="kxpay"){ echo ' selected ';}?>>科迅支付</option>
                            <option value='wwypay'
                                <?php if($info['bankscan_form']=="wwypay"){ echo ' selected ';}?>>旺旺云支付</option>
                            <option value='tudoupay'
								<?php if($info['bankscan_form']=="tudoupay"){ echo ' selected ';}?>>土豆支付</option>
                            <option value='tianyipay'
								<?php if($info['bankscan_form']=="tianyipay"){ echo ' selected ';}?>>天奕支付</option>
                            <option value='xytpay'
								<?php if($info['bankscan_form']=="xytpay"){ echo ' selected ';}?>>迅游通支付</option>
                            <option value='jhzpay'
								<?php if($info['bankscan_form']=="jhzpay"){ echo ' selected ';}?>>金海哲支付</option>

						</select>
					</div>
				</div>

                <hr>
                <div class="control-group">
                    <label class="control-label" for="bankquick_open">网银快捷</label>
                    <div class="controls">
                        <label class="radio inline"> <input type="radio"
                                                            name="bankquick_open" value="0"
								<?php if($info["bankquick_open"]==0){echo ' checked="checked" ';}?>>
                            开启
                        </label> <label class="radio inline"> <input type="radio"
                                                                     name="bankquick_open" value="1"
								<?php if($info["bankquick_open"]==1){echo 'checked="checked"';}?>>
                            关闭
                        </label>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="bankquick_weihu">网银快捷维护</label>
                    <div class="controls">
                        <label class="radio inline"> <input type="radio"
                                                            name="bankquick_weihu" value="0"
								<?php if($info["bankquick_weihu"]==0){echo ' checked="checked" ';}?>>
                            关闭
                        </label> <label class="radio inline"> <input type="radio"
                                                                     name="bankquick_weihu" value="1"
								<?php if($info["bankquick_weihu"]==1){echo ' checked="checked" ';}?>>
                            开启
                        </label>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="bankquick_form">网银快捷方式</label>
                    <div class="controls">
                        <select name="bankquick_form" id="bankquick_form" class="span"
                                style="width: 220px;">
                            <option value='xhytpay'
								<?php  if($info['bankquick_form']=="xhytpay"){ echo ' selected ';}?>>星和易通</option>
                            <option value='gtpay'
								<?php if($info['bankquick_form']=="gtpay"){ echo ' selected ';}?>>高通支付</option>
                            <option value='lbpay'
								<?php if($info['bankquick_form']=="lbpay"){ echo ' selected ';}?>>萝卜支付</option>
                            <option value='kxpay'
								<?php if($info['bankquick_form']=="kxpay"){ echo ' selected ';}?>>科迅支付</option>
                            <option value='wwypay'
								<?php if($info['bankquick_form']=="wwypay"){ echo ' selected ';}?>>旺旺云支付</option>
                            <option value='jypay'
								<?php if($info['bankquick_form']=="jypay"){ echo ' selected ';}?>>金阳支付</option>
                            <option value='tianyipay'
								<?php if($info['bankquick_form']=="tianyipay"){ echo ' selected ';}?>>天奕付支付</option>
                        </select>
                    </div>
                </div>
                <hr>
<!--            支付宝付款码-->
                <div class="control-group">
                    <label class="control-label" for="alipaycode_open">支付宝付款码支付开启</label>
                    <div class="controls">
                        <label class="radio inline"> <input type="radio"
                                                            name="alipaycode_open" value="0"
                                <?php if($info["alipaycode_open"]==0){echo ' checked="checked" ';}?>>
                            开启
                        </label> <label class="radio inline"> <input type="radio"
                                                                     name="alipaycode_open" value="1"
                                <?php if($info["alipaycode_open"]==1){echo ' checked="checked" ';}?>>
                            关闭
                        </label>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="alipaycode_weihu">支付宝付款码支付维护</label>
                    <div class="controls">
                        <label class="radio inline"> <input type="radio"
                                                            name="alipaycode_weihu" value="0"
                                <?php if($info["alipaycode_weihu"]==0){echo ' checked="checked" ';}?>>
                            关闭
                        </label> <label class="radio inline"> <input type="radio"
                                                                     name="alipaycode_weihu" value="1"
                                <?php if($info["alipaycode_weihu"]==1){echo ' checked="checked" ';}?>>
                            开启
                        </label>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="alipaycode_from">支付宝付款码支付方式</label>
                    <div class="controls">
                        <select name="alipaycode_from" id="alipaycode_from" class="span"
                                style="width: 220px;">
                            <option value='gtonepay'
                                <?php if($info['alipaycode_from']=="gtonepay"){ echo ' selected ';}?>>汇通云付</option>

                        </select>
                    </div>
                </div>

                <hr>


                <!--     微信付款码-->
                <div class="control-group">
                    <label class="control-label" for="wechatcode_open">微信付款码支付开启</label>
                    <div class="controls">
                        <label class="radio inline"> <input type="radio"
                                                            name="wechatcode_open" value="0"
                                <?php if($info["wechatcode_open"]==0){echo ' checked="checked" ';}?>>
                            开启
                        </label> <label class="radio inline"> <input type="radio"
                                                                     name="wechatcode_open" value="1"
                                <?php if($info["wechatcode_open"]==1){echo ' checked="checked" ';}?>>
                            关闭
                        </label>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="wechatcode_weihu">微信付款码支付维护</label>
                    <div class="controls">
                        <label class="radio inline"> <input type="radio"
                                                            name="wechatcode_weihu" value="0"
                                <?php if($info["wechatcode_weihu"]==0){echo ' checked="checked" ';}?>>
                            关闭
                        </label> <label class="radio inline"> <input type="radio"
                                                                     name="wechatcode_weihu" value="1"
                                <?php if($info["wechatcode_weihu"]==1){echo ' checked="checked" ';}?>>
                            开启
                        </label>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="wechatcode_from">微信付款码支付方式</label>
                    <div class="controls">
                        <select name="wechatcode_from" id="wechatcode_from" class="span"
                                style="width: 220px;">
                            <option value='gtonepay'
								<?php if($info['wechatcode_from']=="gtonepay"){ echo ' selected ';}?>>汇通云付</option>
                            <option value='jhzpay'
								<?php if($info['wechatcode_from']=="jhzpay"){ echo ' selected ';}?>>金海哲支付</option>

                        </select>
                    </div>
                </div>

                <hr>

                <!--                美团-->
                <div class="control-group">
                    <label class="control-label" for="meituanpay_open">美团支付开关</label>
                    <div class="controls">
                        <label class="radio inline"> <input type="radio"
                                                            name="meituanpay_open" value="0"
								<?php if($info["meituanpay_open"]==0){echo ' checked="checked" ';}?>>
                            开启
                        </label> <label class="radio inline"> <input type="radio"
                                                                     name="meituanpay_open" value="1"
								<?php if($info["meituanpay_open"]==1){echo 'checked="checked"';}?>>
                            关闭
                        </label>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="meituanpay_weihu">美团支付维护</label>
                    <div class="controls">
                        <label class="radio inline"> <input type="radio"
                                                            name="meituanpay_weihu" value="0"
								<?php if($info["meituanpay_weihu"]==0){echo ' checked="checked" ';}?>>
                            关闭
                        </label> <label class="radio inline"> <input type="radio"
                                                                     name="meituanpay_weihu" value="1"
								<?php if($info["meituanpay_weihu"]==1){echo ' checked="checked" ';}?>>
                            开启
                        </label>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="meituanpay_form">美团支付方式</label>
                    <div class="controls">
                        <select name="meituanpay_form" id="meituanpay_form" class="span"
                                style="width: 220px;">
                            <option value='xxx'
								<?php  if($info['meituanpay_form']=="xxx"){ echo ' selected ';}?>>没有</option>
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
            alipaycode_from:$("#alipaycode_from").val(),
            wechatcode_from:$("#wechatcode_from").val(),
			tenpay: $("#tenpay").val(),
			qqpay: $("#qqpay").val(),
			qqwappay:$("#qqwappay").val(),
			diankapay:$("#diankapay").val(),
            jdpay:$("#jdpay").val(),
            kjzf_form:$("#kjzf_form").val(),
            bankwap_form:$("#bankwap_form").val(),
          	bankscan_form:$("#bankscan_form").val(),
            bankquick_form:$("#bankquick_form").val(),
            jdpaywap:$('#jdpaywap').val(),
			page_size: page_size,
			wechat_open: $("input[name='wechat_open']:checked").val(),
            wechatcode_open: $("input[name='wechatcode_open']:checked").val(),
			alipay_open: $("input[name='alipay_open']:checked").val(),
			bank_open: $("input[name='bank_open']:checked").val(),
			wap_open: $("input[name='wap_open']:checked").val(),
            alipaycode_open: $("input[name='alipaycode_open']:checked").val(),
			alipaywap_open: $("input[name='alipaywap_open']:checked").val(),
			tenpay_open: $("input[name='tenpay_open']:checked").val(),
			qqpay_open: $("input[name='qqpay_open']:checked").val(),
			qqwappay_open: $("input[name='qqwappay_open']:checked").val(),
			diankapay_open: $("input[name='diankapay_open']:checked").val(),
            jdpay_open: $("input[name='jdpay_open']:checked").val(),
            kjzf_open: $("input[name='kjzf_open']:checked").val(),
           	bankwap_open: $("input[name='bankwap_open']:checked").val(),
          	bankscan_open: $("input[name='bankscan_open']:checked").val(),
          	bankquick_open: $("input[name='bankquick_open']:checked").val(),
           	jdpaywap_open:$("input[name='jdpaywap_open']:checked").val(),
			wechat_weihu: $("input[name='wechat_weihu']:checked").val(),
            wechatcode_weihu: $("input[name='wechatcode_weihu']:checked").val(),
			alipay_weihu: $("input[name='alipay_weihu']:checked").val(),
            alipaycode_weihu: $("input[name='alipaycode_weihu']:checked").val(),
			bank_weihu: $("input[name='bank_weihu']:checked").val(),
			wap_weihu: $("input[name='wap_weihu']:checked").val(),
			alipaywap_weihu: $("input[name='alipaywap_weihu']:checked").val(),
			tenpay_weihu: $("input[name='tenpay_weihu']:checked").val(),
			qqpay_weihu: $("input[name='qqpay_weihu']:checked").val(),
			qqwappay_weihu: $("input[name='qqwappay_weihu']:checked").val(),
			diankapay_weihu: $("input[name='diankapay_weihu']:checked").val(),
            jdpay_weihu: $("input[name='jdpay_weihu']:checked").val(),
            kjzf_weihu:$("input[name='kjzf_weihu']:checked").val(),
            jdpaywap_weihu:$("input[name='jdpaywap_weihu']:checked").val(),
            bankwap_weihu: $("input[name='bankwap_weihu']:checked").val(),
            bankscan_weihu: $("input[name='bankscan_weihu']:checked").val(),
            bankquick_weihu: $("input[name='bankquick_weihu']:checked").val(),
            //美团
            meituanpay_form:$('#meituanpay_form').val(),
            meituanpay_open: $("input[name='meituanpay_open']:checked").val(),
            meituanpay_weihu: $("input[name='meituanpay_weihu']:checked").val(),
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
