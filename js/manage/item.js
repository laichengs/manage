$(function(){
	$('#search').click(function(){
		$('#item').bootstrapTable('refresh', '');
	})

	$('#item').bootstrapTable({
		url: baseUrl + '/api/manage/item',
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
				field: 'id',
				title: '项目编号'
			},
			{
				field: 'name',
				'title': '项目名称'
			},
			{
				title: '菜单图标',
				formatter: function(value, row, index){
					return '<img src="'+row.title_img.url+'" style="width:30px;height:30px">';
				}
			},
			{
				title: '首页缩略图',
				formatter: function(value, row, index){
					if(row.thumb_img != null){
						return '<img src="'+row.thumb_img.url+'" style="width:30px;height:30px">';						
					}
				}
			},
			{
				title: '详情头图',
				formatter: function(value, row, index){
					if(row.main_img != null){
						return '<img src="'+row.main_img.url+'" style="width: 60px;height: 30px">';						
					}
				}
			},
			{
				'title': '排序',
				formatter: function(value, row, index){
					return '<input type="text" value="'+row.sort+'" style="height:20px;width: 50px;text-align:center;" onblur="hello('+row.id+', this)">';
				}
			},
			{
				'title': '状态',
				formatter: function(value, row, index){
					if(row.status == 1){
						return '<span class="text-success">上架中</span>';
					}else{
						return '<span class="text-default">已停用</span>';
					}
				}
			},
			{
				title: '操作',
				align: 'center',
				formatter: function(value, row, index){
					return '<span class="btn btn-danger btn-xs" onclick="editItem('+row.id+')">编辑</span>&nbsp;&nbsp;<span class="btn btn-warning btn-xs" onclick="deleteItem('+row.id+')">删除</span>';
				}
			}
		]
	});
})

function hello(id, that){
	$.ajax({
		url: baseUrl + '/api/manage/item/change_sort',
		method: 'post',
		data: {
			sort: $(that).val(),
			id: id
		},
		success: function(res){
			console.log(res);
		}
	});
}


function editItem(id){
	$.cookie('item_Id', id);
	location.href="edit_item.html";
}

function deleteItem(id){
	if(confirm('确定要删除这个项目吗？')){
		$.ajax({
			url: baseUrl + '/api/manage/delete_item',
			type: 'post',
			data: {
				id: id
			},
			success: function(res){
				console.log(res);
				location.reload();
				// if(res){
				// 	alert("123");
				// }
			}
		});
	};
}

$('#add').click(function(){
	location.href="add_item.html";	
});
