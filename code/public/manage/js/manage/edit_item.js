$(function(){
	function processData(data){
		var index = data.indexOf('/image');
		return data.substring(index+6);
	}
	var id = $.cookie('item_Id');
	/*获取项目信息*/
	$.ajax({
		url: baseUrl + '/api/manage/oneitem',
		type: 'post',
		data: {
			'id': id
		},
		success: function(res){
			console.log(res);
			$('input[name=name]').val(res.name);
			$('input[name=price]').val(res.price);
			$('input[name=vip_price]').val(res.vip_price);
			$('input[name=start_price]').val(res.start_price);
			$('input[name=title]').val(processData(res.title_img.url));
			if(res.main_img){
				$('input[name=main]').val(processData(res.main_img.url));
				$('img.main').attr({
					'src': res.main_img.url,
					'alt': res.main_img_id
				});					
			}
			if(res.thumb_img){
				$('input[name=thumb]').val(processData(res.thumb_img.url));
				$('img.thumb').attr({
					'src': res.thumb_img.url,
					'alt': res.thumb_img_id
				});					
			}			
			$('img.title').attr({
				'src': res.title_img.url,
				'alt': res.title_img_id
			});		
			if(res.detail != ''){
				for(var i = 0; i<4; i++){
					$('img.main-' + (i+1)).attr({
						'src': res.detail[i].img.url,
						'alt': res.detail[i].img_id
					});		
					$('input[name=main-' + (i+1) +']').val(processData(res.detail[i].img.url));
				}				
			}
			var str = '';
			for(var i=0; i<2; i++){
				str += '<label class="radio-inline">';
				if(i == 0){
					if(i == res.is_show_index){
						str += '<input type="radio" value="0" name="show" checked> 不显示';
					}else{
						str += '<input type="radio" value="0" name="show"> 不显示';
					}					
				}else{
					if(i == res.is_show_index){
						str += '<input type="radio" value="1" name="show" checked> 显示';						
					}else{
						str += '<input type="radio" value="1" name="show"> 显示';
					}

				}
				str += '</label>';
			}
			$('#showbox').append(str);
		}
	});

	/*修改项目数据*/
	$('input[type=submit]').click(function(){
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
			tem.id = $('img.main-' + i).attr('alt');
			$('input[name=main-'+i+']').val() != '' ? tem.url = $('input[name=main-'+i+']').val() : null;
			detail[i-1] = tem;
		}
		var title = {
			id: $('img.title').attr('alt'),
			url: $('input[name=title]').val()
		}
		detail.push(title);
		var main = {
			id: $('img.main').attr('alt'),
			url: $('input[name=main]').val()
		}	
		detail.push(main);	
		data.detail = detail;
		data.id = id;
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/update_item',
			type: 'post',
			data:{
				params: data
			},
			success: function(res){
				if(res){
					layer.alert('修改成功!');
					setTimeout(function(){
						location.href="item.html";
					}, 2000);
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