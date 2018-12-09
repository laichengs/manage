$(function(){
	$('#search').click(function(){
		$('#order').bootstrapTable('refresh', '');
	})

	$('#order').bootstrapTable({
		url: baseUrl + '/api/manage/order',
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
				user: $('input[name=user]').val(),
				phone: $('input[name=phone]').val(),
				status: $('select[name=status]').val()
			}
		},
		//pageList: ['10', '20', '30'],
		columns: [
			{
				'checkbox': true
			},
			{
				field: 'order_no',
				title: '编号'
			},
			{
				field: 'phone',
				title: '联系电话'
			},
			{
				field:'count',
				title: '预约数量'
			},
			{
				title: '预约项目',
				formatter: function(value, row, index){
					return row.item.name;
				}
			},
			{
				title: '预约人',
				formatter: function(value, row, index){
					return row.address.name;
				}
			},
			{
				title: '预约地址',
				formatter: function(value, row, index){
					return row.address.address;
				}
			},
			{
				field: 'status',
				title: '订单状态',
				formatter: function(value, row, index){
					switch(value){
						case '1':
						return '<span class="text-warning">未付款</span>';
						break;
						case '2':
						return '<span class="text-success">已付款</span>';
						break;
						case '3':
						return '<span class="text-primary">待确认</span>';
						break;
						case '4':
						return '<span class="text-info">已完成</span>';
						break;
					}
				}
			},
			{
				title: '预约日期',
				field: 'order_data'
			},
			{
				title: '预约时间',
				field: 'order_time'
			},
			{
				title: '订单金额',
				formatter: function(value, row, index){
					return  row.count * row.snap_price + '元'
				}
			},
			{
				title: '操作',
				align: 'center',
				formatter: function(value, row, index){
					return '<button class="btn btn-xs btn-info">删除</button>'
				}
			}
		]
	});
})