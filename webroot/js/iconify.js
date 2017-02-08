/**
 * Simple "Iconifyer"
 *
 * Scan for elements with following css selectors and inject a font-awesome icon
 * - [data-icon]
 *
 * @todo Move backend js hooks to separate location
 */
(function( $ ) {

    $.fn.iconify = function() {

        this.filter( "[data-icon]" ).each(function() {
            var icon = $( this).data('icon');

            if (icon) {
                $(this).prepend('<i class="fa fa-' + icon + '"></i>');
            }
        });

        return this;

    };

}( jQuery ));

Backend.Renderer.addListener('docready', function(scope) {

    console.log("Iconify", scope);
    $(scope).iconify();

});