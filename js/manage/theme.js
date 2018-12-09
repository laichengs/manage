$(function(){
	$('#search').click(function(){
		$('#theme').bootstrapTable('refresh', '');
	})

	$('#theme').bootstrapTable({
		url: baseUrl + '/api/manage/theme',
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
				title: '跳转栏目',
				formatter: function(value, row, index){
					if(row.item){
						return '<span>'+row.item.name+'</span>';						
					}
				}
			},
			{
				title: '跳转套餐',
				formatter: function(value, row, index){
					if(row.combo){
						return '<span>'+row.combo.title+'</span>';
					}					
				}
			},
			{
				title: '所属主题',
				formatter: function(value, row, index){
					if(row.theme){
						return '<span>'+row.theme.name+'</span>';
					}					
				}
			},
			{
				title: '跳转目标',
				field: 'type',
				formatter: function(value, row ,index){
					if(value == 1){
						return '<span>项目</span>';
					}else if(value == 2){
						return '<span>自定义</span>';
					}
				}
			},
			{
				title: '标题',
				field: 'title'
			},
			{
				title: '描述',
				field: 'describe'
			},
			{
				title:'排序',
				formatter:function(value, row, index){
					return '<input type="number" value="'+row.order+'" style="width:30px;height:20px;text-align:center;display:block"  onblur="hello('+row.id+', this)">';
				}
			},
			{
				title: '操作',
				formatter: function(value, row, index){
					return '<span class="btn btn-danger btn-xs" onclick="editTheme('+row.id+')">编辑</span>&nbsp;&nbsp;<span class="btn btn-warning btn-xs" onclick="deleteTheme('+row.id+')">删除</span>';
				}
			}
		]
	});
})

$('#add').click(function(){
	location.href="add_theme.html";
});

function hello(id, that){
	$.ajax({
		url: baseUrl + '/api/manage/theme/change_sort',
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

function editTheme(id){
	$.cookie('theme_id', id);
	location.href="update_theme.html";
}

function deleteTheme(id){
	if(confirm('确定要删除这个主题吗')){
		$.ajax({
			url: baseUrl + '/api/manage/delete_theme_item',
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