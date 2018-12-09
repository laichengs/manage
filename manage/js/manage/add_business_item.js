$(function(){
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

	/*增加项目数据*/
	$('input[type=submit]').click(function(){
		var data = {
			'describe': $('input[name=describe]').val(),
			'address': $('input[name=address]').val(),
			'tag': $('input[name=tag]').val(),
			'latitude': $('input[name=latitude]').val(),
			'longitude': $('input[name=longitude]').val(),
			'phone': $('input[name=phone]').val(),
			'img': $('input[name=thumb]').val(),
			'business': $('select[name=business]').val(),
			'recommend': $('input[name=recommend]:checked').val()
		};
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/business_item',
			type: 'post',
			data:{
				params: data
			},
			success: function(res){		
				console.log(res);		
				if(res){
					layer.alert('新增成功!');
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