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

});
