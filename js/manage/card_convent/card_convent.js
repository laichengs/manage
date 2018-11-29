$(function(){
	$('#search').click(function(){
		$('#card_convent').bootstrapTable('refresh', '');
	})

	$('#card_convent').bootstrapTable({
		url: baseUrl + '/api/manage/card_convent_list',
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
				title: '用户手机',
				field: 'phone'
			},
			{
				title: "兑换时间",
				field: 'grant_date'
			},
			{
				title: '过期时间',
				field: 'expiry_date'
			},
			{
				title: '起用金额',
				field: 'minimum_amount'
			},
			{
				title: '状态',
				formatter: function(value, row, index){
					switch(row.status){
						case "0":
						return '<span class="text-success">未使用</span>';
						break;
						case "1":
						return '<span class="text-warning">已使用</span>';
						break;			
						case "2":
						return '<span class="text-info">已过期</span>';
						break;									
					}
				}
			},
			{
				title: '适用项目',
				formatter: function(value, row, index){
					if(row.item){
						return '<span>' + row.item.name + '</span>'
					}
				}
			},
			{
				title: '代金券面额',
				field: 'amount'
			}	
		]
	});
})

$('#add').click(function(){
	location.href="add_card_convent.html";
});


function edit(id){
	$.cookie('card_convent_id', id);
	location.href="update_card_convent.html";
}

function remove(id){
	if(confirm('确定要删除这个组合吗')){
		$.ajax({
			url: baseUrl + '/api/manage/card_convent_item',
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