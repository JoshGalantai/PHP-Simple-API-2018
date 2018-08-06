$(function() {
    var base_uri = 'http://example.com/index.php';
    var response = $('#response');

    $('#submit').click(function(e) {
        // Prevent submit from reloading page
        e.preventDefault();

        var request_type = $('#request_type').find(':selected').val();
        var request_uri = $('#request_uri').val();
        var request_params = $('#request_params').val();

        // Super basic input validation
        if (request_uri === '') {
            response.text('Missing URI');
            return;
        }

        // In the real world this should be passed in as data {}
        query_params = '';
        if (request_params !== '') {
            query_params = '?' + request_params.replace(/(?:\r\n|\r|\n)/g, '&');
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
                regex = '<!DOCTYPE html>(.*?)<\/html>';
                data = data.replace(new RegExp(regex,'sg'),'')
                response.text(data);
            }
        });
    });
});