<?php
    include 'global.php';
?>

<?php
    include 'base.php';
?>

<?php
    $configArr = include_once "core/pay_config.php";
?>

<style>
    #api-form {
        margin-top: 40px;
    }
    .row-cust {
        width: 40%;
        float: left;
        margin-left: -150px;
    }
    /*.control-group*/


    .cust .control-group {
        position: relative;
        left: 50px;
    }
    .is_hidden {
        display: none;
    }

    .cust .title {
        font-weight: 700;
        font-size: 16px;
        margin: 10px;
        padding-bottom: 5px;
        border-bottom: 1px solid #c5c5c5;
    }

    .icon-plus {
        color: #1ab394;
    }
    .icon-remove {
        color: #9d261d;
    }

    .icon-remove, .icon-plus {
        font-size: 20px;
        display: inline-block;
        margin-top: 6px;
        margin-left: 15px;
        cursor:pointer
    }

    .sign-function {
        width: 90%;
        height: 300px;
    }
    .sign-key {
        margin-left: 30px;
    }
    .request-name{
        font-size: 18px;
        font-weight: 700;
    }

</style>
<!-- page start -->
<div class="content">
    <div class="header">
        <h1 class="page-title">api代码生成</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.php">主页</a>  <span class="divider">/</span>
        </li>
        <li class="active">api代码生成</li>
    </ul>
    <div class="container-fluid">

        <div class="row-fluid">
            <form class="form-horizontal" id="api-form" >

                <div class="control-group">
                    <label class="control-label" for="inputEmail">支付接口商名称(中文)</label>
                    <div class="controls">
                        <input type="text" id="api_name" name="api_name" placeholder="例: 土豆">
                    </div>
                </div>

                <div class="is_hidden">
                    <div class="control-group">
                        <label class="control-label" for="inputEmail">文件夹名称</label>
                        <div class="controls">
                            <input type="text" id="api_dir_path" name="api_dir_path" readonly>
                        </div>
                    </div>

                    <div class="thumbnails">
                        <div class="span2"></div>
                        <label class="checkbox span2">
                            <input name="is_many_request" type="checkbox" value="1">是否为多请求地址
                        </label>
                        <label class="checkbox span2">
                            <input name="is_bank" type="checkbox" value="1">是否支持银行
                        </label>
                    </div>

                    <hr>

                    <div class="cust cust-config thumbnails thumbnail">
                        <div class="title">配置文件参数:</div>
                        <div class="head thumbnails">
                            <div class="span3">参数名</div>
                            <div class="span3">参数值</div>
                            <div class="span3">注 释</div>
                            <div class="span3">操 作</div>
                        </div>

                        <div class="thumbnails">
                            <div class="span3"><input type="text" name="global[config_key][]" value="local_url" readonly></div>
                            <div class="span3"><input type="text" name="global[config_value][]" id="" value="http://payhy.8889s.com"></div>
                            <div class="span3"><input type="text" name="global[config_note][]" value="本地域名"></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"><input type="text" name="global[config_key][]" value="notify_url" readonly></div>
                            <div class="span3"><input type="text" name="global[config_value][]" id="notify_url" value=""></div>
                            <div class="span3"><input type="text" name="global[config_note][]" value="支付回调接口"></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"><input type="text" name="global[config_key][]" value="return_url" readonly></div>
                            <div class="span3"><input type="text" name="global[config_value][]" value=":$local_url . ''"></div>
                            <div class="span3"><input type="text" name="global[config_note][]" value="支付成功跳转地址"></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"><input type="text" name="global[config_key][]" value="app_id" readonly></div>
                            <div class="span3"><input type="text" name="global[config_value][]" value=""></div>
                            <div class="span3"><input type="text" name="global[config_note][]" value="支付系统平台号"></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"><input type="text" name="global[config_key][]" value="app_key" readonly></div>
                            <div class="span3"><input type="text" name="global[config_value][]" value=""></div>
                            <div class="span3"><input type="text" name="global[config_note][]" value="秘钥"></div>
                        </div>
                        <div class="thumbnails sign-request-show" id="pay-request-url">
                            <div class="span3"><input type="text" name="global[config_key][]" value="pay_request_url" readonly></div>
                            <div class="span3"><input type="text" name="global[config_value][]" value=""></div>
                            <div class="span3"><input type="text" name="global[config_note][]" value="支付请求接口"></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"><input type="text" name="global[config_key][]" value="pay_query_url" readonly></div>
                            <div class="span3"><input type="text" name="global[config_value][]" value=""></div>
                            <div class="span3"><input type="text" name="global[config_note][]" value="支付查询接口"></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"><input type="text" name="global[config_key][]" value="" ></div>
                            <div class="span3"><input type="text" name="global[config_value][]" value=""></div>
                            <div class="span3"><input type="text" name="global[config_note][]" value=""></div>
                            <div class="span1"><i class="icon-plus"></i></div>
                            <div class="span1"><i class="icon-remove"></i></div>
                        </div>

                        <div class="many-request-show">
                            <div>多请求地址</div>
                            <div class="thumbnails" data-index="0">
                                <div class="span3"><input type="text" name="global[many_request_key][]" value="" placeholder="例: ali"></div>
                                <div class="span3"><input type="text" name="global[many_request_url][]" value=""></div>
                                <div class="span3"><input type="text" name="global[many_request_note][]" value="" placeholder="例: 支付宝支付地址"></div>
                                <div class="span1"><i class="icon-plus"></i></div>
                                <div class="span1"><i class="icon-remove"></i></div>
                            </div>
                        </div>


                    </div>

                    <hr>

                    <div class="cust cust-pay thumbnails thumbnail">
                        <div class="title">支付请求参数:</div>
                        <div class="head thumbnails">
                            <div class="span3">参数名</div>
                            <div class="span3">参数值</div>
                            <div class="span3">注 释</div>
                            <div class="span3">操 作</div>
                        </div>

                        <!--  单个请求  -->
                        <div id="single-request" class="sign-request-show">
                            <div class="thumbnails">
                                <div class="span3"><input type="text" name="global[pay_key][]" value="merchantcode" ></div>
                                <div class="span3"><input type="text" name="global[pay_value][]" value=":$app_id"></div>
                                <div class="span3"><input type="text" name="global[pay_note][]" value="商户号"></div>
                                <div class="span1"><i class="icon-plus"></i></div>
                                <div class="span1"><i class="icon-remove"></i></div>
                            </div>
                            <div class="thumbnails">
                                <div class="span3"><input type="text" name="global[pay_key][]" value="type" ></div>
                                <div class="span3"><input type="text" name="global[pay_value][]" value=":$type['code']"></div>
                                <div class="span3"><input type="text" name="global[pay_note][]" value="通道类型"></div>
                                <div class="span1"><i class="icon-plus"></i></div>
                                <div class="span1"><i class="icon-remove"></i></div>
                            </div>
                            <div class="thumbnails">
                                <div class="span3"><input type="text" name="global[pay_key][]" value="amount" ></div>
                                <div class="span3"><input type="text" name="global[pay_value][]" value=":$coin"></div>
                                <div class="span3"><input type="text" name="global[pay_note][]" value="金额"></div>
                                <div class="span1"><i class="icon-plus"></i></div>
                                <div class="span1"><i class="icon-remove"></i></div>
                            </div>
                            <div class="thumbnails">
                                <div class="span3"><input type="text" name="global[pay_key][]" value="orderid" ></div>
                                <div class="span3"><input type="text" name="global[pay_value][]" value=":$order_id"></div>
                                <div class="span3"><input type="text" name="global[pay_note][]" value="商户订单号"></div>
                                <div class="span1"><i class="icon-plus"></i></div>
                                <div class="span1"><i class="icon-remove"></i></div>
                            </div>
                            <div class="thumbnails">
                                <div class="span3"><input type="text" name="global[pay_key][]" value="notifyurl" ></div>
                                <div class="span3"><input type="text" name="global[pay_value][]" value=":$notify_url"></div>
                                <div class="span3"><input type="text" name="global[pay_note][]" value="下行异步通知地址"></div>
                                <div class="span1"><i class="icon-plus"></i></div>
                                <div class="span1"><i class="icon-remove"></i></div>
                            </div>
                            <div class="thumbnails">
                                <div class="span3"><input type="text" name="global[pay_key][]" value="callbackurl" ></div>
                                <div class="span3"><input type="text" name="global[pay_value][]" value=":$return_url"></div>
                                <div class="span3"><input type="text" name="global[pay_note][]" value="下行同步通知地址"></div>
                                <div class="span1"><i class="icon-plus"></i></div>
                                <div class="span1"><i class="icon-remove"></i></div>
                            </div>
                            <div class="thumbnails">
                                <div class="span3"><input type="text" name="global[pay_key][]" value="clientip" ></div>
                                <div class="span3"><input type="text" name="global[pay_value][]" value=":get_client_ip()"></div>
                                <div class="span3"><input type="text" name="global[pay_note][]" value="支付用户IP"></div>
                                <div class="span1"><i class="icon-plus"></i></div>
                                <div class="span1"><i class="icon-remove"></i></div>
                            </div>
                            <div class="thumbnails">
                                <div class="span3"><input type="text" name="global[pay_key][]" value="desc" ></div>
                                <div class="span3"><input type="text" name="global[pay_value][]" value="1"></div>
                                <div class="span3"><input type="text" name="global[pay_note][]" value="备注消息"></div>
                                <div class="span1"><i class="icon-plus"></i></div>
                                <div class="span1"><i class="icon-remove"></i></div>
                            </div>
                        </div>
                        <!--  多个请求  -->
                        <div id="many-request" class="many-request-show">
                            <div class="thumbnails thumbnail" data-index="0" id="">
                                <div class="request-name" style="font-size">&nbsp;</div>
                                <div class="thumbnails">
                                    <div class="span3"><input type="text" name="global[pay__key][]" value="merchantcode" ></div>
                                    <div class="span3"><input type="text" name="global[pay__value][]" value=":$app_id"></div>
                                    <div class="span3"><input type="text" name="global[pay__note][]" value="商户号"></div>
                                    <div class="span1"><i class="icon-plus"></i></div>
                                    <div class="span1"><i class="icon-remove"></i></div>
                                </div>
                                <div class="thumbnails">
                                    <div class="span3"><input type="text" name="global[pay__key][]" value="type" ></div>
                                    <div class="span3"><input type="text" name="global[pay__value][]" value=":$type['code']"></div>
                                    <div class="span3"><input type="text" name="global[pay__note][]" value="通道类型"></div>
                                    <div class="span1"><i class="icon-plus"></i></div>
                                    <div class="span1"><i class="icon-remove"></i></div>
                                </div>
                                <div class="thumbnails">
                                    <div class="span3"><input type="text" name="global[pay__key][]" value="amount" ></div>
                                    <div class="span3"><input type="text" name="global[pay__value][]" value=":$coin"></div>
                                    <div class="span3"><input type="text" name="global[pay__note][]" value="金额"></div>
                                    <div class="span1"><i class="icon-plus"></i></div>
                                    <div class="span1"><i class="icon-remove"></i></div>
                                </div>
                                <div class="thumbnails">
                                    <div class="span3"><input type="text" name="global[pay__key][]" value="orderid" ></div>
                                    <div class="span3"><input type="text" name="global[pay__value][]" value=":$order_id"></div>
                                    <div class="span3"><input type="text" name="global[pay__note][]" value="商户订单号"></div>
                                    <div class="span1"><i class="icon-plus"></i></div>
                                    <div class="span1"><i class="icon-remove"></i></div>
                                </div>
                                <div class="thumbnails">
                                    <div class="span3"><input type="text" name="global[pay__key][]" value="notifyurl" ></div>
                                    <div class="span3"><input type="text" name="global[pay__value][]" value=":$notify_url"></div>
                                    <div class="span3"><input type="text" name="global[pay__note][]" value="下行异步通知地址"></div>
                                    <div class="span1"><i class="icon-plus"></i></div>
                                    <div class="span1"><i class="icon-remove"></i></div>
                                </div>
                                <div class="thumbnails">
                                    <div class="span3"><input type="text" name="global[pay__key][]" value="callbackurl" ></div>
                                    <div class="span3"><input type="text" name="global[pay__value][]" value=":$return_url"></div>
                                    <div class="span3"><input type="text" name="global[pay__note][]" value="下行同步通知地址"></div>
                                    <div class="span1"><i class="icon-plus"></i></div>
                                    <div class="span1"><i class="icon-remove"></i></div>
                                </div>
                                <div class="thumbnails">
                                    <div class="span3"><input type="text" name="global[pay__key][]" value="clientip" ></div>
                                    <div class="span3"><input type="text" name="global[pay__value][]" value=":get_client_ip()"></div>
                                    <div class="span3"><input type="text" name="global[pay__note][]" value="支付用户IP"></div>
                                    <div class="span1"><i class="icon-plus"></i></div>
                                    <div class="span1"><i class="icon-remove"></i></div>
                                </div>
                                <div class="thumbnails">
                                    <div class="span3"><input type="text" name="global[pay__key][]" value="desc" ></div>
                                    <div class="span3"><input type="text" name="global[pay__value][]" value="1"></div>
                                    <div class="span3"><input type="text" name="global[pay__note][]" value="备注消息"></div>
                                    <div class="span1"><i class="icon-plus"></i></div>
                                    <div class="span1"><i class="icon-remove"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="cust cust-query thumbnails thumbnail">
                        <div class="title">查询请求参数:</div>
                        <div class="head thumbnails">
                            <div class="span3">参数名</div>
                            <div class="span3">参数值</div>
                            <div class="span3">注 释</div>
                            <div class="span3">操 作</div>
                        </div>

                        <div class="thumbnails">
                            <div class="span3"><input type="text" name="global[query_name][]" value="merchantcode"></div>
                            <div class="span3"><input type="text" name="global[query_value][]" value=":$app_id"></div>
                            <div class="span3"><input type="text" name="global[query_note][]" value="商户号"></div>
                            <div class="span1"><i class="icon-plus"></i></div>
                            <div class="span1"><i class="icon-remove"></i></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"><input type="text" name="global[query_name][]" value="orderid"></div>
                            <div class="span3"><input type="text" name="global[query_value][]" value=":$orderId"></div>
                            <div class="span3"><input type="text" name="global[query_note][]" value="商户订单号"></div>
                            <div class="span1"><i class="icon-plus"></i></div>
                            <div class="span1"><i class="icon-remove"></i></div>
                        </div>
                    </div>

                    <hr>

                    <div class="cust cust-type thumbnails thumbnail">
                        <div class="title">支付方式参数:</div>

                        <table class="table table-striped table-bordered table-condensed" style="">
                            <tr>
                                <th>支付类型</th>
                                <th class="many-request-show">支付url类型</th>
                                <th>代码</th>
                                <th>方法</th>
                                <th>注释</th>
                            </tr>
							<?php foreach($configArr['pay_type'] as $pay_key => $pay): ?>
                                <tr>
                                    <input type="hidden" name="global[type_name][]" value="<?= $pay_key ?>" >
                                    <td> <?= $pay['desc'] ?> (<span style="color:indianred"><?= $pay_key ?></span>)</td>
                                    <td class="many-request-show" style="text-align: center;"> <input type="text" name="global[type_url][]" style="width:50px;" value=""></td>
                                    <td> <input type="text" name="global[type_value][]" value=""></td>
                                    <td> <input type="text" name="global[type_action][]" value="<?= $pay['action'] ?>"></td>
                                    <td> <input type="text" name="global[type_note][]" value="<?= $pay['desc'] ?>"></td>
                                </tr>
							<?php endforeach; ?>
                        </table>
                    </div>


    <!--                是否支持银行-->
                    <div id="bank">
                        <hr>

                        <div class="cust cust-bank thumbnails thumbnail">
                            <div class="title">银行参数:</div>

                            <table class="table table-striped table-bordered table-condensed" style="width:35%">
                                <tr>
                                    <th>银行名</th>
                                    <th>代码</th>
                                </tr>
								<?php foreach($configArr['bank_type'] as $bank): ?>
                                    <tr>
                                        <input type="hidden" name="global[bank_key][]" value="<?= $bank[0] ?>">
                                        <input type="hidden" name="global[bank_note][]" value="<?= $bank[1] ?>">
                                        <td><?= $bank[1] ?></td>
                                        <td><input type="text" name="global[bank_value][]" value=""></td>
                                    </tr>
								<?php endforeach; ?>
                            </table>
                        </div>
                    </div>


                    <hr>

                    <div class="cust cust-bank thumbnails thumbnail">
                        <div class="title">sign 加密函数(md5):</div>
                        <div class="span12"><textarea class="sign-function" name="sign[function]" rows="3" >function create_sign($data, $sign, $sign_array = []){
    //前排序
    //ksort($data);
    if($sign_array){
		$data += $sign_array;
	}
    //后排序
    //ksort($data);

    $sign_str = '';
    foreach ($data as $key => $val){
//		if($val === ''){
//			continue;
//		}
        $sign_str .= $key . '=' . $val . '&';
    }

//  strtolower小写
    return strtoupper(md5(rtrim($sign_str, '&')));
}
                        </textarea></div>
                    </div>

                    <hr>

                    <div class="cust cust-res thumbnails thumbnail">
                        <div class="title">杂项参数:</div>
                        <div class="head thumbnails">
                            <div class="span3">参数名</div>
                            <div class="span3">参数值</div>
                            <div class="span3">注 释</div>
                            <div class="span3">操 作</div>
                        </div>

                        <div class="thumbnails">
                            <div class="span3"></div>
                            <div class="span3"><input type="text" name="global[sign_str_key]" value="key" placeholder=""></div>
                            <div class="span3"><input type="text" name="" value=" sign 的 sign字符串 字段名"></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"></div>
                            <div class="span3"><input type="text" name="global[sign_url_key]" value="sign" placeholder=""></div>
                            <div class="span3"><input type="text" name="" value=" sign 的 请求 字段名"></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"></div>
                            <div class="span3"><input type="text" name="global[rq_url_value]" value=":['url']" placeholder=""></div>
                            <div class="span3"><input type="text" name="" value="RQ返回url键值"></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"></div>
                            <div class="span3"><input type="text" name="global[return_order_value]" value="['orderid']" placeholder=""></div>
                            <div class="span3"><input type="text" name="" value="回调返回订单key"></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"></div>
                            <div class="span3"><input type="text" name="global[return_succeed_value]" value="SUCCEED" placeholder=""></div>
                            <div class="span3"><input type="text" name="" value="回调成功返回值"></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"></div>
                            <div class="span3"><input type="text" name="global[return_error_value]" value="ERROR" placeholder=""></div>
                            <div class="span3"><input type="text" name="" value="回调失败返回值"></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"></div>
                            <div class="span3">
                                <select name="global[return_data_is_json]" id="">
                                    <option value="0">否</option>
                                    <option value="1">是</option>
                                </select>
                            </div>
                            <div class="span3"><input type="text" name="" value="回调接收数据是否为json"></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"><input type="text" name="global[rq_succeed_key]" value=":['code']"></div>
                            <div class="span3"><input type="text" name="global[rq_succeed_value]" value="1" placeholder=""></div>
                            <div class="span3"><input type="text" name="" value="RQ返回成功值"></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"><input type="text" name="global[qu_succeed_key]" value="['status']"></div>
                            <div class="span3"><input type="text" name="global[qu_succeed_value]" value="0000" placeholder=""></div>
                            <div class="span3"><input type="text" name="" value="查询返回成功值"></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"><input type="text" name="global[re_succeed_key]" value="['status']"></div>
                            <div class="span3"><input type="text" name="global[re_succeed_value]" value="0000" placeholder=""></div>
                            <div class="span3"><input type="text" name="" value="回调返回成功值"></div>
                        </div>
                        <div>回调加密需要的字段</div>
                        <div class="thumbnails">
                            <div class="span3"><input type="text" name="global[return_name][]" value="merchantcode"></div>
                            <div class="span3"><input type="text" name="global[return_value][]" value=":$response['merchantcode']" placeholder=""></div>
                            <div class="span3"><input type="text" name="global[return_note][]" value="商户号"></div>
                            <div class="span1"><i class="icon-plus"></i></div>
                            <div class="span1"><i class="icon-remove"></i></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"><input type="text" name="global[return_name][]" value="orderid"></div>
                            <div class="span3"><input type="text" name="global[return_value][]" value=":$order_id" placeholder=""></div>
                            <div class="span3"><input type="text" name="global[return_note][]" value="订单号"></div>
                            <div class="span1"><i class="icon-plus"></i></div>
                            <div class="span1"><i class="icon-remove"></i></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"><input type="text" name="global[return_name][]" value="status"></div>
                            <div class="span3"><input type="text" name="global[return_value][]" value=":$response['status']" placeholder=""></div>
                            <div class="span3"><input type="text" name="global[return_note][]" value="状态"></div>
                            <div class="span1"><i class="icon-plus"></i></div>
                            <div class="span1"><i class="icon-remove"></i></div>
                        </div>
                        <div class="thumbnails">
                            <div class="span3"><input type="text" name="global[return_name][]" value="amount"></div>
                            <div class="span3"><input type="text" name="global[return_value][]" value=":$response['amount']" placeholder=""></div>
                            <div class="span3"><input type="text" name="global[return_note][]" value="金额"></div>
                            <div class="span1"><i class="icon-plus"></i></div>
                            <div class="span1"><i class="icon-remove"></i></div>
                        </div>
                    </div>


                    <hr>

                    <div class="control-group">
                        <div class="controls">
                            <input type="hidden" name="act" value="generation">
                            <button type="button" class="btn" onclick="generation()">生成文件</button>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="control-group check-name-button">
                    <div class="controls">
                        <button type="button" class="btn" onclick="verify_file_name()">确定</button>
                    </div>
                </div>
            </form>

            <footer>
                <hr>
                <p class="pull-right">
                    <a href="javascript:;">
						<?php echo $appSet[ 'app_name'];?>
                    </a>
                </p>
                <p>&copy;
					<?php echo $appSet[ 'company_year'];?>
                    <a href="<?php echo $appSet['company_url'];?>" title="<?php echo $appSet['company'];?>" target="_blank">
						<?php echo $appSet[ 'company'];?>
                    </a>
                </p>
            </footer>
        </div>
    </div>
</div>

<!-- page end -->
<script type="text/javascript">
    function verify_file_name(){
        var name = $('input[name=api_name]').val();
        if (name == '') {
            alert('支付接口商名称不能为空');
            return false;
        }

        $.post('ajaxGeneration.php', {api_name: name, act: 'check_name'}, function(response){
            if (response.err !== 0) {
                alert(response.msg);
            }else{
                $('input[name=api_name]').attr({readonly: 'true'});
                $('input[name=api_dir_path]').val(response.api_pinyin);
                //隐藏确定按钮
                $('.check-name-button').hide();

                $('#notify_url').val(":$local_url . '/api/" + response.api_pinyin + "/callback.php'");


                //显示配置
                $('.is_hidden').show();
            }
        }, "json");
    }

    //
    function generation(){
        $.post('ajaxGeneration.php', $('#api-form').serialize(), function(response){
            alert('ok');
        });
    }

    $(function(){
        //打开菜单
        $("#tool-menu").addClass('in');

        var add_index = 0;
        //参数增加删除按钮
        $('.icon-plus').click(function(){
            add_index ++;
            var parentDiv = $(this).parent().parent();

            //开启了多请求地址
            if ($(':input[name=is_many_request]').prop('checked')) {
                //   判断是否增加多请求
                if (parentDiv.find('div > input[name*=many_request_key]').length > 0) {
                    //添加支付请求参数 复制所有元素
                    var manyDiv = $('#many-request div:first').clone(true);
                    manyDiv.attr('data-index', add_index);
                    $('#many-request').append(manyDiv);
                }
            }

            var newDiv =  parentDiv.clone(true);
            newDiv.attr('data-index', add_index);
            parentDiv.after(newDiv);
        })
        $('.icon-remove').click(function(){
            var parentDiv = $(this).parent().parent();

            parentDiv.remove();
        });

        //银行
        if (!$(':input[name=is_bank]').prop('checked')) {
            $('#bank').hide();
        }
        $(':input[name=is_bank]').click(function(){
            if ($(this).prop('checked')) {
                $('#bank').show();
            }else{
                $('#bank').hide();
            }
        });



        //多个请求地址
        show_request()
        function show_request(){
            if ($(':input[name=is_many_request]').prop('checked')) {
                $('.many-request-show').show();
                $('.sign-request-show').hide();
            }else{
                $('.many-request-show').hide();
                $('.sign-request-show').show();
            }
        }
        $('input[name=is_many_request]').click(function(){
            show_request()
        })

        //多请求地址修改参数名
        $(':input[name*=many_request_key]').keyup(function(){
            var request_name = $(this).val(),
                index = $(this).parent().parent().attr('data-index');

            //对应的div
            var requestDiv = $('#many-request').find('div[data-index=' + index + ']');
            //更改支付参数标题
            requestDiv.find('.request-name').html(request_name);
            //更改所有input name
            requestDiv.find('input').each(function(){
                var oldNameStr = $(this).attr('name');
                //替换 第一个[ 到 第一个] 里面的值 为 key value
                var oldStr = oldNameStr.slice(oldNameStr.indexOf('[') + 1, oldNameStr.indexOf(']'));
                var newStrArr = oldStr.split('_');
                newStrArr[1] = request_name;
                var newStr = newStrArr.join('_');

                var newNameStr = oldNameStr.replace(oldStr, newStr);
                $(this).attr('name', newNameStr);
            });
        })

    })
</script>
<?php include 'foot.php';?>
