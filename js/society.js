window.Society = (function ( window, document, $, undefined ) {

	'use strict';

    var app = {};

    // Initialize the app
    app.init = function () {

        $('body').on( 'click', '#post-status', app.postStatus );

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

    $( document ).ready( app.init );

    return app;

})( window, document, jQuery );