$(document).ready(function() {


    // #################################
    // # MASTER NAV #
    // #################################

    var $nav = $('#header-nav');


    $(document).on('click','#header-nav .nav a:not(.link-master)', function (ev) {
        //$('#header-nav .nav a').on('click', function (ev) {
        ev.preventDefault();
        ev.stopPropagation();
        //console.log("header nav link clicked: " + this.href);
        //window.location.href = this.href;
        //Backend.openLink(this, ev);
        Backend.openInNewTab(this);
        return false;
    });


    // #################################
    // # MASTER TABS #
    // #################################

    var $tabs = $('#master-tabs');

    //
    // Tabs (bootstrap)
    //
    $(document).on('click', '#master-tabs .nav a', function (e) {

        //console.log("Master tab nav item clicked: " + this.hash);

        e.preventDefault();
        var url = $(this).attr("data-url");

        if (typeof url !== "undefined" && !$(this).hasClass('tab-ajax-loaded')) {

            var _this = $(this);
            var pane = $(this), target = this.hash;

            var tabId = $(this).parent().data('tabId');

            // ajax load from data-url
            //if(url) {
            //    url += (url.match(/\?/) ? '&' : '?') + 'iframe=1';
            //}

            //var $iframeLoader = $('#loader').clone().removeAttr('id').show();
            var $iframe = $('<iframe>', { id: 'frame' + tabId, 'class': 'tab-frame' });
            $(target).html($iframe);
            $(window).trigger('resize'); //@TODO only resize the new frame

            $(this).addClass('tab-loading');
            $(this).tab('show');

            // @TODO Keep this try (see dblclick event)
            $iframe.on('load', function() {
                //console.log("iframe loaded " + url);
                Backend.Loader.hide();
                Backend.setActiveTab(tabId, this.contentWindow);
                //$iframeLoader.remove();
                _this.removeClass('tab-loading');
                _this.addClass('tab-ajax-loaded');
                _this.html(this.contentWindow.document.title);
                _this.attr('title', this.contentWindow.document.title);
                _this.attr('data-url', this.contentWindow.location);
            });

            $iframe.attr('src', url);
            Backend.Loader.show();

        } else {

            $(this).tab('show');
        }


        e.preventDefault();
        return false;
    });

    $(document).on('dblclick','#master-tabs .nav a', function (e) {

        e.preventDefault();
        var url = $(this).attr("data-url");

        if (typeof url !== "undefined") {

            //console.log("Reloading Ajax Tab with URL " + url);

            var tabId = $(this).parent().data('tabId');
            if (!tabId) {
                console.error("Tab reload failed: TabId missing");
            }

            $(this).addClass('tab-loading');
            $(this).tab('show');

            var $iframe = $('#' + tabId).find('iframe.tab-frame').first();
            $iframe.attr('src', url);
            Backend.Loader.show();
        }

    });


    $(document).on('show.bs.tab', '#master-tabs .nav a', function (e) {
        //Backend.Loader.show();
    });

    $(document).on('shown.bs.tab', '#master-tabs .nav a', function (e) {
        //Backend.Loader.hide();
        //document.title = $(this).attr('title') || $(this).text();
        Backend.setActiveTab($(this).data('tabId'));
    });


    $(document).on('click', '#master-tabs .nav .tab-close', function(e) {
        e.preventDefault();

        var tabId = $(this).parent().data('tabId');
        Backend.closeTab(tabId);

        $('#' + tabId).fadeOut().remove();
        $(this).parent().fadeOut().remove();
    });

    $(window).on('resize', function(e) {

        console.log("resize");

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

        Backend.Master.parsePostMessage(data, origin, source);
    });

    $('#master-tabs .nav a').first().trigger('click');

    // clear alerts
    setTimeout(function() {
        $('.alert').each(function() {
            $(this).slideUp(1000, function() { $(this).remove(); });
        });
    }, 10000);

    // reposition frames every 60 sec
    setInterval(function() {
        $(window).trigger('resize');
    }, 60000);

    Backend.ready();
});