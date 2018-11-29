$(function(){
	$('#search').click(function(){
		$('#combo_manage').bootstrapTable('refresh', '');
	})

	$('#combo_manage').bootstrapTable({
		url: baseUrl + '/api/manage/combo_manage',
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
				'field': 'id',
				'title': '编号'
			},
			{
				field: 'title',
				title: '套餐名称'
			},
			{
				field: 'price',
				title: '套餐价格'
			},
			{
				title: '套餐图片',
				formatter: function(value, row, index){
					if(row.img){
						return '<img src="'+row.img.url+'" style="width:200px;height:100px">';						
					}
				}
			},
			{
				title: '套餐项目',
				formatter: function(value, row, index){
					if(row.item){
						return row.item.name;						
					}
				}
			},
			{
				field: 'count',
				title: '套餐数量'
			},
			{
				field: 'unit',
				title: '套餐单位'
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
				formatter: function(value, row, index){
					return '<span class="btn btn-danger btn-xs" onclick="editCombo('+row.id+')">编辑</span>&nbsp;&nbsp;<span class="btn btn-warning btn-xs" onclick="deleteCombo('+row.id+')">删除</span>';
				}
			}
		]
	});
})

$('#add').click(function(){
	location.href = "add_combo.html";
});

function editCombo(id){
	$.cookie('combo_id', id);
	location.href="update_combo.html";
}

function deleteCombo(id){
	if(confirm('真的要删除这个套餐吗？')){
		$.ajax({
			url: baseUrl + '/api/manage/delete_combo',
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

