<?php
declare(strict_types=1);

namespace Admin\View\Helper;

/**
 * Class DataTablesJsHelper
 * @package Admin\View\Helper
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class DataTableJsHelper extends DataTableHelper
{
    /*
    protected $_defaultParams = [
        'processing' => false,
        'serverside' => false,
        'ajax' => null,
        //'data' => [],
        'paging' => true,
        'ordering' => true,
        'info' => true,
    ];

    protected $_jsOptionsId;
    protected $_jsOptions = [];
    protected $_columns = [];
    protected $_data = [];

    protected $_renderTable = true;
    protected $_renderScript = true;
    */

    protected $_jsOptions = [
        'processing' => true,
        //'serverSide' => true,
        //'ajax' => Router::url($url),
        'columns' => [], // array_values($columns),
        //'order' => $this->_buildDataTableOrder($order, $columns),
        'ordering' => false,
        'searching' => false,
        'paging' => false,
        'lengthChange' => false,
        'lengthMenu' => [10, 25, 50, 75, 100],
        'info' => false,
        //'lengthMenu' => $this->_config['lengthMenu'],
        'pageLength' => 10,
        'select' => false,
        'scrollX' => false,
    ];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        //$this->Html->css('/admin/adminlte/plugins/datatables/jquery.dataTables.min.css', ['block' => true]);
        //$this->Html->css('/admin/adminlte/plugins/datatables/dataTables.bootstrap.css', ['block' => true]);
        //$this->Html->script('/admin/adminlte/plugins/datatables/jquery.dataTables.js', ['block' => true]);
        //$this->Html->script('/admin/adminlte/plugins/datatables/dataTables.bootstrap.js', ['block' => true]);

        //$this->Html->css('Admin./js/datatables/dataTables.bootstrap.css', ['block' => true]);
        //$this->Html->script('Admin.datatables/jquery.dataTables', ['block' => true]);
        //$this->Html->script('Admin.datatables/dataTables.bootstrap', ['block' => true]);

        $this->Html->css('/admin/vendor/DataTables/datatables.min.css', ['block' => true]);
        $this->Html->script('/admin/vendor/DataTables/datatables.min.js', ['block' => true]);
        $this->Html->script('/admin/js/admin.datatables.js', ['block' => true]);
    }

    /**
     * @param array $options Javascript options
     * @return $this
     */
    public function options($options = null)
    {
        if ($options === null) {
            return $this->_jsOptions;
        }

        $this->_jsOptions = array_merge($this->_jsOptions, $options);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function _initialize()
    {
        $jsOpts = (array)$this->getParam('extra');
        //$jsOpts['lengthChange'] = false;
        //$jsOpts['lengthMenu'] = [ 10, 25, 50, 75, 100 ];

        // filtering
        //if ($this->_params['filter']) {
        //    $jsOpts['searching'] = true;
        //}

        // paging
        if ($this->_params['paging']) {
            $jsOpts['paging'] = true;
        }

        // sorting
        if ($this->_params['sortable']) {
            $jsOpts['ordering'] = true;
            $jsOpts['order'] = [0, 'asc'];
        }

        // ajax
        //if ($this->_params['ajax']) {
        //}

        // language
        $jsOpts['language'] = [
            'processing' => __d('admin', 'Loading ...'),
        ];

        $this->options($jsOpts);
    }

    /**
     * {@inheritDoc}
     */
    protected function _renderFilterRow()
    {
        // Search is injected by DataTable JS
        return '';
    }

    /**
     * {@inheritDoc}
     */
    protected function _renderPagination()
    {
        // Pagination is injected by DataTable JS
        return '';
    }

    /**
     * {@inheritDoc}
     */
    protected function _buildPaginationFieldLabel($fieldName, $field)
    {
        // Pagination is injected by DataTable JS
        return h($field['label']);
    }

    /**
     * {@inheritDoc}
     */
    protected function _renderScript($block = null)
    {
        $jsTable = $this->_jsOptions;
        if (empty($jsTable['columns'])) {
            $columns = $this->_buildDataTableColumns();
            $jsTable['columns'] = array_values($columns);
        }
        //debug($jsTable);
        $scriptTemplate = <<<SCRIPT
$(document).ready(function(){
    var userOpts = %s;
    console.log("USEROPTS", userOpts);
    var opts = _.extend({}, userOpts, {
        columnDefs: [
            {
             targets: '_all',
             data: null,
             render: function ( data, type, row, meta ) {console.log("render", type, this); return data;},
             createdCell: function (td, cellData, rowData, row, col) {console.log("cell", row, col, this);}
            }
        ]
    });
    console.log("OPTS", opts);
    $("#%s").DataTable(opts);
//    .on( 'init.dt', function () {
//        $(this).dataTable().api().columnDefs() = [
//        { targets : '_all', render: function() {console.log("render");} }
//        ];
//        $(this).dataTable().api().columns().every( function () {
//            var column = this;
//            var select = $('<select><option value=""></option></select>')
//                .appendTo( $(column.footer()).empty() )
//                .on( 'change', function () {
//                    var val = $.fn.dataTable.util.escapeRegex(
//                        $(this).val()
//                    );
//
//                    column
//                        .search( val ? '^'+val+'$' : '', true, false )
//                        .draw();
//                } );
//
//            column.data().unique().sort().each( function ( d, j ) {
//                select.append( '<option value="'+d+'">'+d+'</option>' )
//            } );
//        } );
//    })
//    .DataTable(opts);
});
SCRIPT;
        $script = sprintf(
            $scriptTemplate,
            json_encode($jsTable),
            $this->getParam('id')
        );

        return $this->Html->scriptBlock($script, ['safe' => false, 'block' => $block]);
    }

    /**
     * @return array
     */
    protected function _buildDataTableColumns()
    {
        $columns = [];
        foreach ($this->_fields as $fieldName => $field) {
            $columns[$fieldName] = [
                'data' => $fieldName,
                'title' => $field['label'],
                'class' => $field['class'],
                'searchable' => false,
                'visible' => true,
                'sortable' => true,
                /*
                'render' => [
                    '_' => $fieldName,
                    'filter' => $fieldName,
                    'display' => $fieldName
                ]
                */
            ];
        }

        return $columns;
    }

    /**
     * @param array $order Order list
     * @param array $columns Columns list
     * @return array
     */
    protected function _buildDataTableOrder(&$order, &$columns)
    {
        $_order = [];
        foreach ($order as $col => $dir) {
            $i = 0;
            reset($columns);
            do {
                if (key($columns) == $col) {
                    $_order[] = [$i, $dir];
                    break;
                }
                $i++;
            } while (next($columns));
        }

        return $_order;
    }
}
