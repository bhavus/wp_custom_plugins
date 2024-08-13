jQuery(document).ready(function($) {
    function fetchProducts(page = 1) {
        var search_term = $('#search-term').val();
        var category_id = $('#category-filter').val();
        var orderby = $('#orderby').val();

        $.ajax({
            url: ajax_obj_params.ajax_url,
            type: 'post',
            data: {
                action: 'ajax_products_search',
                nonce: ajax_obj_params.nonce,
                search_term: search_term,
                category_id: category_id,
                orderby: orderby,
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

                    if (results.length === 10) {
                        $('#pagination').html('<button id="load-more">Load More</button>');
                    } else {
                        $('#pagination').empty();
                    }
                }
            }
        });
    }

    $('#search-term').on('keyup', function() {
        fetchProducts();
    });

    $('#category-filter').on('change', function() {
        fetchProducts();
    });

    $('#orderby').on('change', function() {
        fetchProducts();
    });

    $('#pagination').on('click', '#load-more', function(e) {
        e.preventDefault();
        var page = parseInt($(this).data('page')) || 1;
        page++;
        $(this).data('page', page);
        fetchProducts(page);
    });
});
