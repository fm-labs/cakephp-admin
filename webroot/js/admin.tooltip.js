/**
 * AdminJs Tooltip handler
 *
 * Attaches Bootstrap tooltip feature to items with [data-toggle="tooltip"] attribute
 */
(function( $ ) {

    AdminJs.Renderer.addListener('docready', function(scope) {
        // Tooltip
        $(scope).find('[data-toggle="tooltip"]').tooltip({
            //selector: '[data-toggle="tooltip"]',
            container: 'body'
        });

        // Tooltip with Html
        $(scope).find('[data-toggle="tooltip-html"]').tooltip({
            //selector: '[data-toggle="tooltip-html"]',
            container: 'body',
            html: true,
            title: function () {
                if ($(this).next('.toggle-content-html').length > 0) {
                    return $(this).next('.toggle-content-html').html();
                }

                return '';
            }
        });

        // Popover
        $(scope).find('[data-toggle="popover"]').popover({
            //selector: '[rel="popover"]:not(.popover-html)',
            html: false,
            trigger: 'hover',
            container: 'body'
        });

        // Popover with HTML
        $(scope).find('[data-toggle="popover-html"]').popover({
            //selector: '.popover-html[rel="popover-html"]',
            //trigger: 'click',
            container: 'body',
            html: true,
            content: function () {
                if ($(this).next('.toggle-content-html').length > 0) {
                    return $(this).next('.toggle-content-html').html();
                }

                return '';
            }
        });

    });

}( jQuery, AdminJs ));
