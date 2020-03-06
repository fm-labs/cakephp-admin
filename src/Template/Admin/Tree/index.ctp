<?php $this->Breadcrumbs->add(__d('backend','Tree Viewer')); ?>
<?php $this->loadHelper('Backend.JsTree'); ?>
<?php $this->assign('heading', __d('backend','Tree Viewer')); ?>
<div class="form">

    <div class="row" style="margin-top: 1rem;">
        <div class="col-md-5">
            <?php
            $selected = $this->request->getQuery('id');
            $dataUrl = $this->get('dataUrl', ['action' => 'treeData']);
            $sortUrl = $this->get('sortUrl', ['action' => 'treeSort']);

            $defaultJsTree = [
                'core' => [
                    'multiple' => false,
                    'data' => [
                        'url' => $this->Html->Url->build($dataUrl)
                    ],
                    'check_callback' => true,
                ],
                'plugins' => ['wholerow', 'state', 'dnd']
            ];
            $jsTree = $this->get('jsTree', $defaultJsTree);
            ?>
            <?= $this->Html->div('be-index-tree', 'Loading ...', [
                'id' => 'index-tree',
                'data-url' => $this->Html->Url->build($dataUrl),
                'data-active' => $selected
            ]); ?>

            <script>

                var sortUrl = '<?= $this->Html->Url->build($sortUrl) ?>';
                var jsTreeConf = jsTreeConf || JSON.parse('<?= json_encode($jsTree); ?>');

                if (!jsTreeConf.core || !jsTreeConf.data || !jsTreeConf.data.data) {
                    jsTreeConf.core.data.data = function (node) {
                        return {'id': node.id};
                    };
                }


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
                                    r.push(data.instance.get_node(data.selected[i]).data);
                                }
                                console.log('Selected: ' + r.join(', '));
                                $('#menu-info-container').html('<pre>' + JSON.stringify(r, null, 2) + '</pre>');
                            }

                        }).on('move_node.jstree', function (e, data) {
                            console.log('Moved');
                            console.log(data);

                            var nodeId = data.node.id;

                            var oldParentId = data.old_parent;
                            var oldPosition = data.old_position;
                            var newParentId = data.parent;
                            var newPosition = data.position;

                            /*
                            var oldParentNode = $tree.jstree(true).get_node(oldParentId);
                            var newParentNode = $tree.jstree(true).get_node(newParentId);

                            console.log("old parent node", oldParentNode);
                            console.log("new parent node", newParentNode);
                            */


                            var postData = {
                                nodeId: nodeId,
                                oldParentId: oldParentId,
                                oldPos: oldPosition,
                                newParentId: newParentId,
                                newPos: newPosition
                            };

                            console.log("Request", sortUrl, postData);

                            $.ajax({
                                url: sortUrl,
                                method: 'POST',
                                data: postData
                            }).done(function(response) {
                                console.log("Response", response);

                                if (response.result && response.result.error) {
                                    alert(response.result.error);
                                    return;
                                }

                                // Update node data
                                $tree.jstree(true).get_node(nodeId).data = response.node;

                            }).fail(function(error) {
                                console.error(error);
                                alert(error);
                            })

                        })

                        .jstree(jsTreeConf);

                        $(document)

                            .on('dnd_scroll.vakata', function (e, data) {
                                //console.log("dnd_scroll");
                                //console.log(data);
                            })


                            .on('dnd_start.vakata', function (e, data) {
                                //console.log("dnd_start");
                                //console.log(data);
                            })

                            .on('dnd_stop.vakata', function (e, data) {
                                //console.log("dnd_stop");
                                //console.log(data);
                            });
                });

            </script>
        </div>
        <div class="col-md-4" id="menu-info-container">
        </div>
    </div>



</div>