$(function(){
	$('#search').click(function(){
		$('#recharge').bootstrapTable('refresh', '');
	})

	$('#recharge').bootstrapTable({
		url: baseUrl + '/api/manage/recharge',
		method: 'post',
		striped: true,
		cache: false,
		pagination: true,
		sidePagination: 'server',
		pageNumber: 1,
		pageSize: 10,
		queryParams: function(params){
			return {
				number: params.limit,
				start: params.offset,
				orderno: $('input[name=orderno]').val(),
				referrer: $('input[name=referrer]').val(),
				status: $('select[name=status]').val()
			}
		},
		//pageList: ['10', '20', '30'],
		columns: [
			{
				'checkbox': true
			},
			{
				field: 'serial_no',
				title: '订单编号'
			},
			{
				field: 'amount',
				title: '充值原价'
			},
			{
				field: 'discount_amount',
				title: '折扣价格'
			},
			{
				title: '用户昵称',
				formatter: function(value, row, index){
					if(row.user){
						return row.user.nickname;
					}					
				}
			},
			{
				'title': '用户手机',
				formatter: function(value, row, index){
					if(row.user){
						return row.user.phone;
					}	
				}
			},
			{
				title: '推荐人',
				formatter: function(value, row, index){
					if(row.referrer){
						return row.referrer.name;
					}					
				}
			},
			{
				title: '充值状态',
				field: 'status',
				formatter: function(value, row, index){
					if(value == '0'){
						return '<span class="text-danger">充值失败</span>';
					}else if(value == '1'){
						return '<span class="text-success">充值成功</span>';
					}
				}
			}
		]
	});
})