$(function(){
	$('#search').click(function(){
		$('#combo').bootstrapTable('refresh', '');
	})

	$('#combo').bootstrapTable({
		url: baseUrl + '/api/manage/combo',
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
				combo: $('select[name=combo]').val(),
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
				title: '编号'
			},
			{
				title: '套餐名称',
				formatter: function(value, row, index){
					if(row.combo){
						return row.combo.title
					}					
				}
			},
			{
			   	title: '套餐原价',
				field: 'price'
			},
			{
				title: '折扣价格',
				field: 'discount_price'
			},
			{
				title: '套餐数量',
				field: 'count'
			},
			{
				title: '下单时间',
				field: 'create_time'
			},
			{
				title: '用户名称',
				formatter: function(value, row, index){
					if(row.user){
						return row.user.nickname
					}					
				}
			},
			{
				title: '用户手机',
				formatter: function(value, row, index){
					if(row.user){
						return row.user.phone
					}	
				}
			},
			{
				title: '推荐人',
				formatter: function(value, row, index){
					if(row.referrer != null){
						return row.referrer.name
					}
				}
			},
			{
				title: '付款状态',
				formatter: function(value, row, index){
					if(row.status == 0){
						return '<span>未付款</span>';
					}else if(row.status == 1){
						return '<span class="text-danger">已付款</span>';
					}
				}
			}
		]
	});
})