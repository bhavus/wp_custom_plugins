jQuery(document).ready(function($) {
   // Handle the modal open
   $('#openModal').on('click', function() {
       $('#modalTitle').text('Create Post');
       $('#postID').val('');
       $('#postTitle').val('');
       $('#postContent').val('');
       $('#product_price').val('');
       $('#postThumbnail').val('');
       $('#postModal').modal('show');
   });

   // Handle the form submission for Create and Update
   $('#savePost').on('click', function() {
       var postID = $('#postID').val();
       var postTitle = $('#postTitle').val();
       var postContent = $('#postContent').val();
       var product_price = $('#product_price').val();
       var postThumbnail = $('#postThumbnail')[0].files[0];
       

       $.ajax({
           type: 'POST',
           url: ajax_object.ajax_url,
           data: {
               action: 'save_post',
               postID: postID,
               postTitle: postTitle,
               postContent: postContent,
               product_price: product_price,
               postThumbnail: postThumbnail,

           },
          
           success: function(response) {
               if (response === 'success') {
                   $('#postModal').modal('hide');
                   location.reload();
               } else {
                   alert('Failed to save post.');
               }
           }
       });
   });

   // Handle the Edit button click
   $('.edit-post').on('click', function(e) {
       e.preventDefault();
       var postID = $(this).data('id');
       var postTitle = $(this).data('title');
       var postContent = $(this).data('content');
       var product_price = $(this).data('product_price');
      
       $('#modalTitle').text('Edit Post');
       $('#postID').val(postID);
       $('#postTitle').val(postTitle);
       $('#postContent').val(postContent);
       $('#product_price').val(product_price);
       $('#postThumbnail').val('');
       $('#postModal').modal('show');
   });

   // Handle the Delete button click
   $('.delete-post').on('click', function(e) {
       e.preventDefault();
       var postID = $(this).data('id');
       if (confirm('Are you sure you want to delete this post?')) {
           $.ajax({
               type: 'POST',
               url: ajax_object.ajax_url,
               data: {
                   action: 'delete_post',
                   postID: postID
               },
               success: function(response) {
                   if (response === 'success') {
                       location.reload();
                   } else {
                       alert('Failed to delete post.');
                   }
               }
           });
       }
   });
});
