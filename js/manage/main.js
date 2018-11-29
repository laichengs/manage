/** 获取今天总收入 */
$.ajax({
	url: baseUrl + '/api/index/total_amount',
	type: 'get',
	success: function(res){
		console.log(res);
		$('#total').text(res.amount);
		$('#order').text(res.order_count);
		$('#recharge').text(res.recharge_count);
		$('#combo').text(res.combo_count);
	}
});	