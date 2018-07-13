<?php
/* *
 * 功能：一般支付调试入口页面
 * 版本：1.0
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码。
 */
 	require_once("lib/pay.Config.php");
	$time		= time();
	$orderNo	= date("YmdHis",$time);
	$tradeDate	= date("Ymd",$time);
	
	 
	 
	 
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>商户支付接口示例 - 委托结算</title>
	
    <link rel="stylesheet" href="assets/css/style.css" />
   




</head>
<body >
<div class="container">
    <div class="header">
        <h3>支付接口 - 单笔委托结算示例：</h3>
    </div>
    <div class="main">
        <form method="post" action="doSingleSett.php">
            <ul>
                <li>
                    <label>订单号</label>
                    <input type="text" name="tradeNo" value='<?php echo $orderNo; ?>'/>
                </li>
                <li>
                    <label>交易日期</label>
                    <input type="text" name="tradeDate" value='<?php echo $tradeDate; ?>'/>
                </li>
                <li>
                    <label>结算通知地址</label>
                    <input type="text" name="notifyUrl" />
                </li>
                <li>
                    <label>商户参数</label>
                    <input type="text" name="extra" />
                </li>
                <li>
                    <label>交易摘要</label>
                    <input type="text" name="summary" value="委托结算测试" />
                </li>
                <li>
                    <label>银行卡卡号</label>
                    <input type="text" name="bankCardNo" />
                </li>
                <li>
                    <label>开户姓名</label>
                    <input type="text" name="bankCardName" />
                </li>
				
				
				<li>
                    <label>银行卡开户行名称</label>
                   
					<select id="selectName" onchange="name_onChange()" type="text" name="bankName"  style="width: 300px; padding: 5px;margin-top:5px; ">
                        <option value='' >--请选择银行--</option>
                    </select>
					
					
                </li>
				
                <li>
                    <label>银行卡银行代码</label>
                    <select id="selectId" onchange="id_onChange()"  type="text" name="bankId"  style="width: 300px; padding: 5px;margin-top:5px;">
                       <option value='' >--请选择银行代号--</option>
                    </select>
					
					
                </li>
                
				
				
                <li>
                    <label>结算金额</label>
                    <input type="text" name="amount" value="10" />
                </li>
                <li>
                    <label>用途</label>
                    <select name="purpose" style="width: 300px; padding: 5px;margin-top:5px;">
                        <option value="1">还款</option>
                        <option value="2">借款</option>
                        <option value="6">往来款</option>
                        <option value="10" selected="true">其它合法款项</option>
                    </select>
                </li>
                <li style="margin-top: 50px">
                    <label></label>
                    <button type="submit">委托结算</button>
                </li>
            </ul>
        </form>
    </div>

</div>
     <script type="text/javascript" src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
     <script type="text/javascript">
	 $(function () {
    //初始化省份菜单
     var name = new Array();
      name[1] = "中国农业银行";
      name[2] = "中国银行";
      name[3] = "渤海银行";
      name[4] = "中国建设银行";
	  name[5] = "光大银行";
	  name[6] = "兴业银行";
	  name[7] = "招商银行";
	  name[8] = "民生银行";
	  name[9] = "中信银行";
	  name[10] = "交通银行";
	  name[11] = "广发银行";
	  name[12] = "华夏银行";
	  name[13] = "中国工商银行";
	  name[14] = "平安银行";
	  name[15] = "中国邮政储蓄银行";
	  name[16] = "浦发银行";
	  
	  
  
    for (var i = 1; i < name.length; i++) {
      $("#selectName").append("<option>"+name[i]+"</option>");
    }
     var id = new Array();
     id[1] = new Array("ABC");
     id[2] = new Array("BOC");
     id[3] = new Array("CBHB");
     id[4] = new Array("CCB");
	 id[5] = new Array("CEB");
	 id[6] = new Array("CIB");
	 id[7] = new Array("CMB");
	 id[8] = new Array("CMBC");
	 id[9] = new Array("CNCB");
	 id[10] = new Array("COMM");
	 id[11] = new Array("GDB");
	 id[12] = new Array("HXB");
	 id[13] = new Array("ICBC");
	 id[14] = new Array("PAB");
	 id[15] = new Array("PSBC");
	 id[16] = new Array("SPDB");
  
  $("#selectName").on("change",function(){
      $("#selectId").children("option").detach();
      //$("#selectId").append("<option>请选择银行代号</option>");
      var indexName = $("#selectName>option:selected").index();//取得选中的想的数组下标0
      if (indexName > 0) {
        for (var i = 0; i < id[indexName].length; i++) {
          $("#selectId").append("<option>" + id[indexName][i] + "</option>");
      }
       //console.log( $("#selectName>option:selected").val() + $("#selectId>option:first").val() );
     }else {
       //console.log( "请选择省份" );
     }
   });
   $("#selectId").on("change",function(){
      //console.log( $("#selectName>option:selected").val() + $("#selectId>option:selected").val() );
   });
 });
 
	  
	  
	
  </script>



</body>
</html>
