jQuery(document).ready(function($) {
    function fetchNews(page = 1) {
        var search_term = $('#search-term').val();
        var category_id = $('#category-filter').val();

        $.ajax({
            url: ajax_obj_params.ajax_url,
            type: 'post',
            data: {
                action: 'custom_product_search',
                nonce: ajax_obj_params.nonce,
                search_term: search_term,
                category_id: category_id,
                paged: page
            },
            success: function(response) {
                if (response.success) {
                    var results = response.data;
                    var $resultsContainer = $('#search-results');
                    $resultsContainer.empty();

                    if (results.length) {
                        $.each(results, function(index, result) {
                            $resultsContainer.append('<li><a href="' + result.permalink + '">' + result.title + '</a></li>');
                        });
                    } else {
                        $resultsContainer.append('<li>No results found.</li>');
                    }

                    // Pagination logic here
                    if (response.data.length === 10) {
                        $('#pagination').html('<button id="load-more">Load More</button>');
                    } else {
                        $('#pagination').empty();
                    }
                }
            }
        });
    }

    $('#search-term').on('keyup', function() {
        fetchNews();
    });

    $('#category-filter').on('change', function() {
        fetchNews(); // Update results on category change
    });

    $('#pagination').on('click', '#load-more', function() {
        var page = parseInt($(this).data('page')) || 1;
        page++;
        $(this).data('page', page);
        fetchNews(page); // Load more results on pagination click
    });
});
