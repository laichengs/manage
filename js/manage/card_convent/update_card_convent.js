$(function(){
	var id,card_combo_id,recharge_id,card_combo_items;
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

	/*获取当前组合数据*/
	$.ajax({
		url: baseUrl + '/api/manage/card_combo_item/' + id,
		type: 'get',
		async: false,
		success: function(res){
			/*数据写入*/
			if(res.recharge){
				recharge_id = res.recharge_id;
			}			
			card_combo_items = res.card_combo;
		}
	});

	/*获取充值列表数据*/
	$.ajax({
		url: baseUrl + '/api/manage/recharge_amount_list',
		type: 'get',
		success: function(res){
			for(var i=0; i<res.length; i++){
				if(res[i].id == recharge_id){
					$('select[name=recharge]').append('<option value="'+res[i].id+'" selected>'+res[i].name+'</option>');
				}else{
					$('select[name=recharge]').append('<option value="'+res[i].id+'">'+res[i].name+'</option>');
				}
			}
			// if(res[0].id == 1){
			// 	$('select[name=recharge]').append('<option value="1" selected>'+'套餐'+'</option>');
			// }else{
			// 	$('select[name=recharge]').append('<option value="1">'+'套餐'+'</option>');
			// }
			
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
			let arr = res.rows;
			for(var i=0; i<arr.length;i++){
				let name = arr[i].item.name;
				let value = arr[i].value;
				let id = arr[i].id;
				if(card_combo_items.indexOf(arr[i].id) == -1){
					var str = `				<label>
					<input type="checkbox" name="" value="${id}"> ${name}-${value}
				</label>`;
				}else{
					var str = `				<label>
					<input type="checkbox" name="" value="${id}" checked="checked"> ${name}-${value}
				</label>`;
				}

				$('#radio-box').append(str);
			}
		}
	});

	/*修改套餐数据*/
	$('input[type=submit]').click(function(){
		var checkeds = $('input[type=checkbox]:checked');
		if(checkeds.length == 0){
			layer.msg('必须选择一个代金券');
			return;
		}
		var card_combos = '';
		$.each(checkeds, function(){
			card_combos += $(this).val() + ','
		});
		card_combos = card_combos.substr(0, card_combos.length - 1);
		var data = {
			'card_combo': card_combos,
			'recharge_id': $('select[name=recharge]').val(),
			'id': $.cookie('card_combo_id')
		};
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/card_combo_item',
			type: 'put',
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
