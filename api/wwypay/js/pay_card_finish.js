// JavaScript Document
$(function(){
	//pay_card_search();		   
});
var i = 10;		//�ӳٵ�ʱ��
setTimeout("pay_card_search()", i);
function pay_card_search(){
alert("111");
	var order_id = $("#order_id").val();
	$.ajax({
		url:"ajax/pay_card_search.php",	 
		data: "order_id="+order_id,
		dataType: "json",
		type: "post",
		success: function(data){
			//������û�б��������һ��ʱ��󣬼�������, ���Ѿ���������ʾ������
			if(data.success == '1'){
				$("#ekamsg").html(data.message);
			}//else{
				//setTimeout("pay_card_search()", i);
			//}	
		}
	});
}
