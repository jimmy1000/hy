<?php

error_reporting(E_ALL^E_NOTICE^E_WARNING);


$configArr = [
	//支付类型
	'pay_type' => [
		// 支付宝
		'ALIPAY' => [
			'desc' => '支付宝',
			'action' => ''
		],
		// 支付宝app
		'ALIPAYWAP' => [
			'desc' => '支付宝app',
			'action' => 'jump'
		],
		// 支付宝付款码
		'ALIPAYCODE' => [
			'desc' => '支付宝付款码',
			'action' => 'jump'
		],
		//微信
		'WECHAT' => [
			'desc' => '微信',
			'action' => ''
		],
		//微信App
		'WAP' => [
			'desc' => '微信App',
			'action' => 'jump'
		],
		// 微信付款码
		'WEIXINCODE' => [
			'desc' => '微信付款码',
			'action' => 'jump'
		],
		//财付通
		'TENPAY' => [
			'desc' => '财付通',
			'action' => ''
		],
		// QQ扫码方式
		'QQPAY' => [
			'desc' => 'QQ扫码方式',
			'action' => ''
		],
		// 手机QQ钱包方式,
		'QQWAPPAY' => [
			'desc' => '手机QQ钱包方式',
			'action' => 'jump'
		],
		// 京东
		'JDPAY' => [
			'desc' => '京东',
			'action' => ''
		],
		// 京东app
		'JDPAYWAP' => [
			'desc' => '京东app',
			'action' => 'jump'
		],
		// 点卡
		'DIANKAPAY' => [
			'desc' => '点卡',
			'action' => 'jump'
		],
		//银行
		'BANK' => [
			'desc' => '银行',
			'action' => 'jump'
		],
		// 网银WAP
		'BANKWAP' => [
			'desc' => '网银WAP',
			'action' => 'jump'
		],
		// 网银扫码
		'BANKSCAN' => [
			'desc' => '网银扫码',
			'action' => ''
		],
		// 网银快捷
		'BANKQUICK' => [
			'desc' => '网银快捷',
			'action' => 'jump'
		],
		// 美团
		'MEITUANPAY' => [
			'desc' => '美团',
			'action' => ''
		],
	],

	//支持的银行
	'bank_type' => [
		['962', '中信银行'],
		['963', '中国银行'],
		['964',	'农业银行'],
		['965',	'建设银行'],
		['967',	'工商银行'],
		['968',	'浙商银行'],
		['969',	'浙江稠州商业银行'],
		['970', '招商银行'],
		['971',	'邮政储蓄'],
		['972',	'兴业银行'],
		['973',	'顺德农村信用合作社'],
		['974',	'深圳发展银行'],
		['975',	'上海银行'],
		['976',	'上海农村商业银行'],
		['977',	'浦发银行'],
		['978',	'平安银行'],
		['979',	'南京银行'],
		['980', '民生银行'],
		['981',	'交通银行'],
		['982',	'华夏银行'],
		['983',	'杭州银行'],
		['984',	'广州市农村信用社'],
		['985', '广发银行'],
		['986',	'光大银行'],
		['987',	'东亚银行'],
		['988',	'渤海银行'],
		['989',	'北京银行'],
		['990',	'北京农村商业银行'],
	]
];

$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';

$http_host = array_key_exists('HTTP_HOST', $_SERVER) ? $_SERVER['HTTP_HOST'] : '';
$http_host = $http_type . $http_host .  '/api' ;

$act = array_key_exists('act', $_GET) ? $_GET['act'] : '';
if($act){
	if($act == 'url'){
		$hostname = array_key_exists('hostname', $_GET) ? $_GET['hostname'] : 'http://mypay.8889s.com/api';
		$username = array_key_exists('username', $_GET) ? $_GET['username'] : 'test';
		$coin = array_key_exists('coin', $_GET) ? $_GET['coin'] : '20';
		$path = array_key_exists('path', $_GET) ? $_GET['path'] : 'tudou';
		if(!$path){
			$path = 'tudou';
		}
	}
}

?>


<html>

<head>
	<title>支付接口测试url 生成</title>

    <link rel="stylesheet" type="text/css" href="/admin590/lib/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/admin590/stylesheets/theme.css">
    <link rel="stylesheet" href="/admin590/lib/font-awesome/css/font-awesome.css">
    <script src="/admin590/lib/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script type='text/javascript' src="https://cdn.staticfile.org/clipboard.js/1.5.15/clipboard.min.js"></script>
    <style>
        .table td {
            font-size: 14px;
            text-align: center;
        }
        .table th {
            text-align: center;
        }

    </style>
</head>

<body style="background-color: #f1f1f1">
    <div>

        <h3 style="text-align:center;">支付接口测试url 生成</h3>

        <div class="thumbnail" style="width: 30%;margin: 0 auto;padding-top: 40px;background: #fcfcfc;">
            <form class="form-horizontal" action="testPayUrl.php" method="get">
                <div class="control-group">
                    <label class="control-label" for="hostname">域 名:</label>
                    <div class="controls">
                        <input type="text" id="hostname" name="hostname" placeholder="" value="<?= $http_host ?>" style="height: 30px;width: 70%">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="username">用户名:</label>
                    <div class="controls">
                        <input type="text" id="username" name="username" placeholder="" value="<?php echo isset($username) ? $username : 'test'; ?>" style="height: 30px;width: 70%">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="coin">金 额:</label>
                    <div class="controls">
                        <input type="text" id="coin" name="coin" placeholder="" value="<?php echo isset($coin) ? $coin : 20; ?>" style="height: 30px;width: 70%">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label"  for="path">支付接口路径名:</label>
                    <div class=" input-append">
<!--                        <input type="text" id="path" name="path" placeholder="" value="20" style="height: 30px">-->
                        <input class="span2" id="path" name="path" style="height: 30px;margin-left: 20px;width: 70%" type="text" placeholder="例: tudou" value="<?php echo isset($path) ? $path : ''; ?>">
                        <span class="add-on">pay</span>
                    </div>
                </div>

                <div class="control-group">
                    <div class="controls">
                        <!--<label class="checkbox">
                            <input type="checkbox"> Remember me
                        </label>-->
                        <button type="submit" class="btn" name="act" value="url">生成url</button>
                    </div>
                </div>
            </form>
        </div>


        <?php if($act == 'url'): ?>
        <hr>
        <div class="" style="">
            <table class="table table-hover table-condensed table-bordered" style="background-color: #fcfcfc; margin: 0 auto">
                <tr>
                    <th>支付方式</th>

                    <th>支付地址</th>
                    <th>操作</th>
                </tr>

                <?php foreach($configArr['pay_type'] as $key => $value): ?>
                <tr>
                    <td><?= $value['desc'] ?></td>

                    <td id="url<?= $key ?>"><?= $hostname ?>/<?= $path ?>pay/pay.php?username=<?= $username ?>&coin=<?= $coin ?>&type=<?= $key ?></td>
                    <td>
                        <a class="btn btn-mini url" style="" href="<?= $hostname ?>/<?= $path ?>pay/pay.php?username=<?= $username ?>&coin=<?= $coin ?>&type=<?= $key ?>" target="_blank"><i class="icon-share-alt"></i> 跳转</a>
                        <a class="btn btn-mini btn-info copy" href="#" data-clipboard-target="#url<?= $key ?>"><i class="icon-file"></i> 复制</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4">银行</td>
                </tr>
				<?php foreach($configArr['bank_type'] as $value): ?>
                    <tr>
                        <td><?= $value[1] ?></td>

                        <td id="url<?= $value[0] ?>"><?= $hostname ?>/<?= $path ?>pay/pay.php?username=<?= $username ?>&coin=<?= $coin ?>&type=BANK&bank=<?= $value[0] ?></td>
                        <td>
                            <a class="btn btn-mini" style="" href="<?= $hostname ?>/<?= $path ?>pay/pay.php?username=<?= $username ?>&coin=<?= $coin ?>&type=BANK&bank=<?= $value[0] ?>" target="_blank"><i class="icon-share-alt"></i> 跳转</a>
                            <a class="btn btn-mini btn-info copy" href="javascript:void();" data-clipboard-target="#url<?= $value[0] ?>"><i class="icon-file"></i> 复制</a>
                        </td>
                    </tr>
				<?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <script>
        var clipboard = new Clipboard('.copy');
        // new Clipboard('.copy');
/*        $(".copy").zclip({
            path: "https://cdn.bootcss.com/zclip/1.1.2/ZeroClipboard.swf",
            copy: function(){
                return $(this).parent().find(".url").attr('href');
            },
            afterCopy:function(){/!* 复制成功后的操作 *!/
                var $copysuc = $("<div class='copy-tips'><div class='copy-tips-wrap'>☺ 复制成功</div></div>");
                $("body").find(".copy-tips").remove().end().append($copysuc);
                $(".copy-tips").fadeOut(3000);
            }
        });*/
    </script>
</body>
</html>
