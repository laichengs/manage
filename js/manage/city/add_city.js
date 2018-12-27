$(function(){
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

	/*增加数据*/
	$('input[type=submit]').click(function(){
		if(!checkData()){
			return false;
		}
		var data = {
			'title': $('input[name=title]').val(),
			'item_id': $.cookie('item_Id'),
			'img': $('input[name=thumb]').val(),
			'unit': $('input[name=unit]').val(),
			'price': $('input[name=price]').val(),
		};
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/city',
			type: 'post',
			data:{
				params: data
			},
			success: function(res){				
				if(res){
					layer.alert('新增成功!');
					location.href="city.html";
				}
			}
		});
	});
})
