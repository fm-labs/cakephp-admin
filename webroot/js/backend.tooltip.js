/**
 * Backend Tooltip handler
 *
 * Attaches Bootstrap tooltip feature to items with [data-toggle="tooltip"] attribute
 */
(function( $ ) {

    Backend.Renderer.addListener('docready', function(scope) {
        $(scope).find('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });
    });

}( jQuery, Backend ));
