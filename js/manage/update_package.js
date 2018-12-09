$(function(){
	var id,item_id,package_id;
	if($.cookie('package_id')){
		id = $.cookie('package_id');
	}
	/*检测是否有数据*/
	function checkData(){
		if(!$('input[name=title]').val()){
			layer.msg('请输入主题名称');
			return false;
		}
		if(!$('input[name=describe]').val()){
			layer.msg('请输入主题价格');
			return false;
		}	
		if(!$('input[name=thumb]').val()){
			layer.msg('请选择缩略图');
			return false;
		}				
		return true;
	}

	/*获取套餐栏目数据*/
	$.ajax({
		url: baseUrl + '/api/manage/get_package_item',
		type: 'post',
		async: false,
		data: {
			id: id
		},
		success: function(res){
			/*数据写入*/
			console.log(res);
			$('input[name=title]').val(res.title),
			$('input[name=describe]').val(res.describe)
			$('img.thumb').attr({
				'src': res.img.url,
				'alt': res.img_id
			});
			$('input[name=thumb]').val(res.img.url.substring(res.img.url.indexOf('/image') + 6));
			$('input[name=order]').val(res.order);
			item_id = res.item_id;
			package_id = res.package_id;
		}
	});

	/*获取栏目数据*/
	$.ajax({
		url: baseUrl + '/api/manage/item',
		type: 'post',
		data: {
			start: 0,
			number: 100,
		},
		success: function(res){
			for(var i=0; i<res.rows.length; i++){
				if(res.rows[i].id == item_id){
					$('select[name=item]').append('<option value="'+res.rows[i].id+'" selected>'+res.rows[i].name+'</option>');
				}else{
					$('select[name=item]').append('<option value="'+res.rows[i].id+'">'+res.rows[i].name+'</option>');
				}
				
			}
		}
	});



	/*获取主题分类*/
	$.ajax({
		url: baseUrl + '/api/manage/package',
		type: 'post',
		success: function(res){
			console.log(res);
			for(var i=0; i<res.length; i++){
				if(res[i].id == package_id){
					$('select[name=packages]').append('<option value="'+res[i].id+'" selected>'+res[i].title+'</option>');
				}else{
					$('select[name=packages]').append('<option value="'+res[i].id+'">'+res[i].title+'</option>');					
				}

			}
		}
	});



	/*修改套餐数据*/
	$('input[type=submit]').click(function(){
		if(!checkData()){
			return false;
		}
		var data = {
			'title': $('input[name=title]').val(),
			'describe': $('input[name=describe]').val(),
			'img': {
				url: $('input[name=thumb]').val(),
				id: $('img.thumb').attr('alt')
			},
			'item_id': $('select[name=item]').val(),
			'package_id': $('select[name=packages]').val(),
			'id': $.cookie('package_id')
		};
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/update_package_item',
			type: 'post',
			data:{
				params: data
			},
			success: function(res){				
				if(res){
					layer.alert('修改成功!');
					location.href="package.html";
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
