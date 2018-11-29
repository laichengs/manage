$(function(){
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
				$('select[name=item]').append('<option value="'+res.rows[i].id+'">'+res.rows[i].name+'</option>');
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
				$('select[name=combo]').append('<option value="'+res.rows[i].id+'">'+res.rows[i].title+'</option>');
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
				$('select[name=themes]').append('<option value="'+res[i].id+'">'+res[i].name+'</option>');
			}
		}
	});
	/*增加项目数据*/
	$('input[type=submit]').click(function(){
		if(!checkData()){
			return false;
		}
		var data = {
			'title': $('input[name=title]').val(),
			'describe': $('input[name=describe]').val(),
			'img': $('input[name=thumb]').val(),
			'type': $('input[type=radio]:checked').val(),
			'item_id': $('select[name=item]').val(),
			'combo_id': $('select[name=combo]').val(),
			'theme_id': $('select[name=themes]').val(),
		};
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/add_theme_item',
			type: 'post',
			data:{
				params: data
			},
			success: function(res){				
				if(res){
					layer.alert('新增成功!');
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