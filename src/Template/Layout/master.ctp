<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <title><?= $this->fetch('title') ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="robots" content="noindex,nofollow">

    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>

    <?= $this->Html->css('Backend.bootstrap.min'); ?>
    <?= $this->Html->css('Backend.master'); ?>
    <?= $this->fetch('css') ?>

    <?= $this->fetch('script') ?>
    <?= $this->Html->script('Backend.jquery/jquery-1.11.2.min'); ?>
    <?= $this->Html->script('Backend.bootstrap.min'); ?>
    <?= '' // $this->Html->script('Backend.be-master'); ?>
    <?= $this->Html->script('Backend.backend'); ?>

</head>
<body>

<div id="page">
    <header id="top">
        <nav id="header-nav" class="navbar navbar-default navbar-inverse">
            <?= $this->element('Backend.Layout/master/header_nav'); ?>
        </nav>

        <div id="flash">
            <?= $this->Flash->render('auth') ?>
            <?= $this->Flash->render('backend') ?>
        </div>
    </header>

    <main id="main">
        <?= $this->fetch('content'); ?>
    </main>

    <footer id="footer" class="container-fluid">
        <?= $this->element('Backend.Layout/master/footer'); ?>
    </footer>

    <div id="loader" class="loader"><i class="fa fa-cog fa-spin fa-2x"></i></div>
    <div id="modal-container"></div>

</div> <!-- #page -->
<script>
    $(document).ready(function() {


        // #################################
        // # MASTER NAV #
        // #################################

        var $nav = $('#header-nav');


        $(document).on('click','#header-nav .nav a:not(.link-master)', function (ev) {
        //$('#header-nav .nav a').on('click', function (ev) {
            ev.preventDefault();
            ev.stopPropagation();
            console.log("header nav link clicked: " + this.href);
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

            console.log("Master tab nav item clicked: " + this.hash);

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
                    console.log("iframe loaded " + url);
                    Backend.Loader.hide();
                    Backend.setActiveTab(tabId, this.contentWindow);
                    //$iframeLoader.remove();
                    _this.removeClass('tab-loading');
                    _this.addClass('tab-ajax-loaded');
                    _this.html(this.contentWindow.document.title);
                    _this.attr('title', this.contentWindow.document.title);
                    _this.attr('data-url', this.contentWindow.location);
                });

                Backend.Loader.show();
                $iframe.attr('src', url);

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

                var tabId = $(this).parent().data('tabId');

                $(this).addClass('tab-loading');
                $(this).tab('show');

                // @TODO Keep this DRY. (see click event)
                var _this = $(this);
                var $iframe = $('#' + tabId).find('iframe.tab-frame').first();
                $iframe.on('load', function() {
                    Backend.Loader.hide();
                    _this.removeClass('tab-loading');
                });

                Backend.Loader.show();
                $iframe.attr('src', url);
            }

        });


        $(document).on('show.bs.tab', '#master-tabs .nav a', function (e) {
            //Backend.Loader.show();
        });

        $(document).on('shown.bs.tab', '#master-tabs .nav a', function (e) {
            //Backend.Loader.hide();
            document.title = $(this).attr('title') || $(this).text();
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

        $(document).on('click', '.test-notify', function() {
            Backend.Flash.success("Test Notify");
        });

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
</script>
</body>
</html>