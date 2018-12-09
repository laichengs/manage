$(function(){
	$('#user').bootstrapTable({
		url: baseUrl + '/api/manage/user',
		method: 'post',
		striped: true,
		cache: false,
		pagination: true,
		sidePagination: 'server',
		pageNumber: 1,
		pageSize: 10,
		pageList: ['10', '20', '30'],
		columns: [
			{
				'checkbox': true
			},
			{
				field: 'id',
				title: '编号'
			},
			{
				field: 'phone',
				title: '手机号'
			},
			{
				field: 'openid',
				title: '微信openid'
			},
			{
				field: 'vip',
				title: '是否会员',
				formatter: function(value, row, index){
					if(value == 1){
						return '<span>是</span>';
					}else{
						return '<span>否</span>';
					}
				}
			},
			{
				field: 'create_time',
				title: '注册时间'
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