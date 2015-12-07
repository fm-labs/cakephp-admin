$(document).ready(function() {

    // Chosen SelectBox
    /*
    $('select.chosen-select').each(function() {
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
    */


    /*
    // Tinymce wysiwyg HTML editor (default)
    $('textarea.htmleditor').tinymce({
        plugins: 'image link lists code table media paste wordcount',
        content_css: _backend.rootUrl + 'backend/css/admin.tinymce.css',
        menubar: false,
        toolbar: [
            "formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | blockquote | code",
            "undo redo | cut copy paste | link image media | table"
        ]
    });
    */

    // Tinymce wysiwyg HTML text (default)
    $('textarea.htmltext').tinymce({
        plugins: 'link code paste wordcount',
        content_css: _backend.rootUrl + 'backend/css/admin.tinymce.css',
        menubar: false,
        toolbar: [
            "formatselect | bold italic underline strikethrough | code | undo redo | cut copy paste | link"
        ]
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

    /*
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
    */


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

    $('a.iframe-modal').on('click', function(e) {
        e.preventDefault();

        var href = $(this).data('iframe-url');
        if (typeof(href) === 'undefined') {
            var href = $(this).attr('href');
            if (href.indexOf('?') < 0) {
                href = href + '?iframe=1';
            } else {
                href = href + '&iframe=1';
            }
        }

        var $iframe = $('<iframe>', {'src': href, 'class': 'imodal', 'style': 'width: 100%; min-height: 500px;'});

        var $modal = $('<div>', {'class': 'ui fullscreen long modal'});

        $('<i>', {'class': 'close icon'})
            .appendTo($modal)

        $('<div>', {'class': 'header'}).html(href)
            .appendTo($modal);

        $('<div>', {'class': 'content'}).html($iframe)
            .appendTo($modal);

        $('<div>', {'class': 'actions'})
            .append($('<div>', {'class': 'ui green basic approve button'}).html('OK'))
            .appendTo($modal);

        console.log("open iframe modal: " + href);
        $modal
            .modal({
                'onVisible': function() {
                    console.log("onVisisble");
                    $iframe.width($modal.width * 0.9);
                },
                'onHide': function() {
                    console.log("onHide");
                },
                'onApprove': function() {
                    window.location.reload();
                }
            })
            .modal('show');
    });

});