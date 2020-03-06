<?php
use Cake\Routing\Router;

$modelName = $this->request->getQuery('model');
$id = $this->request->getQuery('id');
$columnsUrl = Router::url(['action' => 'columns', 'model' => $modelName]);
$rowsUrl = Router::url(['action' => 'rows', 'model' => $modelName]);
$tableUrl = Router::url(['action' => 'table', 'model' => $modelName]);
?>
<html>
<head>
    <title>FooTable</title>

    <link rel="stylesheet" type="text/css" href="/boneform-js/node_modules/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/boneform-js/node_modules/font-awesome/css/font-awesome.min.css" />

    <script src="/boneform-js/node_modules/jquery/dist/jquery.js"></script>
    <script src="/boneform-js/node_modules/bootstrap/dist/js/bootstrap.js"></script>

    <?= $this->Html->css('Backend./js/footable-bootstrap/css/footable.bootstrap.css'); ?>
    <?= $this->Html->script('Backend./js/footable-bootstrap/js/footable.js'); ?>

    <style>
        html {
            overflow-y: scroll;
        }
    </style>
</head>
<body>

<div id="dt-container" class="container">
    <h1>FooTable with CakePHP</h1>

    <table id="test-table" class="table" data-paging="true" data-sorting="true" data-filtering="true" data-paging-size="15">
        <thead>
        <tr>
            <th data-name="id">ID</th>
            <th data-name="preview_image_file" data-type="object" data-formatter="MyFormatters.mediafile" data-filterable="false">Preview</th>
            <th data-name="title" data-formatter="MyFormatters.link">Title</th>
            <th data-name="shop_category" data-type="object" data-formatter="MyFormatters.shop_category">Category</th>
            <th data-name="is_buyable" data-type="boolean" data-breakpoints="xs" data-formatter="MyFormatters.boolean" data-filterable="false">Buyable</th>
            <th data-name="is_published" data-type="boolean" data-breakpoints="xs" data-formatter="MyFormatters.boolean" data-filterable="true">Published</th>
            <th data-name="_actions" data-formatter="MyFormatters.actions">Actions</th>
        </tr>
        </thead>
    </table>
    <!--
    <table id="test-table" data-url="<?= $tableUrl; ?>"></table>
    -->
</div> <!-- #dt-container -->

<script>
    var MyFormatters = {

        boolean: function(value, options, rowData){
            //console.log(value, typeof value);
            var clazz = (value === "true") ? "text-success" : "text-danger";
            return '<span class="'+ clazz +'"><i class="fa fa-circle"></i></span>';
        },
        link: function(value, options, rowData){
            return '<a href="#" data-id="' + rowData.id +'">' + value + '</a>';
        },
        mediafile: function(value, options, rowData){
            if (value === null || typeof value !== "object") return '<img width="30" src="" />';

            return  '<a href="' + value.full_url +'" target="_blank" data-original-value="' + value.path +'">' +
                        '<img width="30" src="' + value.full_url + '" />' +
                    '</a>';
        },
        shop_category: function(value, options, rowData){
            return ( value !== null && typeof value === "object") ? '<span>' + value.name + '</span>' : value + '';
        },
        actions: function(value, options, rowData){
            return "Action!!";
        }
    };

    function createFilter(colName, statuses) {

        if (!statuses) {
            statuses = {'true': colName + ' enabled', 'false':  colName + ' disabled'}
        }

        return FooTable.Filtering.extend({
            construct: function(instance){
                this._super(instance);
                this.statuses = statuses;
                this.def = 'Any Status';
                this.defVal = '';
                this.$status = null;
            },
            $create: function(){
                this._super();
                var self = this,
                    $form_grp = $('<div/>', {'class': 'form-group'})
                        .append($('<label/>', {'class': 'sr-only', text: 'Status'}))
                        .prependTo(self.$form);

                self.$status = $('<select/>', { 'class': 'form-control' })
                    .on('change', {self: self}, self._onStatusDropdownChanged)
                    .append($('<option/>', {text: self.def, val: self.defVal}))
                    .appendTo($form_grp);

                $.each(self.statuses, function(i, status){
                    self.$status.append($('<option/>').text(status).val(i));
                });
            },
            _onStatusDropdownChanged: function(e){
                var self = e.data.self,
                    selected = $(this).val();
                if (selected !== self.defVal){
                    console.log("add filter", colName);
                    //var q = (selected === "1") ? "true" : "false";
                    console.log("Selected", selected, typeof selected);
                    self.addFilter(colName, selected, [colName]);
                } else {
                    console.log("remove filter", colName);
                    self.removeFilter(colName);
                }
                self.filter();
            },
            draw: function(){
                this._super();
                var status = this.find(colName);
                if (status instanceof FooTable.Filter){
                    console.log("draw", colName, status.query.val(), status);
                    this.$status.val(status.query.val());
                } else {
                    console.log("drawx", colName, this.defVal, status);
                    this.$status.val(this.defVal);
                }
            },
            filter: function(focus){
                var self = this;
                self.filters = self.ensure(self.filters);
                /**
                 * The before.ft.filtering event is raised before a filter is applied and allows listeners to modify the filter or cancel it completely by calling preventDefault on the jQuery.Event object.
                 * @event FooTable.Filtering#"before.ft.filtering"
                 * @param {jQuery.Event} e - The jQuery.Event object for the event.
                 * @param {FooTable.Table} ft - The instance of the plugin raising the event.
                 * @param {Array.<FooTable.Filter>} filters - The filters that are about to be applied.
                 */
                return self.ft.raise('before.ft.filtering', [self.filters]).then(function(){
                    self.filters = self.ensure(self.filters);
                    if (focus){
                        var start = self.$input.prop('selectionStart'),
                            end = self.$input.prop('selectionEnd');
                    }
                    return self.ft.draw().then(function(){
                        if (focus){
                            self.$input.focus().prop({
                                selectionStart: start,
                                selectionEnd: end
                            });
                        }
                        /**
                         * The after.ft.filtering event is raised after a filter has been applied.
                         * @event FooTable.Filtering#"after.ft.filtering"
                         * @param {jQuery.Event} e - The jQuery.Event object for the event.
                         * @param {FooTable.Table} ft - The instance of the plugin raising the event.
                         * @param {FooTable.Filter} filter - The filters that were applied.
                         */
                        self.ft.raise('after.ft.filtering', [self.filters]);
                    });
                });
            },
        });
    }

    function createPaging(url, settings) {

        var MyPaging = FooTable.Paging.extend({

            url: url,

            init: function() {
                this._super();

                console.log(this);

                /*
                 */
                this.ft.$el.on('before.ft.paging', function(ev, ft, pager) {
                    //console.log("[paging] before event", ev, ft);
                    //console.log("pager", pager);

                    ft.urlCache.limit = pager.size;
                    ft.urlCache.page = pager.page;
                    var url = ft.buildUrl('<?= $rowsUrl ?>');
                    console.log("[paging] urlCache", ft.urlCache, url);
                    //var url = '<?= $rowsUrl ?>&limit=' + pager.size + '&page=' + pager.page;
                    ev.promise = $.getJSON(url).then(function(rows) {
                        console.log("data fetched!", pager, rows);
                        ft.rows.load(rows.data);
                        ft.pageCount(rows.pageCount);

                    });
                })
            },

            predraw: function() {

                //this.total = Math.ceil(this.ft.rows.array.length / this.size);
                //this.total = 1;
                //this.current = this.current > this.total ? this.total : (this.current < 1 ? 1 : this.current);

                //this.totalRows = this.ft.rows.array.length;
                //if (this.totalRows > this.size){
                    //this.ft.rows.array = this.ft.rows.array.splice((this.current - 1) * this.size, this.size);
                //}
                this.formattedCount = this.format(this.countFormat);


                this.ft.urlCache.limit = this.size;
                this.ft.urlCache.page = (this.current) ? this.current : 1;
            },

            pageSize: function(value) {
                this._super(value); // @TODO Override pageSize() method
            },

            pageCount: function(value) {
                this.total = value;
            },

            _set: function(page){
                console.log("Set page", page);
                this._super(page);
            },

            setPage: function(page) {
                this._set(page);
            }
        });

        /*
        MyPaging.prototype.setPage = function(page) {
            this._set(page);
        };
        */

        /**
         * Gets or sets the current page count
         * @instance
         * @param {number} [value] - The new page size to use.
         * @returns {(number|undefined)}
         * @see FooTable.Paging#pageSize
         */
        FooTable.Table.prototype.pageCount = function(value){
            return this.use(FooTable.Paging).pageCount(value);
        };


        return MyPaging;
    }

    function createSorting() {

        return FooTable.Sorting.extend({

            /**
             * Performs the actual sorting against the {@link FooTable.Rows#current} array.
             * @instance
             * @protected
             */
            predraw: function () {
                // do nothing
            },

            _sort: function(column, direction) {
                console.log("_sort", column, direction);

                if (!this.allowed) return $.Deferred().reject('sorting disabled');
                var self = this;
                var sorter = new FooTable.Sorter(self.ft.columns.get(column), FooTable.Sorting.dir(direction));

                /**
                 * The before.ft.sorting event is raised before a sort is applied and allows listeners to modify the sorter or cancel it completely by calling preventDefault on the jQuery.Event object.
                 * @event FooTable.Sorting#"before.ft.sorting"
                 * @param {jQuery.Event} e - The jQuery.Event object for the event.
                 * @param {FooTable.Table} ft - The instance of the plugin raising the event.
                 * @param {FooTable.Sorter} sorter - The sorter that is about to be applied.
                 */
                return self.ft.raise('before.ft.sorting', [sorter]).then(function(){
                    //FooTable.arr.each(self.ft.columns.array, function(col){
                    //    if (col != self.column) col.direction = null;
                    //});
                    self.column = self.ft.columns.get(sorter.column);
                    if (self.column) self.column.direction = FooTable.Sorting.dir(sorter.direction);

                    self.ft.urlCache.sort = self.ft.columns.array[column].name;
                    self.ft.urlCache.direction = direction;
                    self.ft.firstPage();

                    //return self.ft.draw().then(function(){
                        /**
                         * The after.ft.sorting event is raised after a sorter has been applied.
                         * @event FooTable.Sorting#"after.ft.sorting"
                         * @param {jQuery.Event} e - The jQuery.Event object for the event.
                         * @param {FooTable.Table} ft - The instance of the plugin raising the event.
                         * @param {FooTable.Sorter} sorter - The sorter that has been applied.
                         */
                    //    self.ft.raise('after.ft.sorting', [sorter]);
                    //});
                });

            }
        });
    }

    jQuery(function($){

        $('#test-table')
            .on('ready.ft.table', function(ev, ft) {
                //console.log("[table] ready event", ev, ft);
                ft.firstPage();
            })
            /*
             .on('preinit.ft.paging', function(ev, ft, data) {
             console.log("[paging] preinit event", ev);
             console.log("ft", ft);
             console.log("data", data);
             })
             .on('init.ft.paging', function(ev, ft, data) {
             console.log("[paging] init event", ev);
             console.log("ft", ft);
             console.log("data", data);
             })
             */
        /*
         .footable({
         //"columns": $.get('<?= $columnsUrl ; ?>'),
         "rows": $.get('<?= $rowsUrl ; ?>'),
         components: {
         filtering: createFilter('is_buyable', {'false': 'Not Buyable', 'true': 'Buyable'}),
         paging: createPaging()
         }
         })
         */
        ;

        FooTable.Table.prototype.urlCache = {
            //foo: 'bar'
        };

        FooTable.Table.prototype.buildUrl = function(baseUrl) {
            var url = '';
            for (var k in this.urlCache) {
                if (this.urlCache.hasOwnProperty(k)) {
                    url = url + k + '=' + this.urlCache[k] + '&';
                }
            }

            if (baseUrl.indexOf('?') === false) {
                url = '?' + url;
            } else {
                url = '&' + url;
            }

            return baseUrl + url;
        };


        var ft = FooTable.init('#test-table', {
            //"rows": $.get('<?= $rowsUrl ; ?>'),
            components: {
                filtering: createFilter('is_buyable', {'false': 'Not Buyable', 'true': 'Buyable'}),
                paging: createPaging(),
                sorting: createSorting()
            }
        });

    });
</script>

</body>
</html>