<div class="filepicker">

    <div class="ui grid">
        <div class="row">
            <div class="four wide column">
                <div class="folder-container">
                    <ul>
                        <?php foreach ($this->get('folders') as $dir): ?>
                            <li class="folder"><?= h($dir); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="folder-selected">

                </div>
            </div>
            <div class="eight wide column">
                <div class="files-container">
                    <ul>
                        <?php foreach ($this->get('files') as $file): ?>
                            <li class="file">
                                <?= $this->Ui->link(basename($file), '#', ['data-path' => $file]); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="four wide column">
                <div class="files-selected"></div>
            </div>
        </div>
    </div>

</div>

<?php debug($folders); ?>
<?php debug($files); ?>
<?= $this->Html->css('Backend.jstree/themes/default/style.min'); ?>
<?= $this->Html->script('Backend.jstree/jstree.min'); ?>
<script>
    $(document).ready(function() {
        var $scontainer = $('.filepicker .files-selected');
        var $fcontainer = $('.filepicker .files-container');
        var selected = {};

        $.jstree.defaults.checkbox.three_state = false;
        $.jstree.defaults.checkbox.cascade = 'up+undetermined';

       $('.filepicker .folder-container')
       .on('changed.jstree', function (e, data) {
           var i, j, r = [];
               console.log(data);
           if (data.action === "select_node") {
               for(i = 0, j = data.selected.length; i < j; i++) {
                   r.push(data.instance.get_node(data.selected[i]).id);
               }
               $('.filepicker .folder-selected').html('Selected: ' + r.join(', '));

               /*
               $.ajax({
                   method: 'POST',
                   url: 'treeFiles.json',
                   dataType: 'json',
                   data: {'selected': r },
                   success: function(data) {
                       $fcontainer.html('');

                       for(var i in data) {
                           var file = data[i];

                           $('<div>', {'class': 'file'}).html($('<img>', {
                               'data-id': file.id,
                               'data-src': file.id,
                               'alt': file.text
                           })).appendTo($fcontainer);
                       }
                   }
               });
               */
           }

       })
       .jstree({
           "core" : {
               "themes" : {
                   //"variant" : "large"
               },
               'data' : {
                   'url': function (node) {
                       console.log(node);
                       return 'treeData.json';
                   },
                   'data': function (node) {
                       console.log(node)
                       return {'id': node.id};
                   },
               }
           },
           "checkbox" : {
               "keep_selected_style" : false
           },
           "plugins" : [ "wholerow", "changed", "state", "checkbox" ]
       });

        /*
        $(document).on('click', '.filepicker .files-container .file img', function(e) {

            var id = $(this).data('id');

            if (id in selected) {
                return;
            }

            var $file = $('<div>', {'class': 'file'})
                .append($('<img>', {
                        'data-id': $(this).data('id'),
                        'src': $(this).data('src'),
                        'alt': $(this).attr('alt'),
                        'title': $(this).attr('title')
                    }).css({ 'max-width': '100px' })
                )
                .append($('<div class="caption">').html(id))
                .appendTo($scontainer);

            selected[id] = true;
        });

        $(document).on('click', '.filepicker .files-selected .file img', function() {

            var id = $(this).data('id');

            if (id in selected) {
                delete selected[id];
            }
           $(this).parent().remove();
        });
        */

    });
</script>
