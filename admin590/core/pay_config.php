<?php

return [
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

