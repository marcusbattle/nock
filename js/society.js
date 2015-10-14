window.Society = (function ( window, document, $, undefined ) {

	'use strict';

    var app = {};

    // Initialize the app
    app.init = function () {

        $('body').on( 'click', '#post-status', app.postStatus );
        $('body').on( 'click', '.action a', app.doAction );

    };

    app.postStatus = function() {
        
        $.post( society.ajax_url, {
            action: 'post_status',
            status : $('#status-box textarea').val()
        }, app.handlePostStatus, 'json' );

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

    $( document ).ready( app.init );

    return app;

})( window, document, jQuery );