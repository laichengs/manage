$(function(){
	$('#search').click(function(){
		$('#card_key').bootstrapTable('refresh', '');
	})

	$('#card_key').bootstrapTable({
		url: baseUrl + '/api/manage/card_key_list',
		method: 'get',
		striped: true,
		cache: false,
		pagination: true,
		sidePagination: 'server',
		pageNumber: 1,
		pageSize: 10,
		queryParams: function(params){
			return {
				number: params.limit,
				start: params.offset
			}
		},
		//pageList: ['10', '20', '30'],
		columns: [
			{
				title: '序号',
				field: 'id'
			},
			{
				title: '包含卡券内容',
				field: 'card_combo_name'	
			},
			{
				title: "密匙",
				field: 'key'
			},
			{
				title: '属于哪种兑换项目',
				formatter: function(value, row ,index){
					if(row.combo){
						return '<span>' + row.combo.combo_name + '</span>';
					}
				}
			},
			{
				title: '生成时间',
				field: 'create_time'
			},
			{
				title: '状态',
				formatter: function(value, row, index){
					switch(row.status){
						case "1":
						return '<span class="text-success">未兑换</span>';
						break;
						case "0":
						return '<span class="text-warning">已兑换</span>';
						break;											
					}
				}
			}
		]
	});
})

$('#add').click(function(){
	location.href="create_key.html";
});

