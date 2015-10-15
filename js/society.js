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

        } 

    }

    app.imageIsLoaded = function( image_evenet ) {
        $('#image-preview').html('');
        $('#image-preview').append('<img src="' + image_evenet.target.result + '" alt="preview" />');
        $('input[name="image"]').val( image_evenet.target.result );
    }

    $( document ).ready( app.init );

    return app;

})( window, document, jQuery );