(function($) {
    var Backend = {
        init: false,
        settings: {
            rootUrl: '/',
            adminUrl: '/admin/'
        },

        _cssLoaded: [],
        _jsLoaded: []
    };

    Backend.initialize = function(settings) {
        console.log("[backendjs] INIT", settings);
        this.settings = settings;
        this.init = true;
    };

    Backend.isFrame = function() {
        var parent = window.parent || window;
        return parent !== window;
    };

    Backend.parsePostMessage = function (msg, origin, source) {

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
                //console.log("hello received");
                break;

            default:
                console.log("Unknown message type: " + parsed.type);
                return;
        }
    };

    Backend.sendPostMessage = function(msg) {
        //console.log("[frame] send msg: " + msg.type);

        // check if current window is a framed window
        if (!Backend.isFrame()) {
            //console.log("sending aborted: already on master");
            return;
        }

        // convert json objects to json strings
        if (typeof msg === 'object') {
            msg = JSON.stringify(msg);
        }

        var hostUrl = window.location.protocol + "//" + window.location.host;
        parent.postMessage(msg, hostUrl);
    };

    Backend.loadCss = function(filename) {

        if (this._cssLoaded.find(function(f) { return f == filename})) {
            console.log("[loadCss] File " + filename + " already loaded");
            return;
        }

        var fileref = document.createElement("link");
        fileref.setAttribute("rel", "stylesheet");
        fileref.setAttribute("type", "text/css");
        fileref.setAttribute("href", filename);

        document.getElementsByTagName("head")[0].appendChild(fileref);
        this._cssLoaded.push(filename);
    };

    Backend.loadJs = function(filename) {

        if (this._jsLoaded.find(function(f) { return f == filename})) {
            console.log("[loadJs] File " + filename + " already loaded");
            return;
        }

        var fileref = document.createElement('script');
        fileref.setAttribute("type", "text/javascript");
        fileref.setAttribute("src", filename);

        document.getElementsByTagName("head")[0].appendChild(fileref);
        this._jsLoaded.push(filename);
    };

    Backend.Frame = {

        /**
         * Reload the closest frame (window or ajax-content container)
         *
         * @param el
         */
        reloadClosest: function(el) {

            if (el && el !== window && $(el).closest('.ajax-content').length > 0) {
                $(el).closest('.ajax-content').trigger('reload');
                return;
            }

            window.location.href = window.location.href;
        }

    };

    /**
     * Url
     */
    Backend.Url = {};
    Backend.Url.buildAdminUrl = function (path) {
        return Backend.settings.adminUrl + path;
    };

    /**
     * Loader
     */
    Backend.Loader = {
        show: function() {
            //console.log("show loader");
            $('#loader').show();
        },

        hide: function() {
            //console.log("hide loader");
            $('#loader').hide();
        }
    };

    /**
     * Flash messages
     */

    Backend.Flash = {

        el: '#main-flash', // @TODO remove dependency on container with id

        message: function(type, msg, persist)
        {
            console.log("Flash: [" + type + "] " + msg);

            var $alert = $('<div>', {
                class: 'alert alert-' + type
            });

            $alert.html('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + msg);
            $alert.hide();
            $alert.appendTo($(this.el)).slideDown();

            if (persist === false) {
                setTimeout(function() {
                    $alert.slideUp(1000, function() { $alert.remove(); });
                }, 10000);
            }

        },

        success: function(msg, persist) { this.message('success', msg, persist); },
        warn: function(msg, persist) { this.message('warning', msg, persist); },
        error: function(msg, persist) { this.message('danger', msg, persist); },
        info: function(msg, persist) { this.message('info', msg, persist); },

        clearAll: function() {
            $(this.el).find('.alert').each(function() {
                var $alert = $(this);
                $alert.fadeOut(500, function() { $alert.remove(); });
            });
        }
    };

    /**
     * Util
     * Collection of helper methods
     */
    Backend.Util = {

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

        randomId: function(prefix) {
            if (prefix === undefined) {
                prefix = 'rnd';
            }
            return prefix + Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5);
        },

        saveScrollPosition: function (scope, val)
        {
            if (!!window.localStorage) {
                var key = scope + '_scrollTop'
                var scrollTop = window.localStorage.setItem(key, val);
            } else {
                console.log("[backendjs] LocalStorage is not available");
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
    };

    /**
     * Renderer
     */
    Backend.Renderer = {

        callbacks: {},

        addListener: function(event, callback) {

            if (this.callbacks[event] === undefined) {
                this.callbacks[event] = [];
            }

            if (typeof callback !== "function") {
                console.warn("[backendjs|renderer] ERROR Given callback is not a valid function");
                return;
            }

            this.callbacks[event].push(callback);
        },

        trigger: function(event, scope) {

            if (scope === undefined) {
                scope = document;
            }

            if (this.callbacks[event] instanceof Array) {
                this.callbacks[event].forEach(function(callback) {
                    callback.call(window, scope);
                });
            }
        },

        onReady: function(scope) {
            this.trigger('docready', scope);
        },

        onUnload: function(scope) {
            this.trigger('unload', scope);
        },

    };

    /**
     * Links
     */
    Backend.Modal = {

        _modalTemplate: '<div class="modal-dialog"> \
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
</div><!-- /.modal-dialog -->',

        open: function(html, modalOptions, options) {

            modalOptions = modalOptions || {};
            options = options || {};

            var modalId = Backend.Util.uniqueDomId('modal');
            var $modal = $('<div>', {
                id: 'modal' + modalId,
                class: 'modal fade',
                tabIndex: -1,
                role: 'dialog'
            }).html(this._modalTemplate);

            if (options.title) {
                $modal.find('.modal-title').html(options.title);
            } else {
                $modal.find('.modal-header').remove();
            }

            $modal.find('.modal-body').html(html);

            $modal.on('hidden.bs.modal', function(ev) {

                console.log("[backendjs] modal " + modalId + " is now hidden");
                console.log(ev);
                // http://stackoverflow.com/questions/11570333/how-to-get-twitter-bootstrap-modals-invoker-element
                var $invoker = $(ev.relatedTarget) || $(window);
                console.log($invoker);

                $invoker.focus();
            });
            $modal.modal(modalOptions);
            return $modal;
        },

        openIframe: function (url, modalOptions, options)
        {
            console.log("[backendjs] Open Iframe Modal with URL " + url);

            modalOptions = modalOptions || {};
            options = options || {};

            var modalId = Backend.Util.uniqueDomId('modal');
            var $modal = $('<div>', {
                id: 'modal' + modalId,
                class: ((options.class) ? options.class + ' ' : '') + 'modal fade',
                tabIndex: -1,
                role: 'dialog'
            }).html(this._modalTemplate);


            var $iframe = $('<iframe>', {
                class: 'modal-iframe',
                src: url,
                style: 'width: 100%; min-height: 500px; border: none;',
                height: $(window).height() * 0.70
            });

            if (options.title) {
                $modal.find('.modal-title')
                    .html(options.title)
                    .append($('<a>', { style: 'margin-left: 10px; font-size: 0.8em;', href: url.replace(/iframe=1/, "iframe=0"), target: '_blank'}).text("Open in new window"));
            } else {
                $modal.find('.modal-header').remove();
            }
            $modal.find('.modal-body').html($iframe);

            $modal.modal(modalOptions);
            $modal.on('shown.bs.modal', function (ev) {
                //$iframe.width($modal.width * 0.9);
                //$iframe.height($(window).height() * 0.70);
            });
            $modal.on('hidden.bs.modal', function(ev) {

                console.log("[backendjs] modal " + modalId + " is now hidden");
                //console.log(ev);
                // http://stackoverflow.com/questions/11570333/how-to-get-twitter-bootstrap-modals-invoker-element
                var $invoker = $(ev.relatedTarget) || $(window);
                //console.log($invoker);
                $invoker.focus();
            });
            return $modal;
            //return Backend.Modal.open($iframe, modalOptions, options);
        }
    };

    /**
     * Ajax
     */
    Backend.Ajax = {
        loadHtml: function (target, url, ajaxSettings, options)
        {
            var $target = $(target);
            /*
             if ($target.parent().hasClass('ajax-content')) {
             $target = $target.parent();
             } else {
             $target = $target;
             }
             */

            ajaxSettings = _.extend({
                url: url,
                type: 'GET',
                dataType: 'html',
                cache: false,
                global: true,
                context: $target,
                beforeSend: function() {
                    Backend.Renderer.onUnload($target);
                    $target
                        .addClass('ajax-content')
                        .addClass('ajax-content-loading')
                        .attr('data-url', url);
                },
                success: function(data, xhr) {
                    if (data.length === 0) {
                        $target.html('- Empty response -');
                        return;
                    }
                    $target.html(data);
                },
                complete: function(xhr, textStatus) {
                    $target
                        .addClass('ajax-content-loaded')
                        .removeClass('ajax-content-loading');

                    Backend.Renderer.onReady($target);
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log("request failed", xhr, textStatus, errorThrown);
                    //$target.html(xhr.responseText);

                    Backend.Modal.open(xhr.responseText);

                    /*
                    var cId = $target.attr('id');
                    var c = document.getElementById(cId);
                    var iframe = document.createElement('iframe');
                    //var html = '<body>Foo</body>';
                    c.innerHTML = '';
                    c.appendChild(iframe);

                    iframe.setAttribute('style', "width: 100%; height: 500px");
                    iframe.setAttribute('id', "ajax-error-iframe");
                    iframe.setAttribute('class', "ajax-error-iframe");
                    iframe.contentWindow.document.open();
                    iframe.contentWindow.document.write(xhr.responseText);
                    iframe.contentWindow.document.close();
                    */

                }
            }, ajaxSettings);

            return $.ajax(ajaxSettings);
        },

        load: function(url, options) {

            var ajaxOptions = _.extend({
                url: url,
                type: 'GET',
                dataType: 'html',
                cache: false,
                global: true
            }, options);

            return $.ajax(ajaxOptions);
        },

        /**
         * @param target
         * @param form
         * @deprecated Use Backend.Ajax.postForm() instead
         */
        submitForm: function(target, form) {

            var $target = $(target);
            var $form = $(form);
            var data = $form.serializeArray();
            var url = $form.data('url') || $form.attr('action');
            var method = $form.attr('method') || 'POST';

            console.log("Submit AJAX form", method, url, data);

            Backend.Ajax.loadHtml($target, url, {
                method: method,
                data: data
            }).done(function() {
                console.log("AJAX FORM SUBMITTED");
            }).fail(function() {
                console.error("AJAX FORM FAILED");
            }).then(function() {
                if ($target.data('url').length > 0) {
                    console.log("AJAX FORM has been submitted in ajax content container");
                }
            });

        },

        postForm: function(form) {

            var $form = $(form);
            var data = $form.serializeArray();
            var url = $form.data('url') || $form.attr('action');
            var method = $form.attr('method') || 'POST';

            console.log("POST AJAX form", method, url, data);
        }
    };

    /**
     * UI Elements
     */
    Backend.Ui = Backend.Ui || {};

    /**
     * UI Elements: Label
     * @type {{create: Function}|*}
     */
    Backend.Ui.Label = Backend.Ui.Label || {
        create: function(label, clazz) {
            return '<span class="label label-' + clazz + '">' + label + '</span>'
        }
    };

    /**
     * UI Elements: Link
     * @type {{create: Function}|*}
     */
    Backend.Ui.Link = {
        create: function(title, url, attrs) {
            attrs = attrs || {};
            var opts = _.extend({},
                {title: 'Untitled', 'href': '#', attrs: {}},
                {title:title, href:url, attrs:attrs});
            return $('<a>')
                .attr('href', opts.href)
                .attr(opts.attrs)
                .html(opts.title)
                .prop('outerHTML');
        }
    };

    /**
     * UI Elements: Link
     * @type {{create: Function}|*}
     */
    Backend.Ui.Icon = {
        create: function(icon, attrs, clazzes) {
            attrs = _.extend({}, {}, attrs);
            return $('<i>', { class: 'fa fa-' + icon})
                .attr(attrs)
                .addClass(clazzes)
                .prop('outerHTML');
        }
    };

    /**
     * Bind global jQuery AJAX events
     */
    $(document).ajaxStart(function(event, xhr) {
        //Backend.Flash.clearAll();
        Backend.Loader.show();
    });

    $(document).ajaxStop(function(event, xhr) {
        Backend.Loader.hide();
    });

    $(document).ajaxSend(function(event, xhr, settings) {
        console.log("AJAX SEND: ", settings.url);
    });

    $(document).ajaxSuccess(function(event, xhr, settings) {
        console.log("AJAX SUCCESS: ", settings.url);
    });

    $(document).ajaxError(function(event, xhr, settings, thrownError) {
        console.error("AJAX ERROR: ", thrownError, xhr);
        //Backend.Flash.error("Ups, something went wrong: " + thrownError);
    });


    $(document).ajaxComplete(function(event, xhr, options){
        var ct = xhr.getResponseHeader("content-type") || "";
        //console.log("AJAX response content-type: " + ct);
        if (ct.indexOf('html') > -1) {
            //console.log("AJAX call obviously returned some HTML");
        }
        if (ct.indexOf('json') > -1) {
            //console.log("AJAX call obviously returned some JSON");
        }
    });

    /**
     * Bind DOM Events
     */

    $(document).on('reload', '.ajax-content', function(ev) {

        if (ev.isPropagationStopped()) {
            return;
        }


        var $container = $(ev.target);
        if ($container.hasClass('ajax-content-loaded')) {
            $container.removeClass('ajax-content-loaded');
        }


        var id = $container.attr('id');
        var url = $container.attr('data-url');
        console.log("AJAX content reloading [" + id + "]: " + url);


        if (!url) {
            $container.html('Failed to load ajax content: No data url provided');
            return;
        }

        Backend.Ajax.loadHtml($container, url, {});

    });

//
// Tabs (bootstrap)
//
    $(document).on('click','.tabs .nav a', function (ev) {

        console.log('tabs nav link clicked: ' + this.hash);

        var $tabLink = $(ev.target);
        var url = $tabLink.attr("data-url");

        if (typeof url !== "undefined" && !$tabLink.hasClass('tab-loaded') && !$tabLink.hasClass('tab-loading')) {
            var target = this.hash;
            var $tab = $(target);
            if (!$tab.length) {
                console.error("Tab content area with ID " + target + " not found");
                return;
            }

            // ajax load from data-url
            $tab.html("Loading ...");
            $tabLink.addClass('tab-loading').tab('show');
            Backend.Ajax.loadHtml($tab, url, {}).then(function() {

                console.log("Tab " + target + " loaded");

                //$tabLink.tab('show');
                $tabLink.addClass('tab-loaded');
                $tabLink.removeClass('tab-loading');
            });
        } else {
            $tabLink.tab('show');
        }

        $tabLink.closest('.tabs').addClass('tabs-init');
        ev.preventDefault();
        ev.stopPropagation();
        return false;
    });


//
// Tab reload with double click
//
    $(document).on('dblclick','.tabs .nav a', function (ev) {
        console.log('tabs nav link dblclicked: ' + this.hash);
        $(this).removeClass('tab-loaded');
        $(this).trigger('click');

        ev.preventDefault();
        ev.stopPropagation();
        return false;
    });

//
// Bind history events
// @TODO Move functionality to Backend.History
//
    $(window).on('popstate', function(ev) {
        var state = ev.originalEvent.state;
        console.log("Popstate! ", window.location.href, state, ev);

        if (state !== null) {
            if (state.context && state.context === "backend") {
                if (state.scopeId && state.scopeId !== "DOCUMENT") {
                    Backend.Ajax.loadHtml($('#' + state.scopeId), location.href);
                } else {
                    window.location.href = location.href;
                }

                ev.stopPropagation();
            }
        } else {
            window.location.href = location.href;
        }
    });

//
// Form fieldset toggle
//
    $(document).on('change', '.select-ajax', function(ev) {
        var $select = $(ev.target);

        var target = $select.data('target');
        var url = $select.data('url');

        Backend.Ajax.loadHtml($('#' + target), url).then(function() {
            console.log("Select Ajax loaded some html");
        });


        console.log("Select Ajax changed", $select.val(), $select);
    });

//
// Form fieldset toggle
//
    $(document).on('click', 'form fieldset > legend', function() {
        $(this).parent().toggleClass('collapsed');
    });

//
// Form Submission
// Submit forms via AJAX in scoped context
//
    $(document).on('submit', 'form[data-ajax]', function(ev) {

        var $form = $(this);
        var $submitBtn = $form.find('button[type=submit]').first();
        $submitBtn.data('label', $submitBtn.text());
        var data = $form.serialize();
        var url = $form.data('url') || $(this).attr('action');
        var method = $form.attr('method') || 'POST';

        console.log("Submit form", method, url, data);

        // If we are in an ajax content container
        // submit form via AJAX
        /*
         var $container = $form.closest('.ajax-content');
         if ($container.length > 0) {
         Backend.Ajax.submitForm($container, ev.target);
         ev.preventDefault();
         ev.stopPropagation();
         }
         */
        var timeout = null;

        $.ajax({
            url: url,
            dataType: 'json',
            data: data,
            method: 'POST',
            beforeSend: function() {
                $submitBtn.addClass('btn-loading').text('Saving ...');
            }
        }).done(function(response) {
            console.log("Ajax form DONE", response)
            $submitBtn.removeClass('btn-default').addClass('btn-success').html('Saved <i class="fa fa-check"></i> ');
        }).fail(function(response) {
            console.log("Ajax form FAIL", response)
            $submitBtn.removeClass('btn-default').addClass('btn-danger').html("Saving failed :(");
        }).always(function() {
            $submitBtn.removeClass('btn-loading');
            if (timeout) {
                clearTimeout(timeout);
            }
            timeout = setTimeout(function() {
                $submitBtn
                    .removeClass('btn-success')
                    .removeClass('btn-danger')
                    .html($submitBtn.data('label'));
            }, 7500);
        });

        ev.preventDefault();
        return false;
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

        Backend.parsePostMessage(data, origin, source);
    });


// send hello message from iframes
    $(document).on('ready', function() {
        Backend.sendPostMessage({ type: 'hello' });
    });

    /**
     * Register global Backend.Renderer event listener
     */
    Backend.Renderer.addListener('docready', function(scope) {

        //console.log("[backendjs] DOCUMENT IS READY");
        var scopeId;
        switch (scope) {
            case undefined:
                console.error("docready in undefined scope");
                return;
            case document:
                scopeId = "DOCUMENT"; break;
            default:
                scopeId = $(scope).attr('id');
        }
        //console.log("[backendjs] Renderer docready in scope: " + scopeId);

        // icon links
        //$(scope).find("a[data-icon]:not(.icon-loaded)").each(function() {
            //console.log("link " + this.href + " has icon " + $(this).data('icon'));

        //    var $ico = $('<i>', { "class": 'fa fa-' + $(this).data('icon') }).html("");
        //    $(this).prepend($ico.prop('outerHTML') + "&nbsp").addClass('icon-loaded');
        //});

        //
        // Tabs: Auto-enable first tab
        //
        //$(scope).find('.tabs:not(.tabs-init) .nav a').first().trigger('click');
        $(scope).find('.tabs:not(.tabs-init)').each(function() {
            $(this).find('.nav a').first().trigger('click');
        });

        //
        // Dropdown
        //
        $(scope).find('.dropdown-toggle').dropdown();

        //
        // Jquery UI Sortable
        //
        //if ($.fn.sortable) {
        //    $('.sortable').sortable();
        //}

        //
        // Array-to-list collapsable
        //
        $(scope).on('click', '.array-list > li.has-children', function(ev){
            $(this).children('ul').toggleClass('collapse');
            ev.stopPropagation();
        });


        //
        // Table: Actions Menu
        //
        $(scope).find('table tr td.actions ul.actions-menu:not(.actions-menu-dropdown)').each(function() {
            $(this).wrap('<div class="btn-group"></div>');
            $(this).before('<button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-gear "></i>&nbsp;<span class="caret"></span></button>');
            $(this).addClass('actions-menu-dropdown dropdown-menu dropdown-menu-right');
        });

        //
        // Panels: Collapsale panels
        //
        $(scope).off('click','.panel-collapse > .panel-heading');
        $(scope).on('click', '.panel-collapse > .panel-heading', function(ev) {
            $(this).parent().toggleClass('panel-collapsed')
        });

        //
        // Ajax Content
        //
        $(scope).find('.ajax-content[data-url]').each(function() {

            var $container = $(this);
            var id = $container.attr('id');
            var url = $container.data('url');
            console.log("AJAX content loading [" + id + "]: " + url);

            if ($container.hasClass('ajax-content-loading') || $container.hasClass('ajax-content-loaded')) {
                return;
            }

            if (!url) {
                $container.html('Failed to load ajax content: No data url provided');
                return;
            }

            Backend.Ajax.loadHtml($('#' + id), url, {});
        });

        //
        // Bind link events for ajax content loading
        //

        $(scope).off('click','a.link-window');
        $(scope).on('click','a.link-window', function (ev) {

            ev.stopPropagation();
        });

        $(scope).off('click','a.link-external')
        $(scope).on('click','a.link-external', function (ev) {

            var $a = $(ev.target);
            $a.attr('target', '_blank');

            ev.stopPropagation();
        });

        $(scope).off('click','a.link-ajax, a[data-ajax]')
        $(scope).on('click','a.link-ajax, a[data-ajax]', function (ev) {

            var $a = $(ev.currentTarget);
            var url = $a.attr('href');
            console.log("Ajax link clicked: " + url, ev);

            if (!url) {
                //return false;
            }

            $.ajax({
                method: "GET",
                url: url,
                success: function(data) {
                    console.log("Ajax link success", data);
                    Backend.Flash.message('success', "Request successful");
                },
                error: function(xhr, err) {
                    Backend.Flash.message('error', "Request failed");
                    console.error("Ajax link error", err);
                }
            });

            ev.stopPropagation();
            ev.preventDefault();
            return false;
        });


        $(scope).off('click','a[data-modal], a.link-modal');
        $(scope).on('click','a[data-modal], a.link-modal', function (ev) {

            //var $target = $(ev.target);
            var $a = $(this);
            var url = $a.attr('href');

            Backend.Ajax.load(url).done(function(html) {
                var $container = $('<div>', {class: 'ajax-content ajax-content-loaded', 'data-url': url}).html(html);
                var $modal = Backend.Modal.open($container, {}, {
                    title: ev.target.title || ev.target.innerText
                });
                $modal.on('shown.bs.modal', function() {
                    console.log("modal shown");
                });
                $modal.on('hidden.bs.modal', function() {
                    console.log("modal hidden");
                });
                Backend.Renderer.onReady($container);
            });


            ev.stopPropagation();
            ev.preventDefault();
            return false;
        });

        $(scope).off('click','a[data-modal-frame], a.link-modal-frame');
        $(scope).on('click','a[data-modal-frame], a.link-modal-frame', function (ev) {

            if(Backend.isFrame()) {
                console.log("Trying to open iframe modal within modal. Open in same window");
                return;
            }

            //var $target = $(ev.target);
            var $target = $(this);
            var modalOptions = {
                //backdrop: $target.data('modalBackdrop'),
                //show: $target.data('modalShow'),
                //keyboard: $target.data('modalKeyboard')
            };
            var modalClass = $target.data('modalClass') || ($target.hasClass('link-modal-wide')) ? 'modal-wide' : '';
            var modalTitle = $target.data('modalTitle') || ev.target.title || ev.target.innerText;
            var modalReloadOnClose = $target.data('modalReload');

            var url = $target.attr('href');
            if (url.indexOf('?') > -1){
                url += '&iframe=1'
            }else{
                url += '?iframe=1'
            }

            var $modal = Backend.Modal.openIframe(url, modalOptions, {
                title: modalTitle,
                class: modalClass
            });

            $modal.on('shown.bs.modal', function() {
                console.log("iframe modal shown");
            });
            $modal.on('hidden.bs.modal', function() {
                //$target.closest('.ajax-content').trigger('reload');

                if (modalReloadOnClose) {
                    //$target.closest('.ajax-content').trigger('reload');
                    Backend.Frame.reloadClosest($target);
                }
            });

            ev.stopPropagation();
            ev.preventDefault();
            return false;
        });





        $(scope).find('div.flash > div').each(function() {
            var $flash = $(Backend.Flash.el);

            $(this).appendTo($flash);
        });

        var linkHandler = function (ev) {

            if (ev.isPropagationStopped()) {
                return;
            }

            if (ev.target.nodeName !== "A") {
                return;
            }

            // skip anchor links
            if (ev.target.hash.length > 0 || ev.target.href.indexOf('#') > -1) {
                return;
            }

            // skip links with target attribute set
            if (ev.target.getAttribute('target')) {
                return;
            }

            var $a = $(ev.target);
            var href = $a.attr('href');

            var scopeId;
            var $scope = $(ev.target).closest('.ajax-content');
            if ($scope.length > 0) {
                scopeId = $scope.attr('id') || 'UNKNOWN';
            } else {
                $scope = $(document);
                scopeId = null;
            }

            console.log("[global] Link clicked in scope: " + href, scopeId);


            if (scopeId !== null) {

                Backend.Ajax.loadHtml($scope, href).always(function() {
                    //if (!!window.history) {
                    //    history.pushState({ context: 'backend', scopeId: scopeId }, '', href);
                    //}
                });
                ev.preventDefault();
                ev.stopPropagation();
                return false;
            }
        };
        //$(scope).off('click', 'a', linkHandler);
        //$(scope).on('click','a', linkHandler);

        // scroll to scope container
        var scopeOffset = 0;
        if (scope !== document) {
            scopeOffset = $(scope).offset().top - 100; // this is a bit inconsistent because of the toolbar affix
            if (scopeOffset < 0) {
                scopeOffset = 0;
            }
        }

        //console.log("Scrolling to offset " + scopeOffset);
        //$(window).scrollTop(scopeOffset);

    });


    Backend.initialize(window.BackendSettings);
    window.Backend = Backend;


    $(document).ready(function() {
        if (Backend.init !== true) {
            console.warn("Backend not initialized. Aborting.");
            return;
        }
        Backend.Renderer.onReady();
    });

    $(window).on('unload', function() {
        Backend.Renderer.onUnload();
    })
})(jQuery);