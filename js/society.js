window.Society = (function ( window, document, $, undefined ) {

	'use strict';

    var app = {};

    // Initialize the app
    app.init = function () {

        $('body').on( 'click', '#post-status', app.postStatus );
        $('body').on( 'click', '.action a', app.doAction );
        $('body').on( 'change', '#image-selector', app.previewImage );

    };

    app.postStatus = function( click_event ) {

        click_event.preventDefault();

        $.post( society.ajax_url, $('#status-box').serialize(), app.handlePostStatus, 'json' );

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

        } 

    }

    app.imageIsLoaded = function( image_event ) {
        $('#image-preview').html('');
        $('#image-preview').append('<img src="' + image_event.target.result + '" alt="preview" />');
        $('input[name="image[data]"]').val( image_event.target.result );
    }

    $( document ).ready( app.init );

    return app;

})( window, document, jQuery );