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

    <?= $this->fetch('cssBackend') ?>
    <?= $this->Html->css('Backend.jqueryui/jquery-ui.min'); ?>
    <?= $this->Html->css('Backend.bootstrap.min'); ?>
    <?= $this->Html->css('Backend.admin'); ?>
    <?= $this->fetch('css') ?>

    <?= $this->fetch('script') ?>
    <?= $this->Html->script('Backend.jqueryui/jquery-ui.min'); ?>
    <?= $this->Html->script('Backend.bootstrap.min'); ?>
    <?= '' // $this->Html->script('Backend.be-ui'); ?>
    <?= $this->Html->script('Backend.backend'); ?>
    <?= $this->fetch('scriptBackend'); ?>

</head>
<body>

<div id="page">
    <header id="top">
        <nav id="toolbar">
            <?php
            echo $this->Ui->menu($this->Toolbar->getMenuItems(),
                ['class' => 'nav nav-pills'],
                ['class' => 'dropdown-menu']
            );
            ?>
        </nav>
        <div id="flash" class="container-fluid">
            <?= $this->Flash->render('backend') ?>
        </div>
    </header>

    <main id="page-main">

        <div id="page-crumbs">
            <?= $this->element('Backend.Layout/admin/breadcrumbs'); ?>
        </div>
        <div id="page-content" class="container-fluid">
            <?= $this->fetch('content'); ?>
        </div>
    </main>

    <footer id="page-footer">
        <?= $this->element('Backend.Layout/admin/footer'); ?>
    </footer>

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