$(function(){
	var id,infos;
	var info_ids = [];
	if($.cookie('city_id')){
		id = $.cookie('city_id');
	}

	/*获取当前城市详细数据*/
	$.ajax({
		url: baseUrl + '/api/manage/city/' + id,
		type: 'get',
		async: false,
		success: function(res){
			/*数据写入*/
			console.log(res.items_info);
			$('input[name=name]').val(res.name);
			infos = res.items_info;
			console.log(infos);
			for(let i = 0; i<infos.length; i++){
				console.log(infos[i].id);
				info_ids.push(infos[i].id);
			}
		}
	});

	/*获取所有项目并复选*/
	$.ajax({
		url: baseUrl + '/api/manage/item',
		type: 'post',
		async: false,
		success: (res)=>{
			let items = res.rows;
			console.log(items);
			console.log(info_ids);
			for(let i = 0; i< items.length; i++){
				if($.inArray(items[i].id, info_ids) != -1){
					$str = `
					<label for="a${items[i].id}">
						<input type="checkbox" id="a${items[i].id}" checked value="${items[i].id}">${items[i].name}
					</label>
					`;
				}else{
					$str = `
					<label for="a${items[i].id}">
						<input type="checkbox" id="a${items[i].id}"  value="${items[i].id}">${items[i].name}
					</label>
					`;
				}

				$('#checkbox-box').append($str);
			}
		}
	})

	/*修改子属性数据*/
	$('input[type=submit]').click(function(){
		let tem = '';
		$('#checkbox-box :checked').each(function(index ,value){
			tem += $(this).val() + ',';
		})
		var data = {
			'items': tem.substr(0, tem.length-1),
			'name': $('input[name=name]').val(),
			'id': id
		};
		$.ajax({
			url: baseUrl + '/api/manage/city',
			type: 'put',
			data:{
				params: data
			},
			success: function(res){			
				if(res){
					layer.alert('修改成功!');
					location.href="city.html";
				}
			}
		});
	});
})
