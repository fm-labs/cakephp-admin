var Backend = Backend || {};

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
            console.log("hello received");
            break;

        default:
            console.log("Unknown message type: " + parsed.type);
            return;
    }
};

Backend.sendPostMessage = function(msg) {
    console.log("[frame] send msg: " + msg.type);

    // check if current window is a framed window
    if (!Backend.isFrame()) {
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

    el: '#flash',

    message: function(type, msg, persist)
    {
        //console.log("Flash: [" + type + "] " + msg);

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
            console.warn("[backend|renderer] ERROR Given callback is not a valid function");
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
    }

};

/**
 * Links
 */
Backend.Modal = {

    _modalTemplate: '<div class="modal-dialog modal-lg" style="width: 95%;"> \
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
        $modal.modal(modalOptions);
        return $modal;
    },

    openIframe: function (url, modalOptions, options)
    {
        console.log("Open Iframe Modal with URL " + url);

        modalOptions = modalOptions || {};
        options = options || {};

        var modalId = Backend.Util.uniqueDomId('modal');
        var $modal = $('<div>', {
            id: 'modal' + modalId,
            class: 'modal fade',
            tabIndex: -1,
            role: 'dialog'
        }).html(this._modalTemplate);


        var $iframe = $('<iframe>', {
            class: 'modal-iframe',
            src: url,
            style: 'width: 100%; min-height: 500px;',
            height: $(window).height() * 0.70
        });

        if (options.title) {
            $modal.find('.modal-title').html(options.title);
        } else {
            $modal.find('.modal-header').remove();
        }
        $modal.find('.modal-body').html($iframe);

        $modal.modal(modalOptions);
        $modal.on('shown.bs.modal', function (ev) {
            $iframe.width($modal.width * 0.9);
            $iframe.height($(window).height() * 0.70);
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
                //$target.html(xhr.responseText);

                var cId = $target.attr('id');
                var c = document.getElementById(cId);
                var iframe = document.createElement('iframe');
                var html = '<body>Foo</body>';
                c.innerHTML = '';
                c.appendChild(iframe);

                iframe.setAttribute('style', "width: 100%; height: 500px");
                iframe.setAttribute('id', "ajax-error-iframe");
                iframe.setAttribute('class', "ajax-error-iframe");
                iframe.contentWindow.document.open();
                iframe.contentWindow.document.write(xhr.responseText);
                iframe.contentWindow.document.close();

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

    submitForm: function(target, form) {

        var $target = $(target);
        var $form = $(form);
        var data = $form.serialize();
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

    }
};


/**
 * Bind global jQuery AJAX events
 */
$(document).ajaxStart(function(event, xhr) {
    Backend.Flash.clearAll();
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
    Backend.Flash.error("Ups, something went wrong: " + thrownError);
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
    var url = $container.data('url');
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

    if (typeof url !== "undefined" && !$tabLink.hasClass('tab-loaded')) {
        var target = this.hash;
        var $tab = $(target);
        if (!$tab.length) {
            console.error("Tab content area with ID " + target + " not found");
            return;
        }

        // ajax load from data-url
        Backend.Ajax.loadHtml($tab, url, {}).then(function() {

            console.log("Tab " + target + " loaded");

            $tabLink.tab('show');
            $tabLink.addClass('tab-loaded');
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
    $(this).removeClass('tab-loaded');
    $(this).trigger('click');

    ev.preventDefault();
    ev.stopPropagation();
    return false;
});


//
// Bind link events for ajax content loading
//

$(document).on('click','a.link-window', function (ev) {

    ev.stopPropagation();
});

$(document).on('click','a.link-external', function (ev) {

    var $a = $(ev.target);
    $a.attr('target', '_blank');

    ev.stopPropagation();
});


$(document).on('click','a.link-modal', function (ev) {

    var $a = $(ev.target);
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

$(document).on('click','a.link-modal-frame, a.link-frame-modal', function (ev) {

    var $a = $(ev.target);

    var url = $a.attr('href');
    if (url.indexOf('?') > -1){
        url += '&iframe=1'
    }else{
        url += '?iframe=1'
    }

    var $modal = Backend.Modal.openIframe(url, {}, {
        title: ev.target.title || ev.target.innerText
    });

    $modal.on('shown.bs.modal', function() {
        console.log("iframe modal shown");
    });
    $modal.on('hidden.bs.modal', function() {
        $a.closest('.ajax-content').trigger('reload');
        /*
        if ($a.data('reloadOnClose')) {
            $a.closest('.ajax-content').trigger('reload');
        }
        */
    });

    ev.stopPropagation();
    ev.preventDefault();
    return false;
});


$(document).on('click','a', function (ev) {

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

            if (!!window.history) {
                history.pushState({ context: 'backend', scopeId: scopeId }, '', href);
            }
        });
        ev.preventDefault();
        ev.stopPropagation();
        return false;
    }
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
$(document).on('submit', 'form', function(ev) {

    var $form = $(ev.target);
    var data = $form.serialize();
    var url = $form.data('url') || $(this).attr('action');
    var method = $form.attr('method') || 'POST';

    console.log("Submit form", method, url, data);

    // If we are in an ajax content container
    // submit form via AJAX
    var $container = $form.closest('.ajax-content');
    if ($container.length > 0) {
        Backend.Ajax.submitForm($container, ev.target);
        ev.preventDefault();
        ev.stopPropagation();
    }

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

    //@TODO Remove debug output
    console.log("DOCUMENT IS READY");
    var scopeId;
    switch (scope) {
        case undefined:
            scopeId = "NOSCOPE"; break;
        case document:
            scopeId = "DOCUMENT"; break;
        default:
            scopeId = $(scope).attr('id');
    }
    console.log("Renderer docready in scope: " + scopeId);

    // icon links
    $(scope).find("a[data-icon]:not(.icon-loaded)").each(function() {
        //console.log("link " + this.href + " has icon " + $(this).data('icon'));

        var $ico = $('<i>', { "class": 'fa fa-' + $(this).data('icon') }).html("");
        $(this).prepend($ico.prop('outerHTML') + "&nbsp").addClass('icon-loaded');
    });

    //
    // Tabs: Auto-enable first tab
    //
    $(scope).find('.tabs:not(.tabs-init) .nav a').first().trigger('click');

    //
    // Jquery UI Sortable
    //
    if ($.fn.sortable) {
        $('.sortable').sortable();
    }

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


});