$(function(){
	var id,card_combo_id,recharge_id;
	if($.cookie('card_combo_id')){
		id = $.cookie('card_combo_id');
	}
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

	/*获取套餐栏目数据*/
	$.ajax({
		url: baseUrl + '/api/manage/card_combo_item/' + id,
		type: 'get',
		async: false,
		success: function(res){
			/*数据写入*/
			console.log(res);
			$('input[name=name]').val(res.name);
			$('input[name=value]').val(res.value);
			$('input[name=expire]').val(res.circle);
			$('input[name=miniamount]').val(res.minimum_amount);
			if(res.recharge){
				recharge_id = res.recharge_id;
			}			
		}
	});

	/*获取充值列表数据*/
	$.ajax({
		url: baseUrl + '/api/manage/recharge_amount_list',
		type: 'get',
		success: function(res){
			console.log(res);
			for(var i=0; i<res.length; i++){
				if(res[i].id == recharge_id){
					$('select[name=recharge]').append('<option value="'+res[i].id+'" selected>'+res[i].name+'</option>');
				}else{
					$('select[name=recharge]').append('<option value="'+res[i].id+'">'+res[i].name+'</option>');
				}
			}
			$('select[name=recharge]').append('<option value="1">'+'套餐'+'</option>');
		}
	});
	/*获取栏目数据*/
	$.ajax({
		url: baseUrl + '/api/manage/card_list',
		type: 'get',
		data:{
			start: 0,
			number: 1000
		},
		success: function(res){
			console.log(res.rows);
			let arr = res.rows;
			for(var i=0; i<arr.length;i++){
				let name = arr[i].item.name;
				let value = arr[i].value;
				let id = arr[i].id;
				let str = `				<label>
				<input type="checkbox" name="" value="${id}"> ${name}-${value}
			</label>`;
				$('#radio-box').append(str);
			}
			
		}
	});

	/*修改套餐数据*/
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
			'id': $.cookie('card_combo_id')
		};
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/card_combo_item',
			type: 'post',
			data:{
				params: data
			},
			success: function(res){				
				if(res){
					layer.alert('修改成功!');
					location.href="card_combo.html";
				}
			}
		});
	});
})
