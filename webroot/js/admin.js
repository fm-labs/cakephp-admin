$(document).ready(function() {

    // dropdown menus
    $('.ui.dropdown')
        .dropdown()
    ;

    // flash messages
    $('.message .close').on('click', function() {
        $(this).closest('.message').fadeOut();
    });

    // pickadate datepicker
    $('.datepicker').pickadate({
        // Escape any “rule” characters with an exclamation mark (!).
        format: 'yyyy-mm-dd',
        formatSubmit: 'yyyy-mm-dd',
        hiddenPrefix: 'pickadate__',
        hiddenSuffix: undefined
    });

    // pickadate timepicker
    $('.timepicker').pickatime({
        // Escape any “rule” characters with an exclamation mark (!).
        format: 'h:i a',
        formatLabel: '<b>h</b>:i <!i>a</!i>',
        formatSubmit: 'HH:ii',
        hiddenPrefix: 'pickatime__',
        hiddenSuffix: undefined
    });

    // imagepicker

    $('.imagepicker').imagepicker({
        show_label: true,
        initialized: function() {
            $(this)[0].picker.find('img.image_picker_image').each(function() {
                var $label = $(this).next('p');
                if ($label.length > 0) {
                    $(this).attr('title', $label.html());
                }
            });

        }
        //clicked: function() { "clicked" },
        //changed: function() { "changed" }
    });
    //$('.imagepicker.multi').imagepicker({ show_label: true });


    /*
    $('.image_picker_selector .thumbnail').on('mouseover', function(e) {
       var $label = $(this).find('p').first();
       var $img = $(this).find('.image_picker_image').first();
       $label.show();
    });
    $('.imageselect .image_picker_selector .thumbnail img.image_picker_image').popup({
        popup: 'p',
        inline: true
    });
     */

    // tabs (depends on semantic ui)
    $('.be-tabs .menu .item')
        .tab()
    ;
    $('.be-tabs .menu .item:first-child').addClass('active');
    $('.be-tabs .tabs .tab:first-child').addClass('active');

});
