$(function(){
	var id,theme_id,item_id,combo_id, target;
	if($.cookie('business_id')){
		id = $.cookie('business_id');
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
		url: baseUrl + '/api/manage/one_business',
		type: 'post',
		async: false,
		data: {
			id: id
		},
		success: function(res){
			/*数据写入*/
			console.log(res);
			$('input[name=name]').val(res.name);
			if(res.img){
				$('input[name=thumb]').val(res.img.url.substring(res.img.url.indexOf('/image') + 6));
				$('img.thumb').attr({
					'src': res.img.url,
					'alt': res.thumb_img_id
				});
			};
			$('input[name=order]').val(res.order)
		}
	});

	/*获取栏目数据*/
	$.ajax({
		url: baseUrl + '/api/manage/city',
		type: 'get',
		success: function(res){
			console.log(res);
			for(var i=0; i<res.length; i++){
				$('select[name=city]').append('<option value="'+res[i].id+'">'+res[i].name+'</option>');
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
			'order': $('input[name=order]').val(),
			'img': {
				url: $('input[name=thumb]').val(),
				id: $('img.thumb').attr('alt')
			},
			'city': $('select[name=city]').val(),
			'id': $.cookie('business_id')
		};
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/update_business',
			type: 'post',
			data:{
				params: data
			},
			success: function(res){				
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