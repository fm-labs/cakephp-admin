/**
 * Simple "Iconifyer"
 *
 * Scan for elements with following css selectors and inject a font-awesome icon
 * - [data-icon]
 */
(function( $ ) {

    $.fn.iconify = function() {

        this.find( "[data-icon]" ).each(function() {
            var icon = $( this).data('icon');

            if (icon) {
                $(this).prepend('<i class="fa fa-' + icon + '"></i>');
            }
        });

        this.find( "[data-locale]" ).each(function() {
            var icon = $( this).data('locale');

            var map = {
                'en' : 'gb'
            };

            if (icon) {
                if (icon in map) {
                    icon = map[icon];
                }
                $(this).prepend('<span class="flag-icon flag-icon-' + icon + '"></span>&nbsp;');
            }
        });

        return this;

    };

}( jQuery ));

Backend.Renderer.addListener('docready', function(scope) {

    //console.log("Iconify", scope);
    $(scope).iconify();

});