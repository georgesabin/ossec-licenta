$(document).ready(function() {

  showLoader($('.auditTableLoader'));
	auditTable = $('.dataTable').on('xhr.dt', function ( e, settings, json, xhr ) {
		hideLoader($('.auditTableLoader'));
	}).dataTable({
    'bFilter': false,
    'bSort': false,
    'serverSide': true,
    'lengthMenu': [50, 100, 200, 500, 1000],
		'iDisplayLength': 50,
    'ajax': {
      'url': baseURL + 'audit/getAudit',
      'type': 'POST',
      'data': function(data) {
        data.date_from_audit = $('#date_from_audit').val();
        data.date_to_audit = $('#date_to_audit').val();
        data.user = $('#usersFullNameSelector').val();
        data.log_action = $('#logActionSelector').val();
        data.target_type = $('#logTargetEntitySelector').val();
        data.token = Cookies.get('token');
      }
    },
    'columns': [
      {'data': 'log_id'},
      {'data': 'log_action'},
      // {'data': 'log_date'},
      {'data': 'log_user'}, // full name of the user after a join with the users
      {'data': 'log_target_entity_type'},
      {'data': 'log_target_entity_id'}, // in case the type is user, show the full name
      {'data': 'log_value'} // print_r(json_decode(STRING), true) for the moment, this will be done in the controller before sending the data to the datatable
    ]
	});

  $('.btn-getAudit').click(function(e) {
		showLoader($('.auditTableLoader'));
		auditTable.api().ajax.reload();
	});

  $('#date_from_audit').dateDropper();
	$('#date_to_audit').dateDropper();

  $('*[name="usersFullNameSelector"]').select2({
    allowClear: true,
    placeholder: 'All full names of users'
  });

  $('*[name="logActionSelector"]').select2({
    allowClear: true,
    placeholder: 'All logs action'
  });

  $('*[name="logTargetEntitySelector"]').select2({
    allowClear: true,
    placeholder: 'All entities types'
  });

});
