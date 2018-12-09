
$(function(){
	$('button[type=submit]').click(function(){
		var user = $('input[type=text]').val();
		var pass = $('input[type=password]').val();
		$.ajax({
			url: baseUrl + '/api/manage/login',
			type: 'post',
			data: {
				user: user,
				pass: pass
			},
			success: function(res){
				var token = res;
				$.cookie('token', res, token_expire);
				location.href="index.html";
				isLogin = false;
			}
		})
	})



})