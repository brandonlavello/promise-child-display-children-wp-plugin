jQuery(document).ready(function ($) {
    $(document).on('click', '.graphql-api-button', function (e) {
        e.preventDefault();
        alert("I am an alert box!");
        var button = $(this);
        var page = button.data('page');

        // Perform AJAX request
        $.ajax({
            url: graphql_api_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'graphql_api_ajax_pagination',
                page: page,
            },
            success: function (response) {
                $('#graphql-api-container').html(response);
            },
        });
    });
});