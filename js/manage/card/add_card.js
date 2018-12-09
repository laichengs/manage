$(function(){
	/*检测是否有数据*/
	function checkData(){
		if(!$('input[name=name]').val()){
			layer.msg('请输入卡券名称');
			return false;
		}
		if(!$('input[name=expire]').val()){
			layer.msg('请输入有效期');
			return false;
		}	
		if(!$('input[name=miniamount]').val()){
			layer.msg('请输入起用金额');
			return false;
		}	
		if(!$('input[name=value]').val()){
			layer.msg('请输入卡券面额');
			return false;
		}				
		return true;
	}
	/*获取栏目数据*/
	$.ajax({
		url: baseUrl + '/api/manage/items',
		type: 'get',
		success: function(res){
			for(var i=0; i<res.length; i++){
				$('select[name=item]').append('<option value="'+res[i].id+'">'+res[i].name+'</option>');
			}
		}
	});

	/*增加数据*/
	$('input[type=submit]').click(function(){
		if(!checkData()){
			return false;
		}
		var data = {
			'name': $('input[name=name]').val(),
			'item_id': $('select[name=item]').val(),
			'circle': $('input[name=expire]').val(),
			'minimum_amount': $('input[name=miniamount]').val(),
			'value': $('input[name=value]').val(),
			'id': $.cookie('card_id')
		};
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/card_item/add',
			type: 'post',
			data:{
				params: data
			},
			success: function(res){				
				if(res){
					layer.alert('修改成功!');
					location.href="card.html";
				}
			}
		});
	});
})
