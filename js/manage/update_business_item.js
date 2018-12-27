	$(function(){
	var id,theme_id,item_id,combo_id, target;
	if($.cookie('business_item_id')){
		id = $.cookie('business_item_id');
	}
	/*检测是否有数据*/
	function checkData(){
		if(!$('input[name=name]').val()){
			layer.msg('请输入主题名称');
			return false;
		}
		if(!$('input[name=thumb]').val()){
			layer.msg('请输入主题价格');
			return false;
		}	
		if(!$('input[name=order]').val()){
			layer.msg('请选择缩略图');
			return false;
		}				
		return true;
	}

	/*获取套餐栏目数据*/
	$.ajax({
		url: baseUrl + '/api/manage/one_business_item?id=' + id,
		type: 'get',
		async: false,
		success: function(res){
			/*数据写入*/
			console.log(res);
			$('input[name=address]').val(res.address);
			$('input[name=tag]').val(res.tag);
			$('input[name=latitude]').val(res.latitude);
			$('input[name=longitude]').val(res.longitude);
			$('input[name=phone]').val(res.phone);
			$('input[name=describe]').val(res.describe);
			if(res.img){
				$('input[name=thumb]').val(res.img.url.substring(res.img.url.indexOf('/image') + 6));
				$('img.thumb').attr({
					'src': res.img.url,
					'alt': res.img_id
				});
			};
		}
	});

	/*获取栏目数据*/
	$.ajax({
		url: baseUrl + '/api/manage/city_info',
		type: 'get',
		success: function(res){
			for(var i=0; i<res.length; i++){
				$('select[name=city]').append('<option value="'+res[i].id+'">'+res[i].name+'</option>');
			}
		}
	});
	/*获取商家数据*/
	$.ajax({
		url: baseUrl + '/api/manage/business',
		type: 'get',
		success: function(res){
			console.log(res);
			if(res.rows){
				for(var i=0; i<res.rows.length; i++){
					$('select[name=business]').append('<option value="'+res.rows[i].id+'">'+res.rows[i].name+'</option>');
				}			
			}

		}
	});


	/*修改套餐数据*/
	$('input[type=submit]').click(function(){
		// if(!checkData()){
		// 	return false;
		// }
		var data = {
			'describe': $('input[name=describe]').val(),
			'address': $('input[name=address]').val(),
			'tag': $('input[name=tag]').val(),
			'latitude': $('input[name=latitude]').val(),
			'longitude': $('input[name=longitude]').val(),
			'phone': $('input[name=phone]').val(),
			'img': {
				url: $('input[name=thumb]').val(),
				id: $('img.thumb').attr('alt')
			},
			'city': $('select[name=city]').val(),
			'recommend': $('input[name=recommend]:checked').val(),
			'business': $('select[name=business]').val(),
			'id': $.cookie('business_id')
		};
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/business_item',
			type: 'put',
			data:{
				params: data
			},
			success: function(res){			
			console.log(res);	
				if(res){
					layer.alert('修改成功!');
					location.href="business.html";
				}
			}
		});
	});
})
var grp;
var modal;
function changeImg(that){
	grp = $(that).parent();
	var str = '<form id="form" enctype="multipart/form-data" method="post">';
	str += '<input type="file" name="image" id="file">';
	str += '</form>';
	modal = layer.open({
		title: '文件上传',
		content: str,
		btn: false
	});
}

$(document).on('change', '#file', function(){
	var form = document.getElementById('form');
	var formData = new FormData(form);
	$.ajax({
		url: baseUrl + '/api/manage/upload',
		type: 'post',
		cache: false,
		data: formData,
		processData:false, 
			contentType:false, 
			success: function(res){
				console.log(res);
				layer.close(modal);
				grp.find('input').val('/' + res);
				grp.find('img').attr('src', baseUrl + '/image/' + res);
				$('#form').remove();
			}
	});
});