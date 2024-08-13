jQuery(document).ready(function($) {



    $('.js-filter select').on('change',function () {
        var cat = $('#cat').val()
        popularity = $('#popularity').val();
        var data = {
            action: 'filter_posts',
            cat: cat,
            popularity: popularity,
        }
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: data,
            success: function (response) {
                //alert(response);
                $('.js-movies').html(response);
            }
        })
    });


   



});


