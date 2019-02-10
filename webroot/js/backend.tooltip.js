/**
 * Backend Tooltip handler
 *
 * Attaches Bootstrap tooltip feature to items with [data-toggle="tooltip"] attribute
 */
(function( $ ) {

    Backend.Renderer.addListener('docready', function(scope) {
        $(scope).find('[data-toggle="tooltip"]:not(.tooltip-html)').tooltip({
            container: 'body'
        });
        $(scope).find('[data-toggle="tooltip"].tooltip-html').tooltip({
            container: 'body',
            html: true
        });
    });

}( jQuery, Backend ));
