$(function(){

	let id = $.cookie('item_Id');

	console.log(id);
	$('#search').click(function(){
		$('#item_type').bootstrapTable('refresh', '');
	})

	$('#item_type').bootstrapTable({
		url: baseUrl + '/api/manage/item_type_list/' + id,
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
				title: '缩略图',
				formatter: (value, row, index)=>{
					if(row.img){
						return '<img src="'+row.img.url+'" style="width:100px;height:100px;"/>'
					}
				}
			},
			{
				title: '名称',
				field: 'title'
			},
			{
				title: '所属栏位',
				formatter: (value, row, index)=>{
					if(row.item){
						return '<span>' + row.item.name + '</span>'
					}
				}
			},
			{
				title: '价格',
				field: 'price'
			},
			{
				title: '单位',
				field: 'unit'
			},
			{
				title: '操作',
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
	location.href="add_item_type.html";
});


function edit(id){
	console.log(id);
	$.cookie('item_type_id', id);
	location.href="update_item_type.html";
}

function remove(id){
	if(confirm('确定要删除这个属性吗')){
		$.ajax({
			url: baseUrl + '/api/manage/item_type',
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