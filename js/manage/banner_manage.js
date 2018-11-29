$(function(){
	$('#search').click(function(){
		$('#banner_manage').bootstrapTable('refresh', '');
	})

	$('#banner_manage').bootstrapTable({
		url: baseUrl + '/api/manage/banner_manage',
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
				title: '编号',
				formatter: function(value, row, index){
					return row.id;
				}			
			},
			{
				title: '图片',
				formatter: function(value, row, index){
					if(row.img){
						return '<img src="'+row.img.url+'" style="width: 100px;height:50px">';
					}
					
				}
			},
			{
				title: '跳转项目',
				formatter: function(value, row, index){
					if(row.item){
						return '<span>'+row.item.name+'</span>';
					}
					
				}
			},
			{
				title: '所属位置',
				formatter: function(value, row, index){
					if(row.banner){
						return '<span>'+row.banner.describe_name+'</span>';
					}
					
				}
			},
			{
				title: '操作',
				formatter: function(value, row, index){
					return '<span class="btn btn-danger btn-xs" onclick="editBanner('+row.id+')">编辑</span>&nbsp;&nbsp;<span class="btn btn-warning btn-xs" onclick="deleteBanner('+row.id+')">删除</span>';
				}
			}
		]
	});
})



$('#add').click(function(){
	location.href="add_banner.html";	
});

function editBanner(id){
	$.cookie('banner_id', id);
	location.href="update_banner.html";
}

function deleteBanner(id){
	if(confirm('确定删除这个banner吗？')){
		$.ajax({
			url: baseUrl + '/api/manage/delete_banner',
			type: 'post', 
			data: {
				id: id
			},
			success: function(res){
				location.reload();
			}
		});		
	}
}
