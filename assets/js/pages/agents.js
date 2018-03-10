var agentsTable;

$(document).ready(function() {

    agentsTable = $('.dataTable').on('xhr.dt', function ( e, settings, json, xhr ) {
        hideLoader($('.agentsTableLoader'));
        $('[data-toggle="tooltip"]').tooltip();
    }).dataTable({
        'bFilter': false,
        'bSort': true,
        'serverSide': true,
        'lengthMenu': [50, 100, 200, 500, 1000],
        'iDisplayLength': 50,
        'order': [[ 0, 'desc' ]],
        'ajax': {
            'url': baseURL + 'agent/getAgents',
            'type': 'POST',
            'data': function(data) {
                data.token = Cookies.get('token');
            }
        },
        'columns': [
            {'data': 'id'},
            {'data': 'agent_id'},
            {'data': 'agent_name'},
            {'data': 'agent_ip'},
            {'data': 'agent_date_created'},
            {'data': 'action'}
        ],
        columnDefs: [
            {
                'targets': [5],
                'orderable': false
            },
            // {
            //     "render": function (data, type, row) {
            //         return (data == '1' ? '<i class="fa fa-circle text-success"></i> ON' : '<i class="fa fa-circle text-danger"></i> OFF');
            //     },
            //     "targets": 6
            // }
        ]
    });

    $('#add-agent').on('click', function() {

        $('#addAgent').modal('show');

    });

});

function removeAgent(agent_id) {

    $.ajax({
        url: baseURL + 'agent/removeAgent/' + agent_id,
        method: 'POST',
        data: {
            token: Cookies.get('token')
        },
        success: function(data) {
            agentsTable.api().ajax.reload();
        }
    });

}

function getAgentKey(agent_id){

    $.ajax({
        url: baseURL + 'agent/getAgentKey/' + agent_id,
        method: 'POST',
        data: {
            token: Cookies.get('token')
        },
        success: function(data) {
            console.log(data);
            data = JSON.parse(data);
            $('#keyAgent').modal('show');
            $('#keyAgent .modal-body').html('<h4>' + data.response + '</h4>');
        }
    });

}