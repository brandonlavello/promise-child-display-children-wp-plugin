jQuery(document).ready(function ($) {
    // Function to handle the AJAX request
    function fetchData(page, country) {
        $.ajax({
            url: graphql_api_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'graphql_api_ajax_pagination',
                page: page,
                country: country,
            },
            success: function (response) {
                $('#graphql-api-container').html(response);
            },
        });
    }

    // Use event delegation for the country dropdown change event
    $(document).on('change', '#graphql-api-country', function () {
        var country = $(this).val();
        var page = 1; // Reset to the first page when filtering

        // Fetch data using AJAX
        fetchData(page, country);
    });

    // Pagination button click handler
    $(document).on('click', '.graphql-api-button', function (e) {
        e.preventDefault();
        var button = $(this);
        var page = button.data('page');
        var country = $('#graphql-api-country').val(); // Get the selected country from the dropdown

        // Fetch data using AJAX
        fetchData(page, country);
    });
});