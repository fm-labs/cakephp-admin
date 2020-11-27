/**
 * AdminJs Alert & Confirmation Boxes replacement extension
 *
 * !Experimental!
 */
(function( $, AdminJs ) {

    AdminJs = AdminJs || {};
    AdminJs.Alert = function() {

        return {

            templates: {

                'alert': '<div class="modal-dialog modal-sm modal-danger"> \
    <div class="modal-content"> \
    <div class="modal-header"> \
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> \
<h4 class="modal-title"></h4> \
</div> \
<div class="modal-body"> \
</div> \
<div class="modal-footer"> \
    <button type="button" class="btn btn-default" data-dismiss="modal">OK</button> \
</div> \
</div><!-- /.modal-content --> \
</div><!-- /.modal-dialog -->',

            'confirm': '<div class="modal-dialog modal-sm"> \
    <div class="modal-content bg-danger"> \
    <div class="modal-header"> \
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> \
<h4 class="modal-title"></h4> \
</div> \
<div class="modal-body"> \
</div> \
<div class="modal-footer"> \
    <button class="confirm-dismiss" type="button" class="btn btn-default btn-danger" data-dismiss="modal">Dismiss</button> \
    <button class="confirm-ok" type="button" class="btn btn-default btn-success">Confirm</button> \
</div> \
</div><!-- /.modal-content --> \
</div><!-- /.modal-dialog -->',

               'prompt':  '<div class="modal-dialog modal-sm"> \
    <div class="modal-content bg-danger"> \
    <div class="modal-header"> \
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> \
<h4 class="modal-title"></h4> \
</div> \
<div class="modal-body"> \
<input type="text" name="prompt" />\
</div> \
<div class="modal-footer"> \
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> \
</div> \
</div><!-- /.modal-content --> \
</div><!-- /.modal-dialog -->',
            },

            alert: function(msg) {
                console.log("ALERT", msg);
                AdminJs.Alert.openModal(msg, {}, { title: 'Alert', 'class': 'modal-info' }, 'alert');
            },

            prompt: function(msg) {
                console.log("PROMPT", msg);
                AdminJs.Alert.openModal(msg, {}, { title: 'Prompt', 'class': 'modal-info' }, 'prompt');
            },

            confirm: function(msg) {
                console.log("CONFIRM", msg);
                AdminJs.Alert.openModal(msg,  {}, { title: 'Confirm', 'class': 'modal-info' }, 'confirm');
                return false;
            },

            openModal: function(html, modalOptions, options, template) {
                modalOptions = modalOptions || {};
                options = options || {};
                template = template || 'alert';

                var modalId = AdminJs.Util.uniqueDomId('modal');
                var $modal = $('<div>', {
                    id: 'modal' + modalId,
                    class: 'modal fade',
                    tabIndex: -1,
                    role: 'dialog'
                }).html(this.templates[template]);

                if (options.title) {
                    $modal.find('.modal-title').html(options.title);
                } else {
                    $modal.find('.modal-header').remove();
                }

                if (options.class) {
                    $modal.addClass(options.class);
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
            }
        }
    }();

    //window._alert = window.alert;
    //window.alert = AdminJs.Alert.alert;
    //window._prompt = window.prompt;
    //window.prompt = AdminJs.Alert.prompt;
    //window._confirm = window.confirm;
    //window.confirm = AdminJs.Alert.confirm;

}( jQuery, AdminJs ));

