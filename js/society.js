window.Society = (function ( window, document, $, undefined ) {

	'use strict';

    var app = {};

    // Initialize the app
    app.init = function () {

        $('body').on( 'click', '#status-bar', app.showStatusBox );
        $('body').on( 'click', '.close-box', app.hideStatusBox );
        $('body').on( 'click', '#post-status', app.postStatus );
        $('body').on( 'click', '.action a', app.doAction );
        $('body').on( 'change', '#image-selector', app.previewImage );
        $('body').on( 'keyup', '#comment-entry textarea', app.addComment );

    };

    app.postStatus = function( click_event ) {

        click_event.preventDefault();

        $.ajax({
            type: 'POST',
            url: society.ajax_url,
            data: $('#status-box').serialize(),
            dataType: 'json',
            xhrFields: {
                onprogress: function (e) {
                    if ( e.lengthComputable ) {
                        $('#upload-percentage').text( e.loaded / e.total * 100 + '%' );
                    }
                }
            },
            success: app.handlePostStatus
        });

    }

    app.handlePostStatus = function( response ) {
        
        if ( response.success ) {
            location.reload();
        }

    }

    app.doAction = function( click_event ) {

        click_event.preventDefault();

        if ( $(this).hasClass('add-picture') ) {
            $('#image-selector').click();
        }

    }

    app.previewImage = function() {
        
         if (this.files && this.files[0]) {
            
            var reader = new FileReader();
            reader.onload = app.imageIsLoaded;
            reader.readAsDataURL( this.files[0] );
            
            $('input[name="image[name]"]').val( this.files[0].name );
            $('input[name="image[type]"]').val( this.files[0].type );
            $('input[name="image[modified]"]').val( this.files[0].lastModified );

        } 

    }

    app.imageIsLoaded = function( image_event ) {
        $('#image-preview').html('');
        $('#image-preview').append('<img src="' + image_event.target.result + '" alt="preview" />');
        $('input[name="image[data]"]').val( image_event.target.result );
    }

    app.showStatusBox = function( click_event ) {

        click_event.preventDefault();

        $('#status-box').show();

    }

    app.hideStatusBox = function( click_event ) {

        click_event.preventDefault();

        $('#status-box').hide();
        
    }

    app.addComment = function( keyup_event ) {

        if( keyup_event.keyCode == 13){
            
            $('#comment-entry').before( '<div class="comment">' + $(this).val() + '</div>' );
            $(this).val('');

        }

    }

    $( document ).ready( app.init );

    return app;

})( window, document, jQuery );