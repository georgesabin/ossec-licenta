
var usersTable;

$(document).ready(function() {

  showLoader($('.usersTableLoader'));
  usersTable = $('.dataTable').on('draw.dt', function ( e, settings, json, xhr ) {
    hideLoader($('.usersTableLoader'));
    $('[data-toggle="tooltip"]').tooltip();
  }).dataTable({
    'bFilter': false,
    'bSort': true,
    'serverSide': true,
    'lengthMenu': [50, 100, 200, 500, 1000],
    'iDisplayLength': 50,
    'order': [[ 0, 'desc' ]],
    'ajax': {
      'url': baseURL + 'users/getDataTableRows',
      'type': 'POST',
      'data': function(data) {
        data.user_full_name = $('*[name="user_full_name"]').val();
        data.user_name = $('*[name="user_name"]').val();
        data.user_role = $('*[name="user_role"]').val();
        data.user_mail = $('*[name="user_mail"]').val();
        data.user_date_from = $('*[name="user_date_from"]').val();
        data.user_date_to = $('*[name="user_date_to"]').val();
        data.token = Cookies.get('token');
      }
    },
    'columns': [
      {'data': 'user_id'},
      {'data': 'user_full_name'},
      {'data': 'user_name'},
      {'data': 'user_role'},
      {'data': 'user_mail'},
      {'data': 'user_created_date'},
      {'data': 'user_status'},
      {'data': 'actions'}
    ],
    columnDefs: [{
      'targets': [6, 7],
      'orderable': false
    },
    {
      "render": function (data, type, row) {
        return (data == '1' ? '<i class="fa fa-circle text-success"></i> ON' : '<i class="fa fa-circle text-danger"></i> OFF');
      },
      "targets": 6
    }]
  });

  $('.btn-getUsers').click(function(e) {
    showLoader($('.usersTableLoader'));
    usersTable.api().ajax.reload();
  });

  // Select2 on user_rule
  $('*[name="user_role"]').select2({
    allowClear: true,
    placeholder: 'All user roles'
  });

});

function deleteUser(userId) {

  bootbox.confirm('Are you sure you want to delete this user?<br /><i><small>This action cannot be undone !</small></i>', function(result) {
    if(result) {
      $.ajax({
        url: baseURL+'users/deleteUser/'+userId,
        type: 'post',
        data: { token: Cookies.get('token') }
      }).done(function(result) {
        if(result === 'true') {
          new Noty({
            text        : 'User removed',
            type        : 'error',
            layout      : 'topRight',
            timeout     : 5000,
            progressBar : true
          }).show();
          usersTable.api().ajax.reload();
        }
      });
    }

  });

}
