$(function(){
	$('#search').click(function(){
		$('#city').bootstrapTable('refresh', '');
	})

	$('#city').bootstrapTable({
		url: baseUrl + '/api/manage/cities',
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
				field: 'name',
			},
			{
				title: "已开通项目",
				formatter: (value, row, index)=>{
					if(row.item_names){
						return '<span>'+row.item_names+'</span>';
					}
				}
			},
			{
				title: '操作',
				width: '100',
				formatter: (value, row, index)=>{
					str = `
						<span onclick="edit(${row.id})" class="btn btn-xs btn-success">编辑</span>
						<span onclick="remove(${row.id})" class="btn btn-xs btn-danger">删除</span>
					`;
					return str;
				}
			}
		]
	});
})

$('#add').click(function(){
	location.href="add_city.html";
});


function edit(id){
	console.log(id);
	$.cookie('city_id', id);
	location.href="update_city.html";
}

function remove(id){
	if(confirm('确定要删除这个属性吗')){
		$.ajax({
			url: baseUrl + '/api/manage/city',
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