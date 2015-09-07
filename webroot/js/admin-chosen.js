$(document).ready(function() {
    // Chosen
    $('select').each(function() {
        // explicitly do not use chosen
        if ($(this).hasClass('nochosen') || $(this).hasClass('no-pretty'))
            return;

        var allowSingleDeselect = $(this).data('chosenAllowSingleDeselect');
        var disableSearchThreshold = $(this).data('chosenDisableSearchThreshold');

        var chosen = {
            allow_single_deselect: (allowSingleDeselect == true) ? allowSingleDeselect : false,
            disable_search_threshold: (disableSearchThreshold > 0) ? disableSearchThreshold : null
        };

        $(this).chosen(chosen);
    });
});