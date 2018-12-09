$(function(){
	$('#search').click(function(){
		$('#card').bootstrapTable('refresh', '');
	})

	$('#card').bootstrapTable({
		url: baseUrl + '/api/manage/card_list',
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
				title: '名称',
				field: 'name'
			},
			{
				title: '适用项目',
				formatter: function(value, row ,index){
					if(row.item){
						return '<span>' + row.item.name + '</span>'
					}
				}
			},
			{
				title: '有效期/天',
				field: 'circle'
			},
			{
				title: '满多少起用',
				field: 'miniamount'
			},
			{
				title: '卡券面额',
				field: 'value'
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
	location.href="add_card.html";
});


function edit(id){
	$.cookie('card_id', id);
	location.href="update_card.html";
}

function remove(id){
	if(confirm('确定要删除这个主题吗')){
		$.ajax({
			url: baseUrl + '/api/manage/card_item',
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