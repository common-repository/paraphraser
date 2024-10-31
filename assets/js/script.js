jQuery(document).ready(function($) {
  jQuery(document).find(".editor-post-switch-to-plagiarism").click(function() {
    var text = jQuery(".is-desktop-preview").text();
      console.log(text);

      jQuery.ajax({
        url: fsbc_url.ajaxurl, // this is the object instantiated in wp_localize_script function
        type: 'POST',
        data:{ 
            action 				: "so_wp_ajax_function",
            nonce 				: fsbc_url.nonce,
        text: text, // this is the function in your functions.php that will be triggered
        },
        success: function( data ){
        //Do something with the result from server
        console.log( data );
        }
      });
  });
});