var Backend = {

    _domIdCounter: 0,
    _tabCounter: 0,

    tabs: {},
    tabActive: null,

    /**
     * Generate unique domid
     * @param prefix
     * @returns string
     */
    uniqueDomId: function(prefix){
        if (prefix === undefined) {
            prefix = 'dom';
        }
        return prefix + this._domIdCounter++
    },

    log: function(msg)
    {
        console.log(msg);
    },

    ready: function()
    {
        this.log("Backend ready event triggered. Location: " + window.location.href);
        this.log("Window is Iframe: " + this.isFrame());
        this.log(window);

        var _this = this;

        // catch frame links
        $(document).on('click','a.link-frame', function (e) {

            var title = $(this).attr('title') || $(this).text();
            var url = $(this).attr('href');

            console.log("Clicked frame link " + url);
            _this.Link.openFrame(title, url);

            e.preventDefault();
            return false;
        });

        // catch modal links
        $(document).on('click','a.link-modal', function (e) {

            _this.Link.openModal(this.href);

            e.preventDefault();
            return false;
        });

        // catch modal frame links
        $(document).on('click','a.link-frame-modal', function (e) {

            _this.Link.openModalFrame(this.href, {
                title: this.title || this.innerText
            });

            e.preventDefault();
            return false;
        });

        // status label toggle
        $(document).on('click', '.label[data-toggle-url]', function(e) {
            console.log("toggle status label with toggle-url " + $(this).data('toggleUrl'));

            var _this = $(this);
            var url = $(this).data('toggleUrl');

            if (url !== undefined) {

                $.ajax({
                    url: url,
                    type: 'json',
                    method: 'GET',
                    beforeSend: function() {
                        _this.addClass('loading');
                    },
                    complete: function() {
                        _this.removeClass('loading');
                    },
                    error: function() {
                        _this.addClass('loading-error');
                    },
                    success: function(json) {

                        console.log(json);

                        if (typeof json !== 'object') {
                            Backend.Flash.error('Toggle request failed: Expected JSON response');
                            return;
                        }

                        if (json.result === -1) {
                            Backend.Flash.error('Toggle request failed: Server error');
                            return;
                        }

                        var newVal = json.new;
                        var newLabel = json.label;
                        var newClass = json.class;

                        _this
                            .html(newLabel)
                            .attr('class', 'label label-' + newClass);
                    }
                })
            }


        });

        this.beautify();

        Backend.Util.restoreScrollPosition(null);

        if (this.isFrame()) {

            this.Frame.sendMessage({
                type: 'hello',
                data: {
                    from: this.getWindowId()
                }
            });
        }

    },

    getWindowId: function() {
        return window._id || 'No Id';
    },

    isFrame: function()
    {

        var parent = window.parent || window;
        return parent !== window;

    },

    /**
     * Apply 'beautification' rules on the whole DOM
     * Intended usage after AJAX loaded content is injected into the DOM
     */
    beautify: function()
    {
        // icon links
        $("a[data-icon]").each(function() {
           //console.log("link " + this.href + " has icon " + $(this).data('icon'));

            var $ico = $('<i>', { class: 'fa fa-' + $(this).data('icon') }).html("");
            $(this).prepend($ico.prop('outerHTML') + "&nbsp");
        });

        // Backend Tabs: Auto-enable first tab
        $('.be-tabs a').first().trigger('click');
    },

    // not in use
    openLink: function(link, ev)
    {

        /*
        if ($.contains(document.getElementById('master-tab-list'), $(link))) {
            console.log("Skip: Is Master Tab Nav Item");
            return;
        }
        */

        var title = $(link).attr('title') || $(link).text();
        //var href = $(link).attr('href');
        var href = link.href;
        var hash = link.hash;
        var target = link.target;

        console.log("Open link (" + hash + ") " + href + " in target " + target );

        // ignore invalid links
        if (href === undefined) {
            return false;
        }

        // ignore anchor links
        if (href.indexOf('#') === 0) {
            //console.log("Skip hash link");
            return false;
        }

        /*
        if (target === "_top") {
            window.top.location.href = href;
            return false;
        } else if (target === "_parent") {
            window.parent.location.href = href;
            return false;
        } else if (target === "_self") {
            window.location.href = href;
            return false;
        }
        */

        console.log("add tab for " + href + " (" + target + ")");
        //this.addTab(title, href, target);

        ev.stopPropagation();
        ev.preventDefault();
        return false;
    },

    openInNewTab: function(link)
    {

        var title = $(link).attr('title') || $(link).text();
        //var href = $(link).attr('href');
        var href = link.href;
        var hash = link.hash;
        var target = link.target || '_self';

        console.log("Open link in new tab " + href + " (" + hash + ") in target " + target );
        this.addTab(title, href);
    },

    addTab: function (title, url)
    {

        if (this.isFrame()) {
            this.Frame.sendMessage({
                type: 'open',
                data: {
                    title: title,
                    url: url
                }
            });
            return;
        }

        console.log("Adding tab " + title + " (" + url + ")" );

        var $tabList = $('#master-tab-list');
        var $tabContent = $('#master-tab-content');

        // create tab nav item

        var tabNo = this._tabCounter++;
        var tabId = this.uniqueDomId('tab');
        var tabLinkId = tabId + 'lnk';

        var $navLink = $('<a>', {
            'role': 'tab',
            'aria-controls': tabId,
            'data-toggle': 'tab',
            'data-url': url,
            'data-tab-id': tabId,
            'href': '#' + tabId,
            'title': title,
            'id': tabLinkId
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

        console.log("tab added with id " + tabId);
        $navLink.trigger('click');
    },

    closeTab: function(tabId) {
        console.log("closing tab " + tabId);

        this.tabs[tabId] = null;
        delete this.tabs[tabId];

        // remove closed tab
        var lastTab;
        for (lastTab in this.tabs);

        if (lastTab) {
            this.focusTab(lastTab);
        }
    },

    focusTab: function(tabId) {
        console.log("Focusing tab with id: " + tabId);

        if (this.tabs.hasOwnProperty(tabId)) {

            var $navLink = $('#' + tabId + 'lnk');
            $navLink.trigger('click');
        }
    },

    reloadTab: function(tabId) {
        console.log("Reload tab with id: " + tabId);
        if (this.tabs.hasOwnProperty(tabId)) {

            var tabWin = this.tabs[tabId];
            if (tabWin) {
                tabWin.location.replace(tabWin.location.href);
            }
        }
    },

    setActiveTab: function(tabId, tabWin) {
        console.log("Active tab with id: " + tabId);

        if (tabWin !== undefined) {
            tabWin._id = 'window'+tabId,
            this.tabs[tabId] = tabWin;
        }
        this.tabActive = tabId;
    },

    Master: {
        parsePostMessage: function (msg, origin, source) {

            // collapse workaround
            // @TODO: Find out why a message with content 'collapse' is received on page load
            if (typeof(msg) === 'string' && msg === "collapse") {
                return false;
            }

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
                case "hello":
                    console.log("hello received");
                    break;

                case "flash":
                    Backend.Flash.message(data.type, data.message);
                    break;

                case "open":
                    Backend.addTab(data.title, data.url);
                    break;

                case "open-frame-modal":
                    Backend.Link.openModalFrame(data.url, data.options, data.opener);
                    break;

                case "loader":
                    var op = data.op;
                    if (Backend.Loader.hasOwnProperty(op)) {
                        var func = Backend.Loader[op];
                        if (typeof(func) === 'function') {
                            func();
                        }
                    }
                    break;

                default:
                    console.log("Unknown message type: " + parsed.type);
                    return;
            }


        }
    },

    Frame: {
        sendMessage: function(msg)
        {
            console.log("[frame] send msg: " + msg.type);

            // check if current window is a framed window
            var parent = window.parent || window;
            if (parent === window) {
                console.log("sending aborted: already on master");
                return;
            }

            // convert json objects to json strings
            if (typeof msg === 'object') {
                msg = JSON.stringify(msg);
            }

            var hostUrl = window.location.protocol + "//" + window.location.host;
            parent.postMessage(msg, hostUrl);
        }
    },

    Loader: {
        show: function() {
            console.log("show loader");

            if (Backend.isFrame()) {
                Backend.Frame.sendMessage({
                    type: 'loader',
                    data: {
                        op: 'show'
                    }
                });
            } else {

                $('#loader').show();
            }
        },

        hide: function() {
            console.log("hide loader");
            if (Backend.isFrame()) {
                Backend.Frame.sendMessage({
                    type: 'loader',
                    data: {
                        op: 'hide'
                    }
                });
            } else {

                $('#loader').hide();
            }
        }
    },

    Ajax: {

        loadHtml: function (target, url, callback)
        {
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html',
                cache: false,
                beforeSend: function() {
                    Backend.Loader.show();
                },
                complete: function() {
                    Backend.Loader.hide();
                },
                success: function(result) {

                    if (target !== null && target !== undefined) {
                        $(target).html(result);
                    }

                    if (typeof callback === 'function') {
                        callback(result);
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    alert("An error occured: " + textStatus + ": " + errorThrown);
                }
            })
        }

    },

    Flash: {

        message: function(type, msg, persist)
        {

            /*
            if (Backend.isFrame()) {
                Backend.Frame.sendMessage({
                    type: 'flash',
                    data: {
                        type: type,
                        message: msg
                    }
                });

            } else {
            }
            */

            console.log("Flash: [" + type + "] " + msg);
            //alert("[" + type + "] " + msg);

            var $alert = $('<div>', {
                class: 'alert alert-' + type
            });

            $alert.html('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + msg);
            $alert.hide();
            $alert.appendTo('#flash').slideDown();

            if (persist !== true) {
                setTimeout(function() {
                    $alert.slideUp(1000, function() { $(this).remove(); });
                }, 5000);
            }

        },

        success: function(msg)
        {
            this.message('success', msg);
        },

        error: function(msg)
        {
            this.message('danger', msg);
        }
    },


    Link: {

        openFrame: function (title, url)
        {

            console.log("Open link in new frame: " + url);
            Backend.addTab(title, url);


        },

        /*
        openModal: function (url)
        {
            if (window.parent === window) {
                window.location.href = url;
                return;
            }

            console.log("Open Link in Modal (todo): " + url);
            window.location.href = url;
        },
        */

        openModalFrame: function openLinkModalFrame(url, options, opener)
        {
            console.log("Open Link in Modal Frame: " + url);

            options = options || {};

            if (Backend.isFrame()) {
                Backend.Frame.sendMessage({
                    type: 'open-frame-modal',
                    data: {
                        url: url,
                        options: options,
                        opener: window._id
                    }
                });
                return;
            }


            console.log("Open Link in Modal Frame: " + url + " from opener with ID " + opener);

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

            //var modalId = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5);
            var modalId = Backend.uniqueDomId('modal');

            var $modal = $('<div>', {
                id: 'modal' + modalId,
                class: 'modal fade',
                tabIndex: -1,
                role: 'dialog'
            }).html(dialogTemplate);


            var $iframe = $('<iframe>', {
                class: 'modal-iframe',
                src: url,
                style: 'width: 100%; min-height: 500px;',
                height: $(window).height() * 0.70
            });

            var _window = window;

            if (options.title) {
                $modal.find('.modal-title').html(options.title);
            } else {
                $modal.find('.modal-header').remove();
            }

            $modal.find('.modal-body').html($iframe);

            $modal.modal({})
            $modal.on('shown.bs.modal', function (e) {
                $iframe.width($modal.width * 0.9);
                $iframe.height($(window).height() * 0.70);
            });

            $modal.on('hidden.bs.modal', function (e) {
                Backend.Util.saveScrollPosition(null, $(_window).scrollTop());
                //_window.location.replace(_window.location.href);
                Backend.reloadTab(opener.substr(6));
            });
        }
    },

    Util: {

        saveScrollPosition: function (scope, val)
        {
            if (!!window.localStorage) {
                var key = scope + '_scrollTop'
                var scrollTop = window.localStorage.setItem(key, val);
            } else {
                console.log("LocalStorage is not available");
            }
        },

        restoreScrollPosition: function (scope)
        {
            if (!!window.localStorage) {
                var key = scope + '_scrollTop'
                var scrollTop = window.localStorage.getItem(key);
                if (scrollTop) {
                    window.localStorage.removeItem(key);
                    $(window).scrollTop(scrollTop);
                }

            }
        }
    }

};

