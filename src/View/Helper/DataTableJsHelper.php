<?php

namespace Backend\View\Helper;

use Cake\ORM\ResultSet;
use Cake\Utility\Inflector;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;
use Cake\View\StringTemplateTrait;
use Cake\View\View;

/**
 * Class DataTablesJsHelper
 * @package Backend\View\Helper
 * @property HtmlHelper $Html
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
        //'lengthMenu' => $this->_config['lengthMenu'],
        //'pageLength' => 10,
    ];

    /**
     * @param View $View
     * @param array $config
     */
    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);

        //$this->Html->css('/backend/adminlte/plugins/datatables/jquery.dataTables.min.css', ['block' => true]);
        //$this->Html->css('/backend/adminlte/plugins/datatables/dataTables.bootstrap.css', ['block' => true]);
        //$this->Html->script('/backend/adminlte/plugins/datatables/jquery.dataTables.js', ['block' => true]);
        //$this->Html->script('/backend/adminlte/plugins/datatables/dataTables.bootstrap.js', ['block' => true]);

        $this->Html->css('Backend./js/datatables/dataTables.bootstrap.css', ['block' => true]);
        $this->Html->script('Backend.datatables/jquery.dataTables', ['block' => true]);
        $this->Html->script('Backend.datatables/dataTables.bootstrap', ['block' => true]);
    }

    public function options($options = null)
    {
        if ($options === null) {
            return $this->_jsOptions;
        }

        $this->_jsOptions = array_merge($this->_jsOptions, $options);

        return $this;
    }

    protected function _initialize()
    {
        $jsOpts = (array) $this->param('extra');

        // filtering
        if ($this->_params['filter']) {
            $jsOpts['searching'] = true;
        }

        // paging
        if ($this->_params['paginate']) {
            $jsOpts['paging'] = true;
        }

        // sorting
        if ($this->_params['sortable']) {
            $jsOpts['ordering'] = true;
            $jsOpts['order'] = [0, 'desc'];
        }

        // ajax
        //if ($this->_params['ajax']) {
        //}

        // language
        $jsOpts['language'] = [
            'processing' => __d('backend','Loading ...')
        ];

        $this->options($jsOpts);
    }

    protected function _renderFilterRow()
    {
        // Search is injected by DataTable JS
        return '';
    }

    protected function _renderPagination()
    {
        // Pagination is injected by DataTable JS
        return '';
    }


    protected function _buildPaginationFieldLabel($fieldName, $field)
    {
        // Pagination is injected by DataTable JS
        return h($field['label']);
    }

    protected function _renderScript($block = null)
    {

        $jsTable = $this->_jsOptions;
        if (empty($jsTable['columns'])) {
            $columns = $this->_buildDataTableColumns();
            $jsTable['columns'] = array_values($columns);
        }
        $script = sprintf(
            '$(document).ready(function(){ $("#%s").dataTable(%s); });',
            $this->param('id'), json_encode($jsTable)
        );

        return $this->Html->scriptBlock($script, ['safe' => false, 'block' => $block]);
    }

    protected function _buildDataTableColumns()
    {
        $columns = [];
        foreach ($this->_fields as $fieldName => $field) {
            $columns[$fieldName] = [
                'data' => $fieldName,
                'title' => Inflector::humanize($fieldName),
                'filterable' => false,
                'visible' => true,
                'sortable' => true
            ];
        }
        return $columns;
    }

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
            } while(next($columns));
        }
        return $_order;
    }


}
