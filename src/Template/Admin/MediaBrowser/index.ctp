<?= $this->Html->css('Backend.jstree/themes/default/style.min', ['block' => true]); ?>
<?= $this->Html->css('Backend.filebrowser', ['block' => true]); ?>
<?= $this->Html->script('Backend.underscore-min', ['block' => true]); ?>
<?= $this->Html->script('Backend.backbone-min', ['block' => true]); ?>
<?= $this->Html->script('Backend.jstree/jstree.min', ['block' => true]); ?>
<div class="index">

    <div id="browser-wrapper">

        <div id="browser-toolbar" class="actions">
            <div class="ui labeled icon menu">
                <a class="item">
                    <i class="outline folder icon"></i>
                    New Folder
                </a>
                <a class="item">
                    <i class="outline file icon"></i>
                    New File
                </a>
                <a class="item">
                    <i class="upload icon"></i>
                    Upload files
                </a>
            </div>
        </div>
        <h4 id="browser-path" class="ui top attached header">
            <div class="ui active small inline loader"></div> Loading
        </h4>

        <div id="browser-container" class="ui attached segment">
            <div class="ui grid">
                <div class="row">
                    <div class="four wide column">
                        <div id="browser-folders">
                            Folders
                        </div>
                    </div>
                    <div class="twelve wide column">
                        <div id="browser-files">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<script type="text/template" id="file_template">
    <li>
        <div class="file">
            <div class="thumbnail">
                <% if ( typeof(icon) === 'string' && ( icon.indexOf('/') === 0 || icon.indexOf('http') === 0 ) ) { %>
                <img src="<%= icon %>" alt="<%= text %>" title="<%= id %>" />
                <% } else if (typeof(icon) === 'boolean' && icon === true) { %>
                <i class="ui big icon outline file"></i>
                <% } %>
            </div>
            <div class="desc">
                <span class="name"><%= text %></span><br />
                <span class="id"><small><%= id %></small></span>
            </div>
            <div class="actions">
                <% _.each(actions, function(action) { %>
                <a class="item" href="<%= action.url %>">
                    <i class="<%= action.icon %> icon"></i>
                    <%= action.title %>
                </a>
                <% }); %>
            </div>
        </div>
    </li>
</script>
<script>

    var File = Backbone.Model.extend({

    });

    var Files = Backbone.Collection.extend({
        model: File
    });

    var FilesView = Backbone.View.extend({
        el: $('#browser-files'), // attaches `this.el` to an existing element.

        // `initialize()`: Automatically called upon instantiation. Where you make all types of bindings, _excluding_ UI events, such as clicks, etc.
        initialize: function(){
            _.bindAll(this, 'render'); // fixes loss of context for 'this' within methods

            this.collection = new Files();
            this.collection.bind('add', this.appendFile);


            this.render(); // not all views are self-rendering. This one is.
        },
        // `render()`: Function in charge of rendering the entire view in `this.el`. Needs to be manually called by the user.
        render: function(){      var self = this;
            //$(this.el).append("<button id='add'>Add list item</button>");
            //$(this.el).append("<h1>Backbone File Browser</h1>");
            $(this.el).append("<ul></ul>");
            _(this.collection.models).each(function(file){ // in case collection is not empty
                self.appendFile(file);
            }, this);
        },

        appendFile: function(file) {
            console.log("Append file");
            console.log(file);
            //$('ul', this.el).append("<li class=\"file\"><div>"+file.get('text')+"</div></li>");

            // Compile the template using underscore
            var template = _.template( $("#file_template").html() );
            // Load the compiled HTML into the Backbone "el"
            $('ul', this.el).append(template(file.toJSON()));
            //console.log(template(file.toJSON()));
        }
    });


    $(document).ready(function() {
        //return;

        var selected = {};
        var path;

        $.jstree.defaults.checkbox.three_state = false;
        $.jstree.defaults.checkbox.cascade = 'up+undetermined';

        $('#browser-folders')
            .on('changed.jstree', function (e, data) {
                var i, j, r = [];
                console.log(data);
                if (data.action === "select_node") {
                    for(i = 0, j = data.selected.length; i < j; i++) {
                        r.push(data.instance.get_node(data.selected[i]).id);
                    }
                    //$('.filepicker .folder-selected').html('Selected: ' + r.join(', '));
                    console.log('Selected: ' + r.join(', '));

                    path = r.join('/');
                    console.log('Path: ' + path);

                    var config = '';
                    var url = 'filesData.json?config='+config+'&id='+path;

                    $.ajax({
                        method: 'GET',
                        url: url,
                        dataType: 'json',
                        data: {'selected': r },
                        beforeSend: function() {
                          $('#browser-files').html('<div class="ui active small inline loader"></div>');
                        },
                        success: function(data) {

                            $('#browser-path').html("<i class=\"ui green outline folder icon\" />&nbsp;" + path);

                            // no files in folder
                            if (data.length === 0) {
                                $('#browser-files').html('<div class="ui info message"><i class="info icon"></i>No files in folder ' + path + '</div>');
                                return;
                            }

                            $('#browser-files').html("");

                            var filesView = new FilesView();
                            for(var i in data) {
                                var file = data[i];

                                filesView.appendFile(new File(file));
                            }
                        }
                    });

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
                "plugins" : [ "wholerow", "changed", "state" ] // , "checkbox"
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