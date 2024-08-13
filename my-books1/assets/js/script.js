jQuery(document).ready(function() {

    // Datatable Bootrap js
    jQuery('#my-book').DataTable();

    // Upload Book Image
    jQuery("#btn-upload").on("click", function() {
        var image = wp.media({
            title: "Upload Image for My Book",
            mulitple: false
        }).open().on("select", function() {
            var uploaded_image = image.state().get("selection").first();
            var getImage = uploaded_image.toJSON().url;
            jQuery("#show-image").html("<img src='" + getImage + "' style='height:50px;width:50px'/>")
            jQuery("#image_name").val(getImage);
        });
    });


    // Add Book 
    jQuery("#frmAddBook").validate({
        submitHandler: function() {
            var postdata = "action=mybooklibrary&param=save_book&" + jQuery("#frmAddBook").serialize();
            jQuery.post(mybookajaxurl, postdata, function(response) {
                var data = jQuery.parseJSON(response);
                if (data.status == 1) {
                    jQuery.notifyBar({
                        cssClass: "success",
                        html: data.message
                    });
                    setTimeout(function() {
                        //window.location.reload();
                        location.reload();
                    }, 1300)
                } else {

                }
            });
        }
    });

    // Edit Book
    jQuery("#frmEditBook").validate({
        submitHandler: function() {
            var postdata = "action=mybooklibrary&param=edit_book&" + jQuery("#frmEditBook").serialize();
            console.log(postdata);
            jQuery.post(mybookajaxurl, postdata, function(response) {
                var data = jQuery.parseJSON(response);
                if (data.status == 1) {
                    jQuery.notifyBar({
                        cssClass: "success",
                        html: data.message
                    });
                    setTimeout(function() {
                        //window.location.reload();
                        location.reload();
                    }, 1300)
                } else {

                }
            });
        }
    });

    // Delete Book
    jQuery(document).on("click", ".btnbookdelete", function() {
        var conf = confirm("Are you sure want to delete?");
        if (conf) { //if(true)
            var book_id = jQuery(this).attr("data-id");
            var postdata = "action=mybooklibrary&param=delete_book&id=" + book_id;
            jQuery.post(mybookajaxurl, postdata, function(response) {
                var data = jQuery.parseJSON(response);
                if (data.status == 1) {
                    jQuery.notifyBar({
                        cssClass: "success",
                        html: data.message
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 1300)
                } else {

                }
            });
        }
    });

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    // Add Student
    jQuery("#frmAddStudent").validate({
       submitHandler: function() {
            var postdata = "action=mybooklibrary&param=save_student&" + jQuery("#frmAddStudent").serialize();
            jQuery.post(mybookajaxurl, postdata, function(response) {
                var data = jQuery.parseJSON(response);
                if (data.status == 1) {
                    jQuery.notifyBar({
                        cssClass: "success",
                        html: data.message
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 1300)
                } else {

                }
            });
        }
    });

    // Delete Student
    jQuery(document).on("click", ".btnstudentdelete", function() {
        var conf = confirm("Are you sure want to delete?");
        if (conf) { //if(true)
            var student_id = jQuery(this).attr("data-id");
            var postdata = "action=mybooklibrary&param=delete_student&id=" + student_id;
            jQuery.post(mybookajaxurl, postdata, function(response) {
                var data = jQuery.parseJSON(response);
                if (data.status == 1) {
                    jQuery.notifyBar({
                        cssClass: "success",
                        html: data.message
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 1300)
                } else {

                }
            });
        }
    });

    // Add Author
    jQuery("#frmAddAuthor").validate({
       submitHandler: function() {
            var postdata = "action=mybooklibrary&param=save_author&" + jQuery("#frmAddAuthor").serialize();
            jQuery.post(mybookajaxurl, postdata, function(response) {
                var data = jQuery.parseJSON(response);
                if (data.status == 1) {
                    jQuery.notifyBar({
                        cssClass: "success",
                        html: data.message
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 1300)
                } else {

                }
            });
        }
   });


    // Delete Author
    jQuery(document).on("click", ".btnauthordelete", function() {
        var conf = confirm("Are you sure want to delete?");
        if (conf) { //if(true)
            var author_id = jQuery(this).attr("data-id");
            var postdata = "action=mybooklibrary&param=delete_author&id=" + author_id;
            jQuery.post(mybookajaxurl, postdata, function(response) {
                var data = jQuery.parseJSON(response);
                if (data.status == 1) {
                    jQuery.notifyBar({
                        cssClass: "success",
                        html: data.message
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 1300)
                } else {

                }
            });
        }
    });


   

});

  // Enrol Code
jQuery(document).on("click",".owt-enrol-btn",function(){
    
    console.log("Enrolled Successfully");
});
