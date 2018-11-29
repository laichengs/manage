$(function(){
	/*检测是否有数据*/
	function checkData(){
		if(!$('input[name=main]').val()){
			layer.msg('请选择显示图片');
			return false;
		}				
		return true;
	}
	/*获取所属位置*/
	$.ajax({
		url: baseUrl + '/api/manage/get_banners',
		type: 'post',
		success: function(res){
			for(var i = 0; i<res.length; i++){
				$('#location').append('<option value="'+res[i].id+'">'+res[i].describe_name +'</option>');				
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
				$('#items').append('<option value="'+res.rows[i].id+'">'+res.rows[i].name+'</option>');
			}
		}
	});

		/*增加项目数据*/
	$('input[type=submit]').click(function(){
		if(!checkData()){
			return false;
		}
		var data = {
			'item_id': $('#items').val(),
			'banner_id': $('#location').val(),
			'img': $('input[name=main]').val()
		};
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/add_banner',
			type: 'post',
			data:{
				params: data
			},
			success: function(res){
				console.log(res);				
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