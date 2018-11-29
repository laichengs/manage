$(function(){
	/*检测是否有数据*/
	function checkData(){
		if(!$('input[name=len]').val()){
			layer.msg('请输入密匙长度');
			return false;
		}
		if(!$('input[name=number]').val()){
			layer.msg('请输入生成数量');
			return false;
		}			
		return true;
	}
	/*获取组合数据*/
	$.ajax({ 
		url: baseUrl + '/api/manage/card_combo_list',
		type: 'get',
		data: {
			start: 0,
			number: 1000
		},
		success: function(data){
			let res = data['rows'];
			console.log(res);
			for(var i=0; i<res.length; i++){
				$('select[name=combo]').append('<option value="'+res[i].id+'">'+res[i].combo_name+'</option>');
			}
		}
	});

	/*增加数据*/
	$('input[type=submit]').click(function(){
		if(!checkData()){
			return false;
		}
		var data = {
			'card_combo': $('select[name=combo]').val(),
			'len': $('input[name=len]').val(),
			'number': $('input[name=number]').val()
		};
		console.log(data);
		$.ajax({
			url: baseUrl + '/api/manage/card_key',
			type: 'post',
			data:{
				params: data
			},
			success: function(res){		
				console.log(res);		
				// if(res){
				// 	layer.alert('修改成功!');
				// 	location.href="card.html";
				// }
			}
		});
	});
})
