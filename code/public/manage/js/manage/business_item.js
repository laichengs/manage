$(function(){
	$('#search').click(function(){
		$('#business').bootstrapTable('refresh', '');
	})
	/*获取business_id数据*/
	if($.cookie('business_id')){
		var id = $.cookie('business_id');
	}else{
		var id = null;
	}
	$('#business_item').bootstrapTable({
		url: baseUrl + '/api/manage/business_item?id=' + id,
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
				title: '描述',
				field: 'describe'
			},
			{
				title: '标签',
				field: 'tag'
			},	
			{
				title: '地址',
				field: 'address'
			},	
			{
				title: '电话',
				field: 'phone'
			},	
			{
				title: '城市',
				formatter: function(value, row ,index){
					if(row.business.city){
						return '<span>'+row.business.city.name+'</span>';
					}
				}
			},	
			{
				title: '商家名称',
				formatter: function(value, row ,index){
					if(row.business){
						return '<span>'+row.business.name+'</span>';
					}
				}
			},		
			{
				title: '是否推荐',
				formatter: function(value, row, index){
					if(row.recommend == 1){
						return '<span>是</span>'
					}else{
						return '<span>否</span>'
					}
				}
			},	
			{
				title: '操作',
				formatter: function(value, row, index){
					return '<span class="btn btn-danger btn-xs" onclick="editbusiness('+row.id+')">编辑</span>&nbsp;&nbsp;<span class="btn btn-warning btn-xs" onclick="deletebusiness('+row.id+')">删除</span>';
				}
			}
		]
	});
})

$('#add').click(function(){
	location.href="add_business_item.html";
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
	$.cookie('business_item_id', id);
	location.href="update_business_item.html";
}

function deletebusiness(id){
	if(confirm('确定要删除这个商家吗')){
		$.ajax({
			url: baseUrl + '/api/manage/business_item',
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