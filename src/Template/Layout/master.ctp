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
        <nav id="header-nav" class="navbar navbar-default">
            <?= $this->element('Backend.Layout/master/header_nav'); ?>
        </nav>

        <div id="flash" class="container-fluid">
            <?= $this->Flash->render('auth') ?>
            <?= $this->Flash->render('backend') ?>
        </div>
    </header>

    <main id="main">

        <?= $this->fetch('content'); ?>

    </main>

    <footer id="footer">
        <?= '' // $this->element('Backend.Layout/master/footer'); ?>
    </footer>

    <div id="loader"></div>

</div> <!-- #page -->
<script>
    $(document).ready(function() {

        // #################################
        // # MASTER NAV #
        // #################################

        var $nav = $('#header-nav');


        $(document).on('click','#header-nav .nav a', function (ev) {
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

            Backend.Master.parsePostMessage(data, origin, source);
        });

        Backend.ready();
    });
</script>
</body>
</html>