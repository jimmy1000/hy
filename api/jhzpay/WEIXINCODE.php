<?php
//	return "<h1>{$name}</h1>";
	return <<<ETC
	<link href="./wechat_pay.css" rel="stylesheet" media="screen">
	
	<h1 class="mod-title">
        <span class="ico-wechat"></span><span class="text">微信付款码支付</span>
    </h1>
    <div class="mod-ct">
        <div class="order">
        </div>
        <div class="amount">
            <span>￥</span>$coin
        </div>
        <div class="qr-image" style="">
            <div id="barCode" style="margin-left: 155px;">

            </div>
        </div>
        <!--detail-open 加上这个类是展示订单信息，不加不展示-->
        <div class="detail detail-open" id="orderDetail" style="">
            <dl class="detail-ct" style="display: block;">
                <dt>请填写付款码:</dt>
                <dd>
                    <form action="{$formAction}" method="post">
                        <input  type='hidden' name='WEIXINCODE_FLAG'      value="1" />
                        <input  type='type'   name='authCode'   value=''  style="width:210px;height:28px;font-size:14px"/>
                </dd>
            </dl>
        </div>
        <div class="tip">
            <span class="dec dec-left"></span>
            <span class="dec dec-right"></span>
            <div class="ico-scan">
            </div>
            <div class="tip-text">
                <p>
                    <input type="submit"  value="提交数据" style="width:100px;height:25px;"/>
                </p>
            </div>
        </div>
    </div>
    <div class="foot">
        <div class="inner">
            <p>
            </p>
        </div>
    </div>
ETC;
