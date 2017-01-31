<?php

namespace Backend\View\Helper;


use Cake\ORM\ResultSet;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;
use Cake\View\StringTemplateTrait;
use Cake\View\View;

/**
 * Class DataTablesJsHelper
 * @package Backend\View\Helper
 * @property HtmlHelper $Html
 */
class DataTableJsHelper extends Helper
{
    use StringTemplateTrait;

    public $helpers = ['Html'];

    protected $_defaultParams = [
        'processing' => false,
        'serverside' => false,
        'ajax' => null,
        //'data' => [],
        'paging' => true,
        'ordering' => true,
        'info' => true,
    ];

    protected $_dataTableId;
    protected $_dataTable = [];
    protected $_columns = [];
    protected $_data = [];

    protected $_renderTable = true;
    protected $_renderScript = true;

    /**
     * @param View $View
     * @param array $config
     */
    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);

        $this->templates([
            'datatablesTable' => '<table{{attrs}}>{{content}}</table>'
        ]);

        $this->Html->css('/backend/adminlte/plugins/datatables/jquery.dataTables.min.css', ['block' => true]);
        $this->Html->css('/backend/adminlte/plugins/datatables/dataTables.bootstrap.css', ['block' => true]);
        $this->Html->script('/backend/adminlte/plugins/datatables/jquery.dataTables.js', ['block' => true]);
        $this->Html->script('/backend/adminlte/plugins/datatables/dataTables.bootstrap.js', ['block' => true]);
    }

    public function fromHtmlTable($domId, $options = [])
    {
        $this->_dataTableId = $domId;
        $this->_dataTable = $this->_defaultParams;
        $this->options($options);

        $this->_renderTable = false;
        $this->_renderScript = true;
        return $this->_renderScript();
    }

    public function create($table, $columns = [], $options = [])
    {
        $this->_renderTable = true;
        $this->_renderScript = true;
        $this->_dataTable = $this->_defaultParams;
        $this->id(uniqid('dtjs'));
        $this->options($options);
        $this->columns($columns);
        return $this;
    }

    public function id($id = null)
    {
        if ($id === null) {
            return $this->_dataTableId;
        }

        $this->_dataTableId = $id;
        return $this;
    }

    public function options($options = null)
    {
        if ($options === null) {
            return $this->_dataTable;
        }

        $this->_dataTable = array_merge($this->_dataTable, $options);
        return $this;
    }

    public function columns($columns = null)
    {

        if ($columns === null) {
            return $this->_columns;
        }

        $_default = [
            'class' => null,
            'orderable' => true,
            'data' => null,
            'defaultContent' => '',
        ];

        $this->_columns = [];
        foreach ($columns as $column => $config) {
            if (is_numeric($column)) {
                $column = $config;
                $config = [];
            }

            $this->_columns[$column] = array_merge($_default, $config);
        }
        return $this;
    }

    public function data($data = null)
    {
        if ($data === null) {
            return $this->_data;
        }

        if ($data instanceof ResultSet) {
            $data = $data->toArray();
        }

        $this->_data = $data;
        $this->_dataTable['data'] = $this->_data;
    }

    protected function _processColumnsConfig()
    {
        $columns = [];

        foreach ($this->_columns as $columnName => $config) {
            $columns[] = ['data' => $columnName];
        }

        $this->_dataTable['columns'] = $columns;
    }

    protected function _processDataSourceConfig()
    {
    }

    protected function _renderScript($block = null)
    {
        $domId = $this->_dataTableId;

        $this->_processColumnsConfig();
        $script = sprintf('$(document).ready(function(){ var %s = $("#%s").dataTable(%s); });',
            $domId, $domId, json_encode($this->_dataTable));

        return $this->Html->scriptBlock($script, ['safe' => false, 'block' => $block]);
    }

    protected function _renderTable()
    {
        $attrs = [
          'id' => $this->_dataTableId
        ];

        return $this->templater()->format('datatablesTable', [
            'attrs' => $this->_templater->formatAttributes($attrs)
        ]);
    }

    public function render()
    {
        $script = ($this->_renderScript) ? $this->_renderScript('script') : '';
        $html = ($this->_renderTable) ? $this->_renderTable() : '';
        return $html . $script;
    }
}