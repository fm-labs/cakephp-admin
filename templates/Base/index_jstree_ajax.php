<?php
/**
 * Extendable HTML view template
 * A two-column template using jsTree as tree navigation and triggers jquery's ajax
 * to load html contents into the content column
 *
 * ---
 * Inject custom jsTree json config from the extending template
 * Example script, which can be inserted in the extending template:
 * <?php $this->start('jsTreeScript'); ?>
 * <script>
 * jsTreeConf = {
 *  core: {
 *    data: {
 *      data: function(node) {
 *        return { 'id': node.id };
 *      }
 *    }
 *  }
 * }
 * </script>
 * <?php $this->end(); ?>
 *
 *
 * Parameters:
 * @param dataUrl string Url to fetch tree data json
 * @param viewUrl string Url to fetch tree node content
 * @param jsTree array jsTree params. See jsTree documentation for options
 * @link https://www.jstree.com/
 */
$this->loadHelper('Sugar.JsTree');


$selected = $this->request->getQuery('id');
$dataUrl = $this->get('dataUrl', ['action' => 'treeData']);

$defaultJsTree = [
    'core' => [
        'multiple' => false,
        //'data' => [
        //    'url' => $this->Html->Url->build($dataUrl)
        //],
        'check_callback' => true,
    ],
    'plugins' => ['wholerow', 'state']
];
$jsTree = $this->get('jsTree', $defaultJsTree);
?>
<?= $this->Html->css('Admin.jstree/themes/admin/style.min', ['block' => true]); ?>
<?= $this->Html->script('Admin.jstree/jstree.min', ['block' => true]); ?>
<div class="index index-tree">

    <?php if ($this->fetch('heading')): ?>
        <div class="page-heading">
            <h1><?= $this->fetch('heading'); ?></h1>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-sm-4 col-md-4">
            <div class="panel panel-primary panel-nopad no-border">
                <?php if ($this->fetch('treeHeading')): ?>
                <div class="panel-heading">
                    <?= $this->fetch('treeHeading'); ?>
                </div>
                <?php endif; ?>

                <div class="panel-body">
                    <?= $this->Html->div('be-index-tree', 'Loading ...', [
                        'id' => 'index-tree',
                        'data-url' => $this->Html->Url->build($dataUrl),
                        'data-active' => $selected
                    ]); ?>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-md-8">
            <div id="index-tree-container">
                <?= $this->fetch('content'); ?>
            </div>
        </div>
    </div>

    <div id="index-tree-noview" style="display: none">
        <?= $this->fetch('noview', $this->element('Admin.Base/IndexTree/noview')); ?>
    </div>

</div>
<script>
    var jsTreeConf;
</script>
<?php
// Custom jstree config injection from 'jsTreeScript' block
echo $this->fetch('jsTreeScript');
?>
<script>

    jsTreeConf = jsTreeConf || JSON.parse('<?= json_encode($jsTree); ?>');

    /*
    if (!jsTreeConf.core || !jsTreeConf.core.data || !jsTreeConf.core.data.data) {
        jsTreeConf.core.data.data = function (node) {
            return {'id': node.id};
        };
    }
    */
    if (!jsTreeConf.core || !jsTreeConf.core.data) {
        jsTreeConf.core.data = {};
        jsTreeConf.core.data.data = function (node) {
            console.log("data");
            return {'id': node.id};
        };
        jsTreeConf.core.data.url = function (node) {
            console.log("url");
            console.log(node);
            var dataUrl = '<?php echo $this->Html->Url->build($dataUrl); ?>';
            if (node.id == '#') {
                return dataUrl;
            }
            var childrenUrl = node.data.children_url;
            if (!childrenUrl) {
                console.warn("No children url has been set");
                return dataUrl;
            }
            return childrenUrl;
        };
    }
    console.log("hello");
    console.log(jsTreeConf);


    $(document).ready(function() {

        var selected = {};
        var path;
        var $tree = $('#index-tree');
        var $container = $('#index-tree-container');
        var $noview = $('#index-tree-noview');

        $.jstree.defaults.checkbox.three_state = false;
        $.jstree.defaults.checkbox.cascade = 'up+undetermined';

        $.jstree.defaults.dnd.is_draggable = function() { return true; };

        $tree
            .on('ready.jstree', function(e) {
                var selected = $tree.data('active');

                if (selected) {
                    $tree.jstree('deselect_all', true);
                    $tree.jstree('select_node', [selected], false, false);

                }
            })
            .on('changed.jstree', function (e, data) {
                var i, j, r = [];
                //console.log(data);
                if (data.action === "select_node") {
                    for(i = 0, j = data.selected.length; i < j; i++) {
                        r.push(data.instance.get_node(data.selected[i]).id);
                    }

                    //console.log('Selected: ' + r.join(', '));

                    var config = '';
                    var url = data.node.data.viewUrl;
                    if (url) {
                        Admin.Ajax.loadHtml($container, url, {
                            data: {'selected': r },
                        }).always(function(ev) {
                            // history pushstate
                            /*
                            if (!!window.history) {
                                if (history.state && history.state.selected && _.isEqual(history.state.selected, r)) {
                                    console.log("ReplaceState!");
                                    history.replaceState({ context: 'tree', selected: r}, '', url);
                                } else {
                                    console.log("PushState");
                                    history.pushState({ context: 'tree', selected: r}, '', url);
                                }
                            }
                            */

                        });
                    } else {
                        $container.html($noview.html());
                    }

                }

            })
            .jstree(jsTreeConf);

        /*
         .on('move_node.jstree', function (e, data) {
         console.log('Moved');
         console.log(data);

         var movedId = data.node.id;

         var movedB
         });
         */

        /*
         $(document)

         .on('dnd_scroll.vakata', function (e, data) {
         console.log("dnd_scroll");
         console.log(data);
         })


         .on('dnd_start.vakata', function (e, data) {
         console.log("dnd_start");
         console.log(data);
         })

         .on('dnd_stop.vakata', function (e, data) {
         console.log("dnd_stop");
         console.log(data);
         })
         */
        /*
        $(window).on('popstate', function(ev) {
            var state = ev.originalEvent.state;
            console.log("Popstate! ", location.href, state, ev);

            if (state !== null && state.context && state.context === "tree") {

                console.log("Selecting node", state.selected);

                $tree.jstree('deselect_all', true);
                $tree.jstree('select_node', state.selected);

                ev.stopPropagation();
            }

        });
        */
    });

</script>