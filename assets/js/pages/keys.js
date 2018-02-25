
var keysTable;

$(document).ready(function() {

	keysTable = $('.dataTable').dataTable({
		bFilter: true,
		bPaginate: true,
		// responsive: true,
		'ajax': {
			'url': baseURL+'keys/getDataTableRows',
			'type': 'POST',
			'data': function(data){
				data.token = Cookies.get('token');
			}
		},
		'columns': [
			{'data': 'key_id'},
			{'data': 'key_description'},
			{'data': 'key_value'},
			{'data': 'key_datetime_created'},
			{'data': 'key_datetime_last_used'},
			{'data': 'key_status'},
			{'data': 'actions'}
		],
		columnDefs: [{
			'targets': 6,
			'orderable': false
		},
		{
			"render": function (data, type, row) {
				return (data == '1' ? '<i class="fa fa-circle text-success"></i> ON' : '<i class="fa fa-circle text-danger"></i> OFF');
			},
			"targets": 5
		}]
	});

});

function deleteKey(keyId) {

	bootbox.confirm("Are you sure you want to delete this key?", function(result) {
		if(result) {
			$.ajax({
				url: baseURL+'keys/deleteKey/'+keyId,
				type: 'post',
				data: {token: Cookies.get('token')}
			}).done(function(result) {
				if(result === 'true') {
					keysTable.api().ajax.reload();
				}
			});
		}
	});

}
