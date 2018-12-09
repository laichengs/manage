$(function(){
	$('#search').click(function(){
		$('#card_combo').bootstrapTable('refresh', '');
	})

	$('#card_combo').bootstrapTable({
		url: baseUrl + '/api/manage/card_combo_list',
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
				title: '组合内容',
				formatter: function(value, row ,index){
					return '<span>' + row.card_combo_name + '</span>';
				}
			},
			{
				title: '赠送项目',
				formatter: function(value, row ,index){
					if(row.recharge_id == 1){
						return '<span>套餐赠送</span>';
					}else{
						return '<span>' + row.recharge.name + '</span>';
					}
					
				}
			},
			{
				title: '操作',
				formatter: function(value, row ,index){
					return '<span class="btn btn-xs btn-info" onclick="edit(' + row.id + ')">编辑</span>&nbsp;&nbsp;<span class="btn btn-xs btn-danger" onclick="remove(' + row.id + ')">删除</span>'
				}
			}		
		]
	});
})

$('#add').click(function(){
	location.href="add_card_combo.html";
});


function edit(id){
	$.cookie('card_combo_id', id);
	location.href="update_card_combo.html";
}

function remove(id){
	if(confirm('确定要删除这个组合吗')){
		$.ajax({
			url: baseUrl + '/api/manage/card_combo_item',
			type: 'delete',
			data: {
				id:id
			},
			success: function(res){
				location.reload();
			}
		});		
	}
}