$(document).ready(function() {

    // dropdown menus
    $('.ui.dropdown')
        .dropdown()
    ;

    // sidebar
    /*
     var $sidebar = $('#backend-admin-sidebar');
     $sidebar.sidebar({
     transition: 'overlay',
     dimPage: false,
     onVisible: function() {
     $('body').addClass('sidebar');
     },
     onHide: function() {
     $('body').removeClass('sidebar');
     $('body').addClass('sidebar-hidden');
     },
     onHidden: function() {
     $('body').removeClass('sidebar-hidden');
     }
     });

    // sidebar toggle
    $('#backend-admin-sidebar-toggle').click(function() {
        $sidebar.sidebar('toggle');
    });
     */

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


    // tinymce wysiwyg editor
    $('.tinymce').tinymce({
        plugins: 'image link lists code table media paste wordcount',
        content_css: '<?= $this->Url->build('/'); ?>backend/css/admin.tinymce.css',
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

    $('.tinymce-default').tinymce({
        content_css: '<?= $this->Url->build('/'); ?>backend/css/admin.tinymce.css'
    });
});
