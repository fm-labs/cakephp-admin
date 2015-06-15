$(document).ready(function() {
    // flash messages
    $('.message .close').on('click', function() {
        $(this).closest('.message').fadeOut();
    });
});