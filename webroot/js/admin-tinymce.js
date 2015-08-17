$(document).ready(function() {

    // tinymce wysiwyg editor
    $('.tinymce').tinymce({
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


    $('.tinymce-default').tinymce({
        content_css: _backend.rootUrl + 'backend/css/admin.tinymce.css'
    });
});