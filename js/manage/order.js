$(function(){
	$('#search').click(function(){
		$('#order').bootstrapTable('refresh', '');
	})

	$('#order').bootstrapTable({
		url: baseUrl + '/api/manage/order',
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
				user: $('input[name=user]').val(),
				phone: $('input[name=phone]').val(),
				status: $('select[name=status]').val()
			}
		},
		//pageList: ['10', '20', '30'],
		columns: [
			{
				'checkbox': true
			},
			{
				field: 'order_no',
				title: '编号'
			},
			{
				field: 'phone',
				title: '联系电话'
			},
			{
				field:'count',
				title: '预约数量'
			},
			{
				title: '付款方式',
				formatter: function(value, row, index){
					switch(row.pay_type){
						case "1": 
						return '<span class="text-success">微信支付</span>';
						break;
						case "2":
						return '<span class="text-info">余额支付</span>';
						break;
						case "3":
						return '<span class="text-warning">套餐支付</span>';
						break;
						case "4":
						return '<span class="text-default">代金券支付</span>';
						break;
					}
				}
			},
			{
				title: '预约项目',
				formatter: function(value, row, index){
					return row.item.name;
				}
			},
			{
				title: '预约人',
				formatter: function(value, row, index){
					return row.address.name;
				}
			},
			{
				title: '预约地址',
				formatter: function(value, row, index){
					return row.address.address;
				}
			},
			{
				field: 'status',
				title: '订单状态',
				formatter: function(value, row, index){
					switch(value){
						case '1':
						return '<span class="text-warning">未付款</span>';
						break;
						case '2':
						return '<span class="text-success">已付款</span>';
						break;
						case '3':
						return '<span class="text-primary">已服务</span>';
						break;
						case '4':
						return '<span class="text-info">已完成</span>';
						break;
					}
				}
			},
			{
				title: '预约日期',
				field: 'order_data'
			},
			{
				title: '预约时间',
				field: 'order_time'
			},
			{
				title: '订单金额',
				formatter: function(value, row, index){
					return  row.count * row.snap_price + '元'
				}
			},
			{
				title: '更改状态',
				align: 'center',
				formatter: function(value, row, index){
					return '<span class="btn btn-xs btn-primary" onclick="changeStatus('+row.id+')">点击变为已服务</span>';
				}
			},
			{
				title: '备注',
				align: 'center',
				formatter: function(value, row, index){
					if(row.server_remark){
						return '<span>'+row.server_remark+'</span>';
					}else{
						return '<button class="btn btn-xs btn-success" onclick="addServerRemark('+row.id+')">点击添加</button>'
					}					
				}
			}
		]
	});
})

function changeStatus(id){
	if(confirm('确定把这个状态更改为已服务吗？')){
		$.ajax({
			url: baseUrl + '/api/manage/order_status',
			type: 'put',
			data: {
				id: id
			},
			success: function(res){
				if(res){
					location.reload();
				}
			}
		});
	};
}
/*定义layer关闭框值*/
var open; var ids;
function addServerRemark(ids){
	$.cookie('oid', ids);
	let str = `
		<div style="padding: 30px">
			<input type="text" class="form-control server-remark" placeholder="请在此输入内容" style="margin:20px 0;"/>
			<button class="btn btn-primary confirm">确认</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<button class="btn btn-default cancel">取消</button>
		</div>
	`
	open = layer.open({
		type: 1,
		title: false,
		closeBtn: 0,
		shadeClose: true,
		skin: 'yourclass',
		content: str
	  });
}

$('body').on('click', '.cancel', function(){
	layer.close(open);
})

$('body').on('click', '.confirm', ()=>{
	console.log($.cookie('oid') + $('input.server-remark').val());
	$.ajax({
		url: baseUrl + '/api/manage/order_remark',
		type: 'post',
		data: {
			params: {
				id: $.cookie('oid'),
				text: $('input.server-remark').val()
			}
		},
		success: function(res){
			if(res){
				location.reload();
			}
		}
	});
})