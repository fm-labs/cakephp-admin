<?php
use Cake\Routing\Router;

$modelName = $this->request->query('model');
$id = $this->request->query('id');

//$viewUrl = $this->Html->Url->build(['action' => 'view', 'model' => $modelName, 'id' => $id]);
$viewUrl = Router::url(['action' => 'view', 'model' => $modelName, 'id' => $id]);
?>
<html>
<head>
    <title>BoneForm Cake Models</title>

    <link rel="stylesheet" type="text/css" href="/boneform-js/node_modules/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/boneform-js/node_modules/font-awesome/css/font-awesome.min.css" />

    <script src="/boneform-js/node_modules/jquery/dist/jquery.js"></script>
    <script src="/boneform-js/node_modules/underscore/underscore.js"></script>
    <script src="/boneform-js/node_modules/backbone/backbone.js"></script>
    <script src="/boneform-js/node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="/boneform-js/src/form.js"></script>
    <script src="/boneform-js/src/datamodel.js"></script>
    <script src="/boneform-js/src/field.js"></script>
    <script src="/boneform-js/src/submit.js"></script>
    <script src="/boneform-js/src/control.js"></script>
    <script src="/boneform-js/src/controls/text.js"></script>
    <script src="/boneform-js/src/controls/number.js"></script>
    <script src="/boneform-js/src/controls/textarea.js"></script>
    <script src="/boneform-js/src/controls/hidden.js"></script>
    <script src="/boneform-js/src/controls/select.js"></script>
    <script src="/boneform-js/src/controls/checkbox.js"></script>
    <script src="/boneform-js/src/controls/button.js"></script>
    <script src="/boneform-js/src/controls/date.js"></script>
    <script src="/boneform-js/src/validators.js"></script>
    <script src="/boneform-js/src/handlers/default.js"></script>
    <script src="/boneform-js/src/handlers/ajax.js"></script>
    <script src="/boneform-js/src/handlers/json.js"></script>
    <script src="/boneform-js/src/handlers/model.js"></script>
    <script src="/boneform-js/src/templates/bootstrap3.js"></script>
    <script src="/boneform-js/src/templates/bootstrap3-horizontal.js"></script>

    <!-- ChosenJS
     -->
    <link rel="stylesheet" type="text/css" href="/boneform-js/node_modules/chosen-js/chosen.min.css">
    <script src="/boneform-js/node_modules/chosen-js/chosen.jquery.min.js"></script>
    <script src="/boneform-js/src/plugins/chosen/controls/chosenselect.js"></script>

    <!-- Datepicker
     -->
    <link rel="stylesheet" type="text/css" href="/boneform-js/node_modules/pickadate/lib/themes/default.css">
    <link rel="stylesheet" type="text/css" href="/boneform-js/node_modules/pickadate/lib/themes/default.date.css">
    <link rel="stylesheet" type="text/css" href="/boneform-js/node_modules/pickadate/lib/themes/default.time.css">
    <script src="/boneform-js/node_modules/pickadate/lib/picker.js"></script>
    <script src="/boneform-js/node_modules/pickadate/lib/picker.date.js"></script>
    <script src="/boneform-js/node_modules/pickadate/lib/picker.time.js"></script>
    <script src="/boneform-js/src/plugins/pickadate/controls/datepicker.js"></script>

    <!-- TinyMCE
    -->
    <script src="/boneform-js/node_modules/tinymce/tinymce.js"></script>
    <!--
    <script src="/boneform-js/node_modules/tinymce/tinymce.jquery.js"></script>
    -->
    <script src="/boneform-js/node_modules/tinymce/jquery.tinymce.js"></script>
    <script src="/boneform-js/src/plugins/tinymce/controls/tinymce.js"></script>

    <style>
        html {
            overflow-y: scroll;
        }

        form .form-group .help-text {
            font-weight: normal;
            font-style: italic;
            /*
            visibility: hidden;
            display: none;
            */
        }
        form .form-group.is-active .help-text {
            visibility: visible;
            display: block;
        }

        /*
        .tab-content .tab-pane {
            padding: 1em 0;
        }
        */

        /** https://codepen.io/joshadamous/pen/wJKzv **/
        body {
            background-color: transparent;
        }

        h3 {
            margin-top: 0;
        }
        .badge {
            background-color: #777;
        }
        .tabs-left {
            margin-top: 3rem;
        }
        .tab-content {
            border-left: 1px solid #000;
        }
        .nav-tabs {
            float: left;
            border-bottom: 0;
        }
        .nav-tabs li {
            float: none;
            margin: 0;
            width: 130px;
        }
        .nav-tabs li a {
            margin-right: 0;
            border: 0;
            border-radius: 0;
            /**background-color: #333;*/
        }
        .nav-tabs li a:hover {
            background-color: #eee;
        }
        .nav-tabs .glyphicon {
            color: #fff;
        }
        .nav-tabs .active .glyphicon {
            color: #333;
        }

        .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
            border: 0;
            background-color: #000;
            color: #fff;
        }

        .tab-content {
            margin-left: 130px;
        }
        .tab-content .tab-pane {
            display: none;
            background-color: #fff;
            padding: 1.6rem;
            overflow-y: auto;
            overflow-x: visible;
            min-height: 300px;
        }
        .tab-content .active {
            display: block;
            overflow: visible;
        }

        .list-group {
            width: 100%;
        }
        .list-group .list-group-item {
            height: 50px;
        }
        .list-group .list-group-item h4, .list-group .list-group-item span {
            line-height: 11px;
        }

        form button[type="submit"] {
            margin: 0.5em 0;
        }

        form div.form-submit {
            text-align: right;
        }

        /** Chosen **/
        .chosen-container {
            min-width: 50%;
        }
    </style>
</head>
<body>

<div id="form-container" class="container">
    <h1>BoneForm with CakePHP</h1>

    <form id="cake-form" style="display: none;" class="form-horizontal">
        <fieldset data-legend="Meta" data-fields="id,parent_id,type,shop_category_id"></fieldset>
        <fieldset>
            <legend>Hello</legend>
            <span data-field="sku"></span>
            <span data-field="title"></span>
            <span data-field="slug"></span>
            <div class="row">
                <div class="col-md-6">
                    <span data-field="teaser_html"></span>
                </div>
                <div class="col-md-6">
                    <span data-field="desc_html"></span>
                </div>
            </div>
        </fieldset>
        <fieldset data-fields="preview_image_file,featured_image_file" data-legend="Images"></fieldset>
        <fieldset data-fields="is_published,publish_start_date,publish_end_date">
            <legend>Publish <i class="fa fa-circle text-success"></i></legend>
        </fieldset>
        <fieldset data-fields="" data-legend="other"></fieldset>

        <fieldset id="schema">
            <legend>*Schema</legend>
            <pre id="debug-schema"></pre>
        </fieldset>
        <fieldset id="data">
            <legend>*Data</legend>
            <pre id="debug-data"></pre>
        </fieldset>
    </form>
</div> <!-- #form-container -->



<script>

    function debugSchema(schema) {
        $('#debug-schema').html(JSON.stringify(schema, null, 2));
    }

    function debugData(data) {
        $('#debug-data').html(JSON.stringify(data, null, 2));
    }


    $(document).ready(function() {

        var schemaUrl = "<?= $viewUrl; ?>";
        $.getJSON(schemaUrl, function(result) {
            console.log(result);
            debugSchema(result);

            var formOpts = _.extend({
                formValidate: false,
                //model: model,
                //method: 'POST',
                //action: 'json.php',
                ajax: 'json',
                //validate: 'default',
                idPrefix: 'cake_',
                formAttrs: {
                    //id: 'my-cake-form',
                    //'class': 'form-horizontal'
                },
                //handler: Backbone.Form.handlers.Default
                /*
                 submit: function(form, data) {
                 console.log("Submitting form");
                 return false;
                 },
                 validate: function() {
                 console.log("Validating form");
                 return false;
                 }
                 */
            }, _.pick(result, ['ajax', 'action', 'schema', 'data']));
            //formOpts.model = model;

            var form1 = new Backbone.Form(formOpts);
            form1.on('change', function(field, form) {
                console.log("[change] field '" + field.key + "'" + " changed to '" + field.getValue() + "'");
                debugData(form.getData());
            });
            form1.on('focus', function(field, form) {
                console.log("[focus] field '" + field.key + "'");
            });
            form1.on('blur', function(field, form) {
                console.log("[blur] field '" + field.key + "'");
            });
            form1.on('submit', Backbone.Form.handlers.Json);
            //$('#form-container').append(form1.render().$el);

            form1.setElement($('#cake-form'));
            form1.on('afterRender', function() {
                console.log("AFTER RENDER");

                var $form = $('#cake-form');
                var $menu = $('<ul>', { id: 'tabs-menu', 'class': 'nav nav-tabs', role: 'tabslist' });

                var paneIdx = 0;
                $form.find('fieldset').each(function() {
                    var paneId = 'pane' + paneIdx;
                    var paneClass = 'tab-pane';

                    var $tabLink = $('<a>', { href: '#' + paneId, 'aria-controls': paneId, role: 'tab', 'data-toggle': 'tab'}).html($(this).find('legend').html());
                    var $tab = $('<li>', { role: 'presentation'}).html($tabLink);
                    if (paneIdx === 0) {
                        $tab.addClass('active');
                        paneClass += ' active';
                    }
                    $menu.append($tab);
                    $(this).wrap($('<div>', {'class': paneClass, id: paneId, role: 'tabpanel' }));
                    paneIdx++;
                });

                $menu.on('click', 'a', function (e) {
                    e.preventDefault();
                    $(this).tab('show')
                });
                $form.wrapInner($('<div>', { 'class': 'tab-content'}));
                $form.prepend($menu);
                $form.wrapInner($('<div>', { 'class': 'tabs-left' }));
                $form.show();

            });
            form1.render().$el.show();

        })

    });





</script>
</body>
</html>