var Backend = {

    _domIdCounter: 0,

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
        this.log("Window is Iframe: " + this.isIframe());
        this.log(window);

        var _this = this;

        // capture all links
        /*
        $(document).on('click', 'a', function(ev) {

            console.log("Link clicked: " + this.href);

            return _this.openLink(this, ev);
        });
        */

    },

    isIframe: function()
    {

        var parent = window.parent || window;
        if (parent !== window) {
            return true;
        }

        return false;
    },

    openLink: function(link, ev)
    {

        if ($.contains(document.getElementById('master-tab-list'), $(link))) {
            console.log("Skip: Is Master Tab Nav Item");
            return;
        }

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

        console.log("add tab for " + href + " (" + target + ")");
        //this.addTab(title, href, target);

        ev.stopPropagation();
        ev.preventDefault();
        return false;
    },

    openLinkIframeModal: function() {

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

        console.log("Open new tab " + title + " (" + url + ")" );

        var $tabList = $('#master-tab-list');
        var $tabContent = $('#master-tab-content');

        // create tab nav item

        var tabId = Backend.uniqueDomId('tab');

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

        console.log("tab added");
        $navLink.trigger('click');
    },

    Master: {
        parsePostMessage: function parsePostMessage(msg, origin, source) {

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
                    Backend.addTab(data.title, data.url);
                    break;

                default:
                    console.log("Unknown message type: " + parsed.type);
                    return;
            }


        }
    },

    Loader: {
        show: function() {
            console.log("show loader");
        },

        hide: function() {
            console.log("hide loader");
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

        message: function(type, msg)
        {
            console.log("Flash: [" + type + "] " + msg);
            alert("[" + type + "] " + msg);
        },

        success: function(msg)
        {
            this.message('success', msg);
        },

        error: function(msg)
        {
            this.message('error', msg);
        }
    },


    Link: {

        openFrame: function (title, url)
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

        },

        openModal: function (url)
        {
            if (window.parent === window) {
                window.location.href = url;
                return;
            }

            console.log("Open Link in Modal (todo): " + url);
            window.location.href = url;
        },

        openModalFrame: function openLinkModalFrame(url)
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
                _window.location.replace(_window.location.href);
            });
        }
    }

};

