<?php
include 'global.php';

if(isset($_GET['action'])){
    if($_GET['action'] == "add"){
      	error_reporting(E_ALL);
      	ini_set('display_errors',true);
        $data['orderid'] = strtoupper(uniqid('QSF'));
        $data['pay_api'] = '轻松付';
        $data['money'] = $_POST['money'];
        $data['bankname'] = $_POST['bankname'];
        $data['bank_account'] = $_POST['bank_account'];
        $data['account_name'] = $_POST['account_name'];
        $data['pay_ip'] = get_client_ip();
        $data['api_name'] = 'qspay';
        $data['submit_date'] = date('Y-m-d H:i:s');
        $data['status'] = 2;//2 //提交等待处理,1处理成功,0下发失败 
        $file = dirname(__FILE__).'/../api/'.$data['api_name'].'/daifu.php';
      	include $file;
        $daifu  = new daifu();
        $offlineNotifyUrl = 'https://pay1.zf590.com/api/'.$data['api_name'].'/daifu_notify.php';
        $bankLinked = $_POST['lhh'];
        $res = $daifu->dopost($data['orderid'],$data['money'],$data['bankname'],$data['bank_account'],$data['account_name'],$_POST['openBankName'],$offlineNotifyUrl,$bankLinked);
        $data['submit_data'] = $res['data'];
        $ret = json_decode($res['res'],true);
      	if($ret['code'] == '520000' && $ret['message'] == 'success'){
        	if($database->insert(DB_PREFIX.'xiafa', $data)){
                echo json_encode(array('stat' => 0));
            }else{
                echo json_encode(array('stat' => 1,'message'=>'订单提交成功,但写入数据库失败!'));
            }
        }else{
          	echo json_encode(array('stat' => 1,'message'=>$ret['message']));
        }
        exit();
    }
}

?>

<?php include 'base.php';?>

<!-- page start -->
<div class="content">
    <div class="header">        
        <h1 class="page-title">代付管理</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.php">主页</a>  <span class="divider">/</span>
        </li>
        <li class="active">代付管理</li>
    </ul>
    <div class="container-fluid">
        <div class="row-fluid">
              	
			
			<form class="form-horizontal" onsubmit="return false;">
			  <div class="control-group">
				<label class="control-label"  for="sys_user">第三方</label>
				<div class="controls">
				  <!-- <input type="text" id="sys_user" value=""> -->
				  <select name="pay_api" id="pay_api">
				  	<option value="qspay|轻松付">轻松付</option>
				  </select>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="money">代付金额<font color="red">*</font></label>
				<div class="controls">
				  <input type="text" id="money" value="">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="bankname">银行名称<font color="red">*</font></label>
				<div class="controls">
				  <input type="text" id="bankname" value="">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="bank_account">银行卡号<font color="red">*</font></label>
				<div class="controls">
				  <input type="text" id="bank_account" value="">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="lhh">联行号<font color="red">*</font></label>
				<div class="controls">
				  <input type="text" id="lhh" value="">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="account_name">户名<font color="red">*</font></label>
				<div class="controls">
				  <input type="text" id="account_name" value="">
				</div>
			  </div>
			  
			  <div class="control-group">
				<label class="control-label"  for="openBankName">开户行全称<font color="red">*</font></label>
				<div class="controls">
				  <input type="text" id="openBankName" value="">
				</div>
			  </div>	
			  <div class="control-group">
				<div class="controls">
				  <button id="btnSave" class="btn btn-success">确定添加</button>
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
$(function(){
	$("#money-menu").addClass('in');
})
</script>

<script type="text/javascript">
	$(function(){
		$("#btnSave").click(function(){
			var _payapi = $("#pay_api").val();
			var _money = $("#money").val();
			if(_money == ""){
				alert("代付金额不能为空");
				return;
			}

			var _bankname = $("#bankname").val();
			if(_bankname == ""){
				alert("银行名称不能为空");
				return;
			}
			
			var _bank_account = $("#bank_account").val();
			if(_bank_account == ''){
				alert("银行卡号不能为空");
				return;
			}
			
			var _lhh = $("#lhh").val();
			if(_lhh == ""){
				alert("联行号不能为空");
				return;
			}

			var _account_name = $("#account_name").val();
			if(_account_name == ""){
				alert("开户姓名不能为空");
				return;
			}

			var _openBankName = $('#openBankName').val();
			if(_openBankName == ''){
				alert("开户行全称不能为空");
				return;
				
			}

			/*var _lhh = $("#lhh").val();
			if(_lhh == ""){
				alert("联行号不能为空");
				return;
			}*/

			$.post("daifu.php?action=add",{pay_api:_payapi,money:_money,bankname:_bankname,bank_account:_bank_account,lhh:_lhh,account_name:_account_name,openBankName:_openBankName},function(obj){
				if(obj.stat == 0){
					alert('提交成功!');
					window.location.href = 'daifu.php';
				}else{
					alert('提交失败!'+obj.message);
				}

			},"json");
			
		})
	})
</script>


<?php include 'foot.php';?>
