$(document).ready(function() {

    //
    // General (depends on semantic ui)
    //

    // dropdown menus
    $('.ui.dropdown')
        .dropdown()
    ;

    // form
    $('.ui.form select.dropdown')
        .dropdown()
    ;

    $('.ui.form select:not(.nochosen)').addClass('chosen');

    $('form fieldset > legend').on('click', function() {
       $(this).parent().toggleClass('collapsed');
    });


    // flash messages
    $('.message .close').on('click', function() {
        $(this).closest('.message').fadeOut();
    });

    // actions
    $('.actions a.icon.view').html($('<i>', {'class': 'eye icon'}));
    $('.actions a.icon.edit').html($('<i>', {'class': 'edit icon'}));
    $('.actions a.icon.delete').html($('<i>', {'class': 'trash icon'}));
    $('.actions a.icon.copy').html($('<i>', {'class': 'copy icon'}));

    //
    // Chosen SelectBox
    // @see http://harvesthq.github.io/chosen
    //
    $('select.chosen').each(function() {
        // explicitly do not use chosen
        //if ($(this).hasClass('nochosen') || $(this).hasClass('no-pretty'))
        //    return;

        var chosen = $(this).data('chosen') || {};

        chosen.width = (chosen.width !== undefined)
            ? chosen.width : "100%";
        chosen.search_contains = (chosen.search_contains !== undefined)
            ? chosen.search_contains : true;
        chosen.inherit_select_classes = (chosen.inherit_select_classes !== undefined)
            ? chosen.inherit_select_classes : true;

        $(this).chosen(chosen);
    });


    //
    // Tinymce wysiwyg HTML editor
    //

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


    //
    // Pickadate Time and datepicker
    //
    //$('input.datepicker').after($('<i>', {'class': 'calendar icon'}));

    // pickadate datepicker
    $('input.datepicker').pickadate({
        // Escape any “rule” characters with an exclamation mark (!).
        format: 'yyyy-mm-dd',
        formatSubmit: 'yyyy-mm-dd',
        hiddenPrefix: 'pickadate__',
        hiddenSuffix: undefined
    });

    // pickadate timepicker
    $('input.timepicker').pickatime({
        // Escape any “rule” characters with an exclamation mark (!).
        format: 'h:i a',
        formatLabel: '<b>h</b>:i <!i>a</!i>',
        formatSubmit: 'HH:ii',
        hiddenPrefix: 'pickatime__',
        hiddenSuffix: undefined
    });

    //
    // imagepicker
    //

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


    //
    // Tabs (depends on semantic ui)
    //

    //$('.be-tabs .menu .item:first-child').addClass('active');
    //$('.be-tabs .tabs .tab:first-child').addClass('active');
    //$('.be-tabs .menu .item').tab();
    //$('.be-tabs .menu .item:first-child').trigger('click');
    //$('.be-tabs .tabs .tab:first-child').addClass('loading');
    $('.be-tabs .menu .item').tab({
        //history: true,
        evaluateScripts: true,
        onFirstLoad : function(tabPath,parameterArray, historyEvent){
            var $tabMenuItem = $('#'+tabPath+'-menu');
            var url = $tabMenuItem.data('url');
            if(url && !$tabMenuItem.hasClass('tab-loaded')){
                console.log("loading tab " + url);
                var _this = $(this);
                $(this).addClass('loading');
                $.get(url, function(response){
                    _this.html(response);
                    _this.removeClass('loading');
                    $tabMenuItem.addClass('tab-loaded');
                });
            }
        }
    });
    $('.be-tabs .menu .item:first-child').trigger('click');



    //
    // Modals (depends on semantic ui)
    //

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


    // Top Affix
    var $top = $('#top');
    var $topClone = $top.clone();
    var topAffixOffset = 150;
    $topClone.attr('id', 'top-fixed');
    $topClone.appendTo('body');

    $(window).scroll(function(e) {

        var pageY = e.originalEvent.pageY;
        //console.log("Y: " + pageY);

        if (pageY > topAffixOffset && !$('body').hasClass('fixed-nav')) {
            $('body').addClass('fixed-nav');
        } else if (pageY < topAffixOffset && $('body').hasClass('fixed-nav')) {
            $('body').removeClass('fixed-nav');
        }

    });


});
