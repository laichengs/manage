$(function(){
	var id,theme_id,item_id,combo_id, target;
	if($.cookie('theme_id')){
		id = $.cookie('theme_id');
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
		url: baseUrl + '/api/manage/get_theme_item',
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
			theme_id = res.theme_id;
			combo_id = res.combo_id;
			target = res.type;
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

	/*获取套餐数据*/
	$.ajax({
		url: baseUrl + '/api/manage/combo_manage',
		type: 'post',
		data: {
			start: 0,
			number: 100,
		},
		success: function(res){
			for(var i=0; i<res.rows.length; i++){
				if(res.rows[i].id == combo_id){
					$('select[name=combo]').append('<option value="'+res.rows[i].id+'" selected>'+res.rows[i].title+'</option>');
				}else{
					$('select[name=combo]').append('<option value="'+res.rows[i].id+'">'+res.rows[i].title+'</option>');
				}
			}
		}
	});

	/*获取主题分类*/
	$.ajax({
		url: baseUrl + '/api/manage/get_theme_category',
		type: 'post',
		success: function(res){
			console.log(res);
			for(var i=0; i<res.length; i++){
				if(res[i].id == theme_id){
					$('select[name=themes]').append('<option value="'+res[i].id+'" selected>'+res[i].name+'</option>');
				}else{
					$('select[name=themes]').append('<option value="'+res[i].id+'">'+res[i].name+'</option>');					
				}

			}
		}
	});

	/*获取跳转栏目*/
	var tarStr = '';
	tarStr += '<label class="radio-inline">';
	console.log(target);
	if(target == '1'){
		tarStr += '<input type="radio" name="target" value="1" checked/>栏目';
	}else{
		tarStr += '<input type="radio" name="target" value="1" />栏目';
	}	
	tarStr += '</label>';
	tarStr += '<label class="radio-inline">';
	if(target == '2'){
		tarStr += '<input type="radio" name="target" value="2" checked/>套餐';
	}else{
		tarStr += '<input type="radio" name="target" value="2"/>套餐';
	}	
	tarStr += '</label>';
	$('div#targets').append(tarStr);

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
			'type': $('input[type=radio]:checked').val(),
			'item_id': $('select[name=item]').val(),
			'order': $('input[name=order]').val(),
			'combo_id': $('select[name=combo]').val(),
			'theme_id': $('select[name=themes]').val(),
			'id': $.cookie('theme_id')
		};
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/update_theme_item',
			type: 'post',
			data:{
				params: data
			},
			success: function(res){				
				if(res){
					layer.alert('修改成功!');
					location.href="theme.html";
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