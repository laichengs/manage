$(function(){
	$('#search').click(function(){
		$('#business').bootstrapTable('refresh', '');
	})

	$('#business').bootstrapTable({
		url: baseUrl + '/api/manage/business',
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
				'checkbox': true
			},
			{
				field: 'id',
				title: '编号'
			},
			{
				title: '缩略图',
				formatter: function(value, row, index){
					if(row.img){
						return '<img src="'+row.img.url+'" style="width:100px;height: 80px">';						
					}
				}
			},
			{
				title: '名称',
				field: 'name'
			},
			{
				title: '排序',
				field: 'order'
			},			
			{
				title: '操作',
				formatter: function(value, row, index){
					return '<span class="btn btn-danger btn-xs" onclick="editbusiness('+row.id+')">编辑</span>&nbsp;&nbsp;<span class="btn btn-warning btn-xs" onclick="deletebusiness('+row.id+')">删除</span>&nbsp;&nbsp;<span class="btn btn-info btn-xs" onclick="targetChild('+row.id+')">进入子栏目</span>';
				}
			}
		]
	});
})

$('#add').click(function(){
	location.href="add_business.html";
});

function hello(id, that){
	$.ajax({
		url: baseUrl + '/api/manage/business/change_sort',
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

function editbusiness(id){
	$.cookie('business_id', id);
	location.href="update_business.html";
}

function deletebusiness(id){
	if(confirm('确定要删除这个主题吗')){
		$.ajax({
			url: baseUrl + '/api/manage/delete_business',
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

function targetChild(id){
	$.cookie('business_id', id);
	location.href="business_item.html";
}