<?php
$this->loadHelper('Backend.FooTable');
?>
<div class="index">
    <div class="box">
        <div class="box-body">
            <?php
            $table = $this->get('dataTable');
            //$table['id'] = 'foo-table';
            echo $this->FooTable->create($table)
                ->setData($this->get('result'))
                ->render(['script' => false]);
            $domId = $this->FooTable->getParam('id');
            //$rowsUrl = \Cake\Routing\Router::url(['plugin' => 'Backend', 'controller' => 'FooTables', 'action' => 'rows', 'model' => $table['model']]);
            $rowsUrl = $this->get('rowsUrl', '');
            ?>
        </div>
    </div>
    <?php debug($rowsUrl); ?>
    <?php debug($table); ?>
</div>
<script>

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

    function createPaging(url, settings) {

        console.log("PAGNG", url);

        var MyPaging = FooTable.Paging.extend({

            url: url,

            init: function() {
                this._super();

                //console.log(this);

                /*
                 */
                var self = this;
                this.ft.$el.on('before.ft.paging', function(ev, ft, pager) {
                    //console.log("[paging] before event", ev, ft);
                    //console.log("pager", pager);

                    ft.urlCache.limit = pager.size;
                    ft.urlCache.page = pager.page;
                    var dataUrl = ft.buildUrl(self.url);
                    console.log("[paging] urlCache", self.url, ft.urlCache, dataUrl);
                    ev.promise = $.getJSON(dataUrl).then(function(rows) {
                        console.log("data fetched!", pager, rows);
                        ft.pageCount(rows.pageCount);
                        ft.rows.load(rows.data);

                        setTimeout(function() {
                            Backend.Renderer.trigger('docready', ft.$el);
                        }, 50);

                    });
                });
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
                console.log("updating pageSize to ", value);
                this._super(value); // @TODO Override pageSize() method
            },

            pageCount: function(value) {
                console.log("updating pageCount to ", value);
                this.total = value;
                //this.predraw();
                //this.draw();
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


    $(document).ready(function() {
        var tblId = '#<?= $domId; ?>';

        var $tbl = $(tblId);
        $tbl.on('ready.ft.table', function(ev, ft) {
               //console.log("[table] ready event", ev, ft);
               ft.firstPage();
           });


        var tbl = FooTable.init(tblId, {
            components: {
                //filtering: createFilter('is_buyable', {'false': 'Not Buyable', 'true': 'Buyable'}),
                paging: createPaging('<?= $rowsUrl ?>'),
                sorting: createSorting()
            }
        });
    });
</script>
