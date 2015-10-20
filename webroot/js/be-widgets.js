$(document).ready(function() {

    // Chosen SelectBox
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

    // Tinymce wysiwyg HTML editor
    $('textarea.htmleditor').tinymce({
        plugins: 'image link lists code table media paste wordcount',
        content_css: _backend.rootUrl + 'backend/css/admin.tinymce.css',
        menu : { // this is the complete default configuration
            file   : {title : 'File'  , items : 'newdocument'},
            edit   : {title : 'Edit'  , items : 'undo redo | cut copy paste pastetext | selectall'},
            insert : {title : 'Insert', items : 'link media | template hr'},
            view   : {title : 'View'  , items : 'visualaid'},
            format : {title : 'Format', items : 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
            table  : {title : 'Table' , items : 'inserttable tableprops deletetable | cell row column'},
            tools  : {title : 'Tools' , items : 'spellchecker code'}
        },
        menubar: false,
        toolbar: [
            "formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | blockquote | code",
            "undo redo | cut copy paste | link image media | table"
        ]
    });


    $('textarea.htmltext').tinymce({
        content_css: _backend.rootUrl + 'backend/css/admin.tinymce.css',
        menubar: false
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

});