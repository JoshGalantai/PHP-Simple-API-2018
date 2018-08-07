$(function() {
    var base_uri = 'http://example.com/index.php';

    // Bindings
    var id_input = $('#id');
    var username_input = $('#username');
    var reason_input = $('#reason');
    var start_input = $('#start');
    var end_input = $('#end');
    var action_input = $('#action');
    var submit = $('#submit');
    var response = $('#response');

    // Reset fields when changing action
    function reset_input() {
        id_input.val('').prop('disabled', true);
        username_input.val('').prop('disabled', true);
        reason_input.val('').prop('disabled', true);
        start_input.val('').prop('disabled', true);
        end_input.val('').prop('disabled', true);
    }

    // Enable / Disable input fields depending on action
    action_input.on('change', function() {
        reset_input();

        var action = this.value;

        switch(action) {
            case 'GET':
            case 'DELETE':
                id_input.prop('disabled', false);
                break;
            case 'PUT':
                id_input.prop('disabled', false);
                username_input.prop('disabled', false);
                reason_input.prop('disabled', false);
                start_input.prop('disabled', false);
                end_input.prop('disabled', false);
                break;
            case 'POST':
                username_input.prop('disabled', false);
                reason_input.prop('disabled', false);
                start_input.prop('disabled', false);
                end_input.prop('disabled', false);
                break;
            default:
                break;
        }
    });

    submit.click(function(e) {
        // Prevent submit from reloading page
        e.preventDefault();

        var params = Array();
        var id = id_input.val();
        var username = username_input.val() ? 'username=' + username_input.val() : '';
        if (username !== '') {
            params.push(username);
        }
        var reason = reason_input.val() ? 'reason=' + reason_input.val() : '';
        if (reason !== '') {
            params.push(reason);
        }
        var start = start_input.val() ? 'start=' + start_input.val() : '';
        if (start !== '') {
            params.push(start);
        }
        var end = end_input.val() ? 'end=' + end_input.val() : '';
        if (end !== '') {
            params.push(end);
        }

        // Build query param string
        var query_params = (params.join('&') !== '') ? '?' + params.join('&') : '';

        var action = action_input.find(':selected').val();
        var request_uri = '';
        var request_type = action;

        switch(action) {
            case 'GET':
                request_uri = (id) ? '/bookings/' + id : '';
                break;
            case 'DELETE':
                request_uri = (id) ? '/bookings/' + id : '';
                break;
            case 'PUT':
                request_uri = (id) ? '/bookings/' + id : '';
                break;
            case 'POST':
                request_uri = '/bookings';
                break;
            default:
                request_uri = '/bookings';
                request_type = 'GET';
                break;
        }

        // Super basic input validation
        if (request_uri === '') {
            response.text('Invalid input!');
            return;
        }

        // Super basic ajax calls to routes
        $.ajax({
            url: base_uri + request_uri + query_params,
            type: request_type,
            error: function(xhr, status, error) {
                code = xhr.status;
                error = xhr.statusText;
                response.text('Status code: ' + code + ' - ' + error);
            },
            'success' : function(data) {
                // Don't do this, haha
                var regex = '<!DOCTYPE html>(.*?)<\/html>';
                response.text(data.replace(new RegExp(regex,'sg'),''));
            }
        });
    });
});