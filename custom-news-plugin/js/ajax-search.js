jQuery(document).ready(function($){
    $('#search-form').submit(function(event){
        event.preventDefault();
        var search_term = $('#search-input').val();
        $.ajax({
            url: ajax_search_params.ajax_url,
            type: 'post',
            data: {
                action: 'ajax_search',
                search_term: search_term
            },
            success: function(response) {
                $('#search-results').html(response);
            }
        });
    });

    $('#category-filter').change(function(){
        var category = $(this).val();
        $.ajax({
            url: ajax_search_params.ajax_url,
            type: 'post',
            data: {
                action: 'category_filter',
                category: category
            },
            success: function(response) {
                $('#category-results').html(response);
            }
        });
    });

    $(document).on('click', '.news-post', function(){
        var post_id = $(this).data('id');
        $.ajax({
            url: ajax_search_params.ajax_url,
            type: 'post',
            data: {
                action: 'load_popup_content',
                post_id: post_id
            },
            success: function(response) {
                $('#popup-content').html(response);
                $('#popup').show();
            }
        });
    });

    $(document).on('click', '#popup-close', function(){
        $('#popup').hide();
    });
});
