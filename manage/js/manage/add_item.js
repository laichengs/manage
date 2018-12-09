$(function(){
	/*检测是否有数据*/
	function checkData(){
		if(!$('input[name=name]').val()){
			layer.msg('请输入项目名称');
			return false;
		}
		if(!$('input[name=price]').val()){
			layer.msg('请输入项目价格');
			return false;
		}	
		if(!$('input[name=vip_price]').val()){
			layer.msg('请输入会员价格');
			return false;
		}			
		if(!$('input[name=title]').val()){
			layer.msg('请选择首页菜单图');
			return false;
		}	
		if(!$('input[name=main]').val()){
			layer.msg('请选择详情主图');
			return false;
		}
		if(!$('input[name=thumb]').val()){
			layer.msg('请选择首页缩略图');
			return false;
		}				
		return true;
	}

	/*增加项目数据*/
	$('input[type=submit]').click(function(){
		if(!checkData()){
			return false;
		}
		var data = {
			name: $('input[name=name]').val(),
			price: $('input[name=price]').val(),
			vip_price: $('input[name=vip_price]').val(),
			start_price: $('input[name=start_price]').val()
		};
		data.is_show_index = $('input[type=radio]:checked').val();
		var detail = [];
		for(var i=1; i<=4; i++){
			var tem = {};
			tem.order = i;
			tem.url = $('input[name=main-'+i+']').val();
			detail[i-1] = tem;
		}
		data.detail = detail;
		data.title = $('input[name=title]').val();
		data.main = $('input[name=main]').val();
		data.thumb = $('input[name=thumb]').val();
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/add_item',
			type: 'post',
			data:{
				params: data
			},
			success: function(res){
				if(res){
					layer.alert('新增成功!');
					location.href="item.html";
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