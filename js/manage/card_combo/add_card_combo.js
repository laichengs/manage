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
					var str = `				<label>
					<input type="checkbox" name="" value="${id}"> ${name}-${value}
				</label>`;
				$('#radio-box').append(str);
			}
		}
	});

	/*增加数据*/
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
			'name': $('input[name=name]').val(),
			'card_combo': card_combos
		};
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/card_combo',
			type: 'post',
			data:{
				params: data
			},
			success: function(res){				
				if(res){
					layer.alert('新增成功!');
					location.href="card_combo.html";
				}
			}
		});
	});
})
