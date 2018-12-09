$(function(){
	var id,item_type_id,recharge_id,item_type_items;
	if($.cookie('item_type_id')){
		id = $.cookie('item_type_id');
	}
	/*检测是否有数据*/
	function checkData(){
		if(!$('input[name=title]').val()){
			layer.msg('请输入属性名称');
			return false;
		}
		if(!$('input[name=unit]').val()){
			layer.msg('请输入属性单位');
			return false;
		}	
		if(!$('input[name=price]').val()){
			layer.msg('请输入属性金额');
			return false;
		}				
		return true;
	}

	/*获取当前组合数据*/
	$.ajax({
		url: baseUrl + '/api/manage/item_type_detail/' + id,
		type: 'get',
		async: false,
		success: function(res){
			/*数据写入*/
			$('input[name=title]').val(res.title);
			$('input[name=unit]').val(res.unit);
			$('input[name=price]').val(res.price);
			$('input[name=thumb]').val(res.img.url.substring(res.img.url.indexOf('/image') + 6));
			$('img.thumb').attr({
				'src': res.img.url,
				'alt': res.img_id
			});
		}
	});


	/*修改子属性数据*/
	$('input[type=submit]').click(function(){
		var data = {
			'title': $('input[name=title]').val(),
			'item_id': $.cookie('item_Id'),
			'img': {
				'url': $('input[name=thumb]').val(),
				'id': $('img.thumb').attr('alt')
			},
			'unit': $('input[name=unit]').val(),
			'price': $('input[name=price]').val(),
			'id': $.cookie('item_type_id')
		};
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/item_type',
			type: 'put',
			data:{
				params: data
			},
			success: function(res){				
				if(res){
					layer.alert('修改成功!');
					location.href="item_type.html";
				}
			}
		});
	});
})
