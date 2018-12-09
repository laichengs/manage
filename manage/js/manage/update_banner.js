$(function(){
	var item_id;
	var banner_id;
	/*检测是否有数据*/
	function checkData(){
		if(!$('input[name=main]').val()){
			layer.msg('请选择显示图片');
			return false;
		}				
		return true;
	}
	/*获取banner数据*/
	if($.cookie('banner_id')){
		var id = $.cookie('banner_id');
	}else{
		var id = null;
	}
	$.ajax({
		url: baseUrl + '/api/manage/onebanner',
		type: 'post',
		async: false,
		data: {
			id: id
		},
		success: function(res){
			console.log(res);
			item_id = res.item_id;
			banner_id = res.banner_id;
			$('img.main').attr({
				src: res.img.url,
				alt: res.img_id,
			});
			$('input[name=main]').val(res.img.url.substring(res.img.url.indexOf('/image/') + 6));
		}
	});
	/*获取所属位置*/
	$.ajax({
		url: baseUrl + '/api/manage/get_banners',
		type: 'post',
		success: function(res){
			for(var i = 0; i<res.length; i++){
				if(res[i].id == banner_id){
					$('#location').append('<option value="'+res[i].id+'" selected>'+res[i].describe_name +'</option>');
				}else{
					$('#location').append('<option value="'+res[i].id+'">'+res[i].describe_name +'</option>');					
				}				
			}
		}
	});
	/*获取跳转栏目*/
	$.ajax({
		url: baseUrl + '/api/manage/item',
		type: 'post',
		data: {
			start: 0,
			number: 1000,
		},
		success: function(res){
			console.log(res);
			for(var i = 0; i<res.rows.length; i++){
				if(res.rows[i].id == item_id){
					$('#items').append('<option value="'+res.rows[i].id+'" selected>'+res.rows[i].name+'</option>');
				}else{
					$('#items').append('<option value="'+res.rows[i].id+'">'+res.rows[i].name+'</option>');
				}
				
			}
		}
	});

	/*修改项目数据*/
	$('input[type=submit]').click(function(){
		if(!checkData()){
			return false;
		}
		var data = {
			'item_id': $('#items').val(),
			'banner_id': $('#location').val(),
		};
		data.img = {
			url:  $('input[name=main]').val(),
			id: $('img.main').attr('alt')
		};
		data.id = $.cookie('banner_id');
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/update_banner',
			type: 'post',
			data:{
				params: data
			},
			success: function(res){				
				if(res){
					layer.alert('新增成功!');
					location.href="banner_manage.html";
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