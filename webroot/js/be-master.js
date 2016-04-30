var domIdCounter = 0;
window.uniqueDomId = function(prefix){
    if (prefix === undefined) {
        prefix = 'domid';
    }
    return prefix + domIdCounter++
}

function addTab(title, url)
{
    var $tabList = $('#master-tab-list');
    var $tabContent = $('#master-tab-content');

    // create tab nav item

    var tabId = uniqueDomId('tab');

    var $navLink = $('<a>', {
        'role': 'tab',
        'aria-controls': tabId,
        'data-toggle': 'tab',
        'data-url': url,
        'href': '#' + tabId,
        'title': title,
    })
        .html(title);

    var $navItem = $('<li>', { role: 'presentation', 'data-tab-id': tabId })
        .append($navLink)
        .append($('<span>', {'class': 'tab-close'}).html('x'))
        .appendTo($tabList)

    var $contentItem = $('<div>', {
        'class': 'tab-pane',
        'role': 'tabpanel',
        'id': tabId
    }).appendTo($tabContent);

    $navLink.trigger('click');
}

function parsePostMessage(msg, origin, source) {

    var parsed;
    try {
        parsed = JSON.parse(msg);
    } catch (ex) {
        console.log(ex);
        return;
    }

    var type = parsed.type;
    var data = parsed.data;

    switch(type) {

        case "ready":
            console.log("[master] Page ready: " + data.url);
            break;

        case "open":
            addTab(data.title, data.url);
            break;

        default:
            console.log("Unknown message type: " + parsed.type);
            return;
    }


}

$(document).ready(function() {

    var $tabs = $('#master-tabs');

    $("a[data-icon]").each(function() {
        console.log("found link with icon " + $(this).data('icon'));
        $(this).prepend("&nbsp;").prepend($('<i>', { 'class': 'fa fa-'+$(this).data('icon')}));
    });

   // capture all links
    $(document).on('click', 'a:not(.link-master)', function(e) {

        if ($.contains(document.getElementById('master-tab-list'), $(this))) {
            //console.log("Skip: Is Master Tab Nav Item");
            return;
        }

        var title = $(this).attr('title') || $(this).text();
        var href = $(this).attr('href');
        var hash = this.hash;

        //console.log("Open link (" + hash + ") " + href);

        // ignore invalid links
        if (href === undefined) {
            return
        }

        // ignore anchor links
        if (href.indexOf('#') === 0) {
            //console.log("Skip hash link");
            return;
        }

        e.preventDefault();
        addTab(title, href);
    });

    //
    // Tabs (bootstrap)
    //
    $(document).on('click','#master-tabs .nav a', function (e) {

        e.preventDefault();
        var url = $(this).attr("data-url");

        if (typeof url !== "undefined") {

            if ($(this).hasClass('tab-ajax-loaded')) {
                $(this).tab('show');
                return;
            }

            var _this = $(this);
            var pane = $(this), target = this.hash;


            // ajax load from data-url
            //if(url) {
            //    url += (url.match(/\?/) ? '&' : '?') + 'iframe=1';
            //}

            var $iframe = $('<iframe>', { 'class': 'tab-frame' });
            $(target).html($iframe);
            $(window).trigger('resize'); //@TODO only resize the new frame
            $iframe.on('load', function() {
                _this.addClass('tab-ajax-loaded');
                _this.html(this.contentWindow.document.title);
                _this.attr('data-url', this.contentWindow.location)
            });
            $iframe.attr('src', url);

        } else {
            $(this).tab('show');
        }
    });

    $(document).on('dblclick','#master-tabs .nav a', function (e) {

        e.preventDefault();
        var url = $(this).attr("data-url");

        if (typeof url !== "undefined") {

            var tabId = $(this).parent().data('tabId');
            $('#' + tabId).find('iframe.tab-frame').each(function() {
                $(this).attr('src', url);
            });
        }

    });

    $(document).on('shown.bs.tab','#master-tabs .nav a', function (e) {
        document.title = $(this).attr('title') || $(this).text();
    });


    $(document).on('click', '#master-tabs .nav .tab-close', function(e) {
        e.preventDefault();

        var tabId = $(this).parent().data('tabId');

        $('#' + tabId).fadeOut().remove();
        $(this).parent().fadeOut().remove();
    });

    $(window).on('resize', function(e) {

        $('#master-tab-content iframe').each(function() {

            var $frame = $(this);

            var frameOffset = $frame.offset();
            var frameH = $(window).height() - frameOffset.top - 10;
            var frameW = $tabs.width() - frameOffset.left;

            $frame.height(frameH).width(frameW);
        });

    });

    // listen for post messages
    $(window).on('message', function(event) {

        console.log(event);

        var hostUrl = window.location.protocol + "//" + window.location.host
        var origin = event.origin || event.originalEvent.origin; // For Chrome, the origin property is in the event.originalEvent object.
        if (origin !== hostUrl) {
            console.log("message not allowed from " + origin + ". Expects " + hostUrl);
            return;
        }

        var data = event.data || event.originalEvent.data;
        var source = event.source || event.originalEvent.source;

        console.log("[master] received message: " + data);

        parsePostMessage(data, origin, source);
    });

});