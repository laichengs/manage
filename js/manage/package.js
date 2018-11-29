$(function(){
	$('#search').click(function(){
		$('#package').bootstrapTable('refresh', '');
	})

	$('#package').bootstrapTable({
		url: baseUrl + '/api/manage/package_items',
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
				start: params.offset,
				orderno: $('input[name=orderno]').val(),
				referrer: $('input[name=referrer]').val(),
				theme: $('select[name=theme]').val(),
				status: $('select[name=status]').val()
			}
		},
		//pageList: ['10', '20', '30'],
		columns: [
			{
				'checkbox': true
			},
			{
				field: 'id',
				title: '编号'
			},
			{
				field: 'title',
				title: '名称'
			},
			{
				field: 'describe',
				title: '描述'
			},
			{
				title: '缩略图',
				formatter: function(value, row, index){
					if(row.img){
						return '<img src="'+row.img.url+'" style="width:50px;height:50px;">';
					}					
				}
			},
			{
				title: '所属组合',
				formatter: function(value, row, index){
					if(row.package){
						return row.package.title
					}
					
				}
			},
			{
				title: '跳转栏目',
				formatter: function(value, row, index){
					if(row.item){
						return row.item.name;	
					}					
				}
			},
			{
				title: '操作',
				formatter: function(value, row, index){
					return '<span class="btn btn-danger btn-xs" onclick="editPackage('+row.id+')">编辑</span>&nbsp;&nbsp;<span class="btn btn-warning btn-xs" onclick="deletePackage('+row.id+')">删除</span>';
				}
			}			
		]
	});
})

$('#add').click(function(){
	location.href="add_package.html";
});

function editPackage(id){
	$.cookie('package_id', id);
	location.href="update_package.html";
}

function deletePackage(id){
	if(confirm('确定要删除这个组内数据吗')){
		$.ajax({
			url: baseUrl + '/api/manage/delete_package_item',
			type: 'post',
			data: {
				id:id
			},
			success: function(res){
				location.reload();
			}
		});		
	}
}