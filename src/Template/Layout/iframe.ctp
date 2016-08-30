<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::locale(); ?>" class="iframe">
<head>
    <?= $this->Html->charset() ?>
    <title><?= $this->fetch('title') ?></title>
    <meta name="description" content="">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="robots" content="noindex,nofollow">

    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>

    <?= $this->fetch('cssBackend') ?>
    <?= $this->Html->css('Backend.jqueryui/jquery-ui.min'); ?>
    <?= $this->Html->css('Backend.jstree/themes/backend/style'); ?>
    <?= $this->Html->css('Backend.font-awesome'); ?>
    <?= $this->Html->css('Backend.bootstrap.min'); ?>
    <?= $this->Html->css('Backend.admin'); ?>
    <?= $this->Html->css('Backend.iframe'); ?>
    <?= $this->fetch('css') ?>

    <?= $this->fetch('script') ?>
    <?= $this->Html->script('Backend.jqueryui/jquery-ui.min'); ?>
    <?= $this->Html->script('Backend.bootstrap.min'); ?>
    <?= $this->Html->script('Backend.backend'); ?>
    <?= $this->fetch('scriptBackend'); ?>
</head>
<body>

<div id="page">
    <div id="page-top" class="fixed-top">
        <div id="flash">
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('auth'); ?>
            <?= $this->Flash->render('backend'); ?>
        </div>
    </div>

    <div id="page-main" class="container-fluid">
        <?= $this->fetch('content'); ?>
    </div>

    <div id="page-footer">
    </div>

</div> <!-- #page -->

<script>
    $(document).ready(function() {

        //
        // Tabs (bootstrap)
        //
        $(document).on('click','.be-tabs .nav a', function (e) {

            //console.log('be-tabs nav link clicked: ' + this.hash);

            e.preventDefault();
            var url = $(this).attr("data-url");

            if (typeof url !== "undefined" && !$(this).hasClass('tab-loaded')) {
                var pane = $(this), target = this.hash;

                // ajax load from data-url
                var _this = $(this);
                Backend.Ajax.loadHtml(target, url, function(html) {
                    pane.tab('show');
                    _this.addClass('tab-loaded');

                });
            } else {
                $(this).tab('show');
            }
        });

        $(document).on('dblclick','.be-tabs .nav a', function (e) {
            $(this).removeClass('tab-loaded');
            $(this).trigger('click');

            e.preventDefault();
            e.stopPropagation();
            return false;
        });

        //
        // Form Fieldset toggle
        //
        $(document).on('click', 'form fieldset > legend', function() {
            $(this).parent().toggleClass('collapsed');
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

            //console.log("Submit form to " + action + " via " + method + " with data " + data);

            $.ajax({
                url: action,
                data: data,
                method: method,
                beforeSend: function() {
                    Backend.Loader.show();
                },
                complete: function() {
                    Backend.Loader.hide();
                },
                success: function(result) {

                    //_self.parent().replaceWith(result);
                    //ajaxify();

                    Backend.beautify();
                    Backend.Flash.success('Success');

                    //window.location.href = window.location.href;

                }
            });

        });

        //
        // Backend Tabs: Auto-enable first tab
        //
        $('.be-tabs a').first().trigger('click');

        //
        // Jquery UI Sortable
        //
        //$('.sortable').sortable();


        Backend.ready();
    });
</script>
<?= $this->fetch('scriptBottom'); ?>

</body>
</html>