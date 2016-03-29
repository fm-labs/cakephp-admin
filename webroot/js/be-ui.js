function ajaxify()
{

    // Tinymce wysiwyg HTML text (default)
    $('textarea.htmltext').tinymce({
        plugins: 'link code paste wordcount',
        content_css: _backend.rootUrl + 'backend/css/admin.tinymce.css',
        menubar: false,
        toolbar: [
            "formatselect | bold italic underline strikethrough | code | undo redo | cut copy paste | link"
        ]
    });

    // form fieldset toogle
    $('form fieldset > legend').on('click', function() {
        $(this).parent().toggleClass('collapsed');
    });
}

function initLoader()
{
    // Inject loading animation
    $('<div>', { id: 'loader' })
        .css({
            position: 'fixed',
            top: 0,
            right: 0,
            background: 'transparent',
            'z-index': 77777,
        })
        .html($('<i>', { 'class': 'fa fa-circle-o-notch fa-spin fa-3x'}))
        .hide()
        .appendTo($('body'));
}

function showLoader()
{
    $('#loader').show();
}

function hideLoader()
{
    $('#loader').hide();
}

function initAlertPane()
{
    $('<div>', {
        id: 'alert-pane',
    }).appendTo($('body'));
}

function alertSuccess(msg)
{
    var $pane = $('#alert-pane');

    var $el = $('<div>', {
        'class': 'alert alert-success'
    });

    $el.html(msg).appendTo('#alert-pane');

    setTimeout(function() {
        $el.fadeOut();
        $el.remove();
    }, 3000);
}

function ajaxLoadHtml(target, url, callback)
{
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'html',
        cache: false,
        beforeSend: function() {
            showLoader();
        },
        complete: function() {
            hideLoader();
        },
        success: function(result) {
            $(target).html(result);
            ajaxify();

            if (typeof callback === 'function') {
                callback();
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            alert("An error occured: " + textStatus + ": " + errorThrown);
        }
    })
}

function sendMessage(msg)
{
    // check if current window is a framed window
    var parent = window.parent || null;
    if (parent === window) {
        console.log("same window");
        return;
    }

    // convert json objects to json strings
    if (typeof msg === 'object') {
        msg = JSON.stringify(msg);
    }

    var hostUrl = window.location.protocol + "//" + window.location.host;
    parent.postMessage(msg, hostUrl);
}

function saveScrollTop(val)
{
    if (!!window.localStorage) {
        var scrollTop = window.localStorage.setItem('scrollTop', val);
    } else {
        console.log("LocalStorage is not available");
    }
}

function restoreScrollTop()
{
    if (!!window.localStorage) {
        var scrollTop = window.localStorage.getItem('scrollTop');
        if (scrollTop) {
            window.localStorage.removeItem('scrollTop');
            $(window).scrollTop(scrollTop);
        }

    }
}

function openLinkFrame(title, url)
{
    if (window.parent === window) {
        window.location.href = url;
        return;
    }

    console.log("Open link in new frame: " + this.href);
    sendMessage({
        type: 'open',
        data: {
            title: title,
            url: url
        }
    });
}

function openLinkModal(url)
{
    if (window.parent === window) {
        window.location.href = url;
        return;
    }

    console.log("Open Link in Modal (todo): " + url);
    window.location.href = url;
}

function openLinkModalFrame(url)
{
    console.log("Open Link in Modal Frame: " + url);

    if (window.parent === window) {
        window.location.href = url;
        return;
    }

    var dialogTemplate = '<div class="modal-dialog modal-lg" style="width: 95%;"> \
    <div class="modal-content"> \
    <div class="modal-header"> \
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> \
<h4 class="modal-title"></h4> \
</div> \
<div class="modal-body"> \
</div> \
<div class="modal-footer"> \
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> \
</div> \
</div><!-- /.modal-content --> \
</div><!-- /.modal-dialog -->';

    var modalId = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5);

    var $modal = $('<div>', {
        id: 'modal' + modalId,
        class: 'modal fade',
        tabIndex: -1,
        role: 'dialog'
    }).html(dialogTemplate);


    var $iframe = $('<iframe>', {
        class: 'modal-iframe',
        src: url,
        style: 'width: 100%; min-height: 500px;'
    });

    var _window = window;

    $modal.find('.modal-body').html($iframe);

    $modal.modal({})
    $modal.on('shown.bs.modal', function (e) {
        $iframe.width($modal.width * 0.9);
    });

    $modal.on('hidden.bs.modal', function (e) {
        saveScrollTop($(_window).scrollTop());
        _window.location.href = _window.location.href;
    });
}

$(document).ready(function() {

    initLoader();
    initAlertPane();
    ajaxify();
    restoreScrollTop();

    sendMessage({
        type: 'ready',
        data: {
            url: window.location.href,
            title: document.title
        }
    });

    //
    // Ajax Form Submission
    //
    $(document).on('submit', 'form:not(.no-ajax)', function(e) {
        e.preventDefault();

        var data = $(this).serialize();
        var action = $(this).attr('action');
        var method = $(this).attr('method') || 'POST';
        var _self = $(this);

        console.log("Submit form to " + action + " via " + method + " with data " + data);

        $.ajax({
            url: action,
            data: data,
            method: method,
            beforeSend: function() {
                showLoader();
            },
            complete: function() {
                hideLoader();
            },
            success: function(result) {

                //_self.parent().replaceWith(result);
                //ajaxify();

                alertSuccess('Success');

                //window.location.href = window.location.href;

            }
        });

    });

    //
    // Tabs (bootstrap)
    //
    $(document).on('click','.be-tabs .nav a', function (e) {

        e.preventDefault();
        var url = $(this).attr("data-url");

        if (typeof url !== "undefined") {
            var pane = $(this), target = this.hash;

            // ajax load from data-url
            ajaxLoadHtml(target, url, function() {
                pane.tab('show');
            })
        } else {
            $(this).tab('show');
        }
    });


    $(document).on('click','a.link-frame', function (e) {

        var title = $(this).attr('title') || $(this).text();
        var url = $(this).attr('href');

        openLinkFrame(title, url);

        e.preventDefault();
        return false;
    });

    $(document).on('click','a.link-modal', function (e) {

        openLinkModal(this.href);

        e.preventDefault();
        return false;
    });

    $(document).on('click','a.iframe-modal, a.link-modal-frame', function (e) {

        openLinkModalFrame(this.href);

        e.preventDefault();
        return false;
    });


    //
    // Top Affix
    //
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

    //
    // Pickadate Time and datepicker
    //
    //$('input.datepicker').after($('<i>', {'class': 'calendar icon'}));

    // pickadate datepicker
    /*
    $('input.datepicker').pickadate({
        // Escape any “rule” characters with an exclamation mark (!).
        format: 'yyyy-mm-dd',
        formatSubmit: 'yyyy-mm-dd',
        hiddenPrefix: 'pickadate__',
        hiddenSuffix: undefined
    });
    */

    // pickadate timepicker
    /*
    $('input.timepicker').pickatime({
        // Escape any “rule” characters with an exclamation mark (!).
        format: 'h:i a',
        formatLabel: '<b>h</b>:i <!i>a</!i>',
        formatSubmit: 'HH:ii',
        hiddenPrefix: 'pickatime__',
        hiddenSuffix: undefined
    });
    */

    //
    // General (semantic ui)
    //

    /*
    // dropdown menus
    $('.ui.dropdown')
        .dropdown()
    ;

    // form
    $('.ui.form select.dropdown')
        .dropdown()
    ;


     // flash messages
     $('.message .close').on('click', function() {
     $(this).closest('.message').fadeOut();
     });
    */

    //$('form select:not(.nochosen)').addClass('chosen');



    // actions
    $('.actions a.icon.view').html($('<i>', {'class': 'eye icon'}));
    $('.actions a.icon.edit').html($('<i>', {'class': 'edit icon'}));
    $('.actions a.icon.delete').html($('<i>', {'class': 'trash icon'}));
    $('.actions a.icon.copy').html($('<i>', {'class': 'copy icon'}));

    //
    // Chosen SelectBox
    // @see http://harvesthq.github.io/chosen
    //
    /*
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
    */


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
    // Tabs (semantic ui)
    //
    /*
    $('.be-tabs .menu .item').tab({
        //history: true,
        //evaluateScripts: true,
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
    */




    //
    // Modals (semantic ui)
    //

    /*
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
    */



});
