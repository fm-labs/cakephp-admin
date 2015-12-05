$(document).ready(function() {

    // dropdown menus
    $('.ui.dropdown')
        .dropdown()
    ;

    // form
    $('.ui.form select.dropdown')
        .dropdown()
    ;

    $('form fieldset > legend').on('click', function() {
       $(this).parent().toggleClass('collapsed');
    });


    // flash messages
    $('.message .close').on('click', function() {
        $(this).closest('.message').fadeOut();
    });

    // tabs (depends on semantic ui)
    $('.be-tabs .menu .item')
        .tab()
    ;
    $('.be-tabs .menu .item:first-child').addClass('active');
    $('.be-tabs .tabs .tab:first-child').addClass('active');

});
