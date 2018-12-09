baseUrl + $(function(){
	var item_id;
	/*检测是否有数据*/
	function checkData(){
		if(!$('input[name=name]').val()){
			layer.msg('请输入套餐名称');
			return false;
		}
		if(!$('input[name=price]').val()){
			layer.msg('请输入套餐价格');
			return false;
		}	
		if(!$('input[name=count]').val()){
			layer.msg('请输入套餐数量');
			return false;
		}	
		if(!$('select[name=item]').val()){
			layer.msg('请选择套餐项目');
			return false;
		}
		if(!$('input[name=unit]').val()){
			layer.msg('请选择套餐单位');
			return false;
		}				
		return true;
	}
	/*获取套餐数据*/
	if($.cookie('combo_id')){
		var id = $.cookie('combo_id');
	}else{
		var id = null;
	}
	$.ajax({
		url: baseUrl + '/api/manage/onecombo',
		type: 'post',
		async: false,
		data: {
			id: id
		},
		success: function(res){
			console.log(res);
			$('input[name=name]').val(res.title);
			$('input[name=describe]').val(res.describe);
			$('input[name=price]').val(res.price);
			$('input[name=count]').val(res.count);			
			if(res.img){
				$('input[name=img]').val(res.img.url.substring(res.img.url.indexOf('/image/') + 6));
			}
			if(res.tags){
				$('input[name=tags]').val(res.tags);
			}			
			item_id = res.item_id;
			$('img.title').attr({
				'src':res.img.url,
				'alt': res.img_id
			});
			$('input[name=unit]').val(res.unit);
		}
	});

	/*获取项目数据*/
	$.ajax({
		url: baseUrl + '/api/manage/item',
		type: 'post',
		data: {
			start: 0,
			number: 100,
		},
		success: function(res){
			for(var i=0; i<res.rows.length; i++){
				if(item_id == res.rows[i].id){
					$('select[name=item]').append('<option value="'+res.rows[i].id+'" selected>'+res.rows[i].name+'</option>');
				}else{
					$('select[name=item]').append('<option value="'+res.rows[i].id+'">'+res.rows[i].name+'</option>');
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
			'describe': $('input[name=describe]').val(),
			'tags': $('input[name=tags]').val(),
			'name': $('input[name=name]').val(),
			'status': $('input[name=show]').val(),
			'price': $('input[name=price]').val(),
			'count': $('input[name=count]').val(),
			'item': $('select[name=item]').val(),

			'unit': $('input[name=unit]').val(),
		};
		data.img = {
			url:  $('input[name=img]').val(),
			id: $('img.title').attr('alt')
		};
		data.id = $.cookie('combo_id');
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/update_combo',
			type: 'post',
			data:{
				params: data
			},
			success: function(res){				
				if(res){
					layer.alert('新增成功!');
					location.href="combo_manage.html";
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