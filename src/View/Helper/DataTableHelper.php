<?php

namespace Backend\View\Helper;

use Cake\Event\Event;
use Cake\ORM\Association;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Utility\Text;
use Cake\View\Helper;
use Cake\View\Helper\FormHelper;
use Cake\View\Helper\HtmlHelper;
use Cake\View\Helper\PaginatorHelper;
use Cake\View\StringTemplateTrait;
use Cake\View\View;

/**
 * Class DataTableHelper
 * @package Backend\View\Helper
 *
 * @property HtmlHelper $Html
 * @property FormHelper $Form
 * @property PaginatorHelper $Paginator
 * @property FormatterHelper $Formatter
 */
class DataTableHelper extends Helper
{
    use StringTemplateTrait;

    public $helpers = ['Html', 'Form', 'Paginator', 'Backend.Formatter', 'Bootstrap.Button', 'Bootstrap.Icon'];

    protected $_params = [];

    protected $_fields = [];

    protected $_defaultParams = [
        'model' => null,
        'data' => null,
        'id' => null,
        'class' => '',
        'fields' => [],
        'fieldsWhitelist' => null,
        'fieldsBlacklist' => null,
        'actions' => [],
        'rowActions' => [],
        'paging' => false,
        'pagingLimit' => 20,
        'paginate' => [],
        'select' => false,
        'sortable' => false,
        'reduce' => [],
        'filter' => false,
        'ajax' => false,
    ];

    protected $_defaultField = ['type' => null, 'label' => null, 'class' => null, 'formatter' => null, 'formatterArgs' => [], 'schema' => null];

    protected $_tableArgs = [];

    protected $_reduceStack = [];

    protected $_rowCallbacks = [];

    protected $_setup = null;

    /**
     * Table instance
     * @var Table
     */
    protected $_table;

    /**
     * {@inheritDoc}
     */
    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);

        $this->templater()->add([
            'table_container' => '<div class="datatable-container">{{table}}{{pagination}}{{script}}</div>',
            'table' => '<table class="table table-condensed table-hover"{{attrs}}>{{head}}{{body}}{{footer}}</table>',
            'head' => '<thead><tr>{{cellheads}}{{actionshead}}</tr></thead>',
            'footer' => '<tfoot><tr>{{cellheads}}{{actionshead}}</tr></tfoot>',
            'headCell' => '<th{{attrs}}>{{content}}</th>',
            'headCellActions' => '<th{{attrs}} style="text-align: right;">{{content}}</th>',
            'body' => '<tbody>{{rows}}</tbody>',
            'row' => '<tr{{attrs}}>{{cells}}{{actionscell}}</tr>',
            'rowCell' => '<td{{attrs}}>{{content}}</td>',
            'rowActionsCell' => '<td class="actions" style="text-align: right;"{{attrs}}>{{actions}}</td>',
            '_rowActionsCell' => '<td class="actions">
                <div class="dropdown pull-right">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-gear"></i>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                         {{actions}}
                    </ul>
                </div>
            </td>',
            'rowAction' => '<li>{{content}}</li>',
            'rowSelectCell' => '<td>{{content}}</td>'
        ]);

        /*
        $this->templater()->add([
            'table' => '<div class="dtable-container"><div class="dtable"{{attrs}}>{{head}}{{body}}</div></div>',
            'head' => '<div class="dtable-head">{{cellheads}}{{actionshead}}</div>',
            'headCell' => '<div class="dtable-head-cell"{{attrs}}>{{content}}</div>',
            'headCellActions' => '<div class="dtable-head-cell"{{attrs}} style="text-align: right;">{{content}}</div>',
            'body' => '<div class="dtable-body">{{rows}}</div>',
            'row' => '<div class="dtable-row"{{attrs}}>{{cells}}{{actionscell}}</div>',
            'rowCell' => '<div class="dtable-cell"{{attrs}}>{{content}}</div>',
            'rowActionsCell' => '<div class="dtable-cell dtable-row-actions">
                <div class="dropdown pull-right">
                    <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-gear"></i>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                         {{actions}}
                    </ul>
                </div>
            </div>',
            'rowAction' => '<li>{{content}}</li>',
            'rowSelectCell' => '<div class="dtable-row-select">{{content}}</div>'
        ]);
        */
    }

    /**
     * @param array $params Table parameters
     * - `model` string Model name
     * - `data` array|Collection Table data
     * - `id` string Html element id attribute
     * - `class` string Html element class attribute
     * - `fields` string|array List of entity fields used as columns. Accepts '*' as wildcard field. MUST BE used as
     *      string parameter or as first item in list
     * - `exclude` array List of entity fields to ignore.
     * - `actions` array List of actions link. Accepts entity fields as string templates in url arrays (:[field])
     *      e.g. [__d('backend', 'Edit'), ['action' => 'edit', 'id' => ':id']]
     * - `paginate` boolean
     * - `sortable` boolean
     * - `filter` boolean
     * - `reduce` array
     * @param array $data Table data
     * @return $this
     */
    public function create($params = [], $data = [])
    {
        if (isset($params['data']) && $params['data']) {
            $data = $params['data'];
            unset($params['data']);
        }

        $this->init($params);
        $this->data($data);

        return $this;
    }

    /**
     * @param array $params Table parameters
     * @return $this
     */
    public function init($params)
    {
        // parse params
        $this->setParam($this->_defaultParams, null, false);
        $this->setParam($params);
        //$this->_params = $params + $this->_defaultParams;
        if (!$this->_params['id']) {
            $this->_params['id'] = uniqid('dt');
        }

        // model
        if ($this->_params['model'] instanceof Table) {
            $this->_table = $this->_params['model'];
            $this->_params['model'] = $this->_table->alias();
        } elseif ($this->_params['model'] instanceof Association) {
            $this->_table = $this->_params['model']->target();
            $this->_params['model'] = $this->_table->alias();
        }

        // fields whitelist
        if (is_array($this->_params['fieldsWhitelist']) && empty($this->_params['fieldsWhitelist'])) {
            $this->_params['fieldsWhitelist'] = null;
        } elseif ($this->_params['fieldsWhitelist'] === true) {
            $this->_params['fieldsWhitelist'] = null;
        }

        // fields blacklist
        if (is_array($this->_params['fieldsBlacklist']) && empty($this->_params['fieldsBlacklist'])) {
            $this->_params['fieldsBlacklist'] = null;
        }

        // fields
        $_fields = $this->_params['fields'];
        if (empty($_fields) && $this->_params['model']) {
            $_fields = $this->_table()->schema()->columns();
        }
        $this->_params['fields'] = $_fields;

        // callback listeners
        if (isset($this->_params['rowActionCallbacks'])) {
            foreach ($this->_params['rowActionCallbacks'] as $callback) {
                $this->addRowCallback($callback);
            }
        }

        if (isset($this->_params['rowActions']) && $this->_params['rowActions'] !== false) {
            $rowActions = $this->_params['rowActions'];
            $this->addRowCallback(function ($row) use ($rowActions) {
                return $this->_applyRowActions($rowActions, $row);
            });
        }

        $this->_initializeFields();
        $this->_initialize();

        //if ($this->_setup === null) {
            $event = $this->_View->eventManager()->dispatch(new Event('Backend.DataTable.setup', $this));
        //}

        return $this;
    }

    /**
     * Convenience wrapper to get ID param
     *
     * @return int|string
     */
    public function id()
    {
        return $this->getParam('id');
    }

    /**
     * Set datatable parameter
     *
     * @param string|array $key Parameter key
     * @param null|mixed $val Parameter value
     * @param bool $merge If True and $key is an array the values will be merged with existing params
     * @return $this
     */
    public function setParam($key, $val = null, $merge = true)
    {
        if (is_array($key)) {
            if ($merge === false) {
                $this->_params = $key;
            } else {
                foreach ($key as $_key => $_val) {
                    $this->setParam($_key, $_val, $merge);
                }
            }

            return $this;
        }
        $this->_params[$key] = $val;

        return $this;
    }

    /**
     * Get datatable parameter
     *
     * @param string|null $key Pass NULL to return all params
     * @return mixed
     */
    public function getParam($key)
    {
        if ($key === null) {
            return $this->_params;
        }

        if (isset($this->_params[$key])) {
            return $this->_params[$key];
        }

        return null;
    }

    /**
     * Alias for getParam()
     *
     * @param string $key Key name
     * @return mixed
     */
    public function param($key)
    {
        return $this->getParam($key);
    }

    /**
     * Get data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->_params['data'];
    }

    /**
     * Set data
     *
     * @param mixed $data Table data
     * @return $this
     */
    public function setData($data)
    {
        $this->_params['data'] = $data;

        /*
        if (empty($this->_params['fieldsWhitelist'])) {
            if ($data instanceof CollectionInterface) {
                $firstRow = $data->first();
            } else {
                $firstRow = (is_array($data) && !empty($data) && $data[0]) ? $data[0] : [];
            }
            $firstRow = is_object($firstRow) ? $firstRow->toArray() : $firstRow;
            if ($firstRow) {
                $this->_params['fieldsWhitelist'] = array_keys($firstRow);
            }
        }
        */
        return $this;
    }

    /**
     * Alias for get/setData()
     *
     * @param null|array $data Table data. If NULL, existing table data will be returned
     * @return array|$this
     */
    public function data($data = null)
    {
        if ($data === null) {
            return $this->getData();
        }

        return $this->setData($data);
    }

    /**
     * @param callable $callable A callback receiving a table row as single argument
     * @return $this
     */
    public function addRowCallback(callable $callable)
    {
        $this->_rowCallbacks[] = $callable;

        return $this;
    }

    /**
     * Render the table HTML
     *
     * @param array $options Render options
     * @return string
     */
    public function render($options = [])
    {
        $options += ['pagination' => null, 'script' => null];

        $table = $this->_renderTable();

        $pagination = "";
        if ($this->_params['paginate'] && $options['pagination'] !== false) {
            $pagination = $this->_renderPagination();
        }

        $script = "";
        if ($options['script'] !== false) {
            $script = $this->_renderScript();
        }

        $container = $this->templater()->format('table_container', [
            'attrs' => $this->templater()->formatAttributes($options, ['pagination', 'table', 'script']),
            'pagination' => $pagination,
            'table' => $table,
            'script' => $script
        ]);

        return $container;
    }

    /**
     * @deprecated Use render() instread
     * @return string
     */
    public function renderAll()
    {
        return $this->render();
    }

    /**
     * Implementation specific initializer method
     * May be overridden in subclasses for different initialization
     *
     * @return void
     */
    protected function _initialize()
    {
        // feature sorting
        if ($this->_params['sortable']) {
            //$this->_params = $this->Html->addClass($this->_params, 'sortable');
            //$this->Html->script('$("#' . $this->param('id') . ' .dtable-row").sortable()', ['block' => true]);
            $this->_tableArgs['data-ui-sortable'] = true;
        }

        // feature row selection
        if ($this->_params['select']) {
            $this->_tableArgs['data-selectable'] = "true";
        }
    }

    /**
     * @return void
     */
    protected function _initializeFields()
    {
        $fields = (array)$this->_params['fields'];
        $this->_fields = [];

        foreach ($fields as $field => $fieldConfig) {
            if (is_numeric($field)) {
                $field = $fieldConfig;
                $fieldConfig = [];
            }

            if (is_array($this->_params['fieldsWhitelist']) && !in_array($field, $this->_params['fieldsWhitelist'])) {
                //debug("field $field is NOT whitelisted");
                continue;
            }

            if (is_array($this->_params['fieldsBlacklist']) && in_array($field, $this->_params['fieldsBlacklist'])) {
                //debug("field $field is blacklisted");
                continue;
            }

            $this->_configureField($field, $fieldConfig);
        }
    }

    /**
     * @param string $field Field name
     * @param array $conf Field config
     * @return void
     */
    protected function _configureField($field, array $conf = [])
    {
        if ($field == '*' || !is_string($field)) {
            throw new \InvalidArgumentException('Field parameter MUST be a string value');
        };

        $conf += $this->_defaultField;

        // defaults from table column schema
        if (!isset($conf['schema']) && $this->_table()) {
            $conf['schema'] = $column = $this->_table()->schema()->column($field);
            if ($column && !$conf['type']) {
                $conf['type'] = $column['type'];
            }
        }

        // defaults
        if (isset($conf['title']) && !isset($conf['label'])) {
            $conf['label'] = $conf['title'];
        }
        if ($conf['label'] === null) {
            $conf['label'] = Inflector::humanize($field);
        }

        $this->_fields[$field] = $conf;
    }

    /**
     * @param string $fieldName Field name
     * @return array|null
     */
    public function getField($fieldName)
    {
        if (isset($this->_fields[$fieldName])) {
            return $this->_fields[$fieldName];
        }

        return null;
    }

    /**
     * @param string $fieldName Field name
     * @param array $config Field config
     * @param bool $merge If True, merge with existing field config (Default: True)
     * @return $this
     */
    public function setField($fieldName, array $config, $merge = true)
    {
        $field = $this->getField($fieldName);
        if ($merge) {
            $config = array_merge($this->_defaultConfig, $field, $config);
        } else {
            $config = array_merge($this->_defaultConfig, $config);
        }
        $this->_fields[$fieldName] = $config;

        return $this;
    }

    /**
     * @return Table
     */
    protected function _table()
    {
        if ($this->_table === null && $this->_params['model']) {
            $this->_table = TableRegistry::get($this->_params['model']);
        }

        return $this->_table;
    }

    /**
     * Get table css class
     *
     * @param string $class Additional class(es)
     * @return string
     */
    protected function _tableClass($class = '')
    {
        $class = 'table data-table' . $class;
        if ($this->_params['sortable']) {
            $class .= ' sortable';
        }

        if ($this->_params['select']) {
            $class .= ' select';
        }

        return trim($class);
    }

    /**
     * @return string
     */
    protected function _renderTable()
    {
        $tableAttributes = $this->_tableArgs + ['id' => $this->param('id')];

        //$entity = null;
        //if ($this->_params['model']) {
        //    $entity = TableRegistry::get($this->_params['model'])->newEntity();
        //}

        $formStart = $this->Form->create(null, ['method' => 'GET', 'novalidate' => true, 'context' => false]);
        $table = $this->templater()->format('table', [
            'attrs' => $this->templater()->formatAttributes($tableAttributes),
            'head' => $this->_renderHead(),
            'body' => $this->_renderBody(),
            'footer' => (count($this->data()) > 25) ? $this->_renderHead('footer') : ''
        ]);
        $formEnd = $this->Form->end();

        return $formStart . $table . $formEnd;
    }

    /**
     * @param string $template String template name
     * @return string
     */
    protected function _renderHead($template = 'head')
    {
        $headCellActions = '';
        if ($this->_params['rowActions'] !== false) {
            $headCellActions = $this->templater()->format('headCellActions', [
                'content' => __d('backend', 'Actions'),
                'attrs' => $this->_buildFieldAttributes('_actions_', [
                    'type' => 'object',
                    'label' => 'Actions',
                    'formatter' => 'actions',
                    'formatterArgs' => []
                ])
            ]);
        }

        $html = $this->templater()->format($template, [
            'cellheads' => $this->_renderHeadCells(),
            'actionshead' => $headCellActions
        ]);

        return $html;
    }

    /**
     * @return string
     */
    protected function _renderHeadCells()
    {
        $html = "";
        foreach ($this->_fields as $fieldName => $field) {
            $html .= $this->templater()->format('headCell', [
                'content' => $this->_buildPaginationFieldLabel($fieldName, $field),
                'attrs' => $this->_buildFieldAttributes($fieldName, $field)
            ]);
        }

        return $html;
    }

    /**
     * @return string
     */
    protected function _renderBody()
    {
        $rows = "";

        // filter cells
        $rows .= $this->_renderFilterRow();

        // data cells
        if ($this->_params['data']) {
            foreach ($this->_params['data'] as $row) {
                $rows .= $this->_renderRow($row);
            }
        }

        // reduce row
        $rows .= $this->_renderReduceRow();

        return $this->templater()->format('body', [
            'rows' => $rows,
        ]);
    }

    /**
     * @return string
     */
    protected function _renderFilterRow()
    {
        if ($this->_params['filter'] === false) {
            return '';
        }

        $Model = $this->_table();
        $filters = ($this->_params['filter'] === true) ? [] : (array)$this->_params['filter'];
        if (empty($filters) && $Model && $Model->behaviors()->has('Search')) {
            $searchFilters = $Model->searchManager()->all();
            $filters = array_keys($searchFilters);
        }
        $cells = "";
        foreach ($this->_fields as $fieldName => $field) {
            $filterInput = '';

            //if ($this->_params['filter'] == true || in_array($fieldName, $this->_params['filter'])) {
            //if (in_array($fieldName, $filters)) {
            $filterInputOptions = ['label' => false];

            // get current filter value from request query
            $filterInputOptions['value'] = $this->_View->request->query($fieldName);
            $column = ['type' => 'string', 'null' => true, 'default' => null];

            if ($Model) {
                $column = $Model->schema()->column($fieldName);
            }
            //$column['null'] = true;
            //$column['default'] = null;

            $filterInputOptions['title'] = json_encode($column);
            $filterInputOptions['data-filter'] = $fieldName;
            $filterInputOptions['class'] = 'filter';

            if ($column['type'] == 'boolean') {
                $filterInputOptions['type'] = 'select';
                $filterInputOptions['options'] = [ 0 => __d('backend', 'No'), 1 => __d('backend', 'Yes')];
                //$filterInputOptions['empty'] = __d('backend', 'All');
                $filterInputOptions['data-placeholder'] = __d('backend', 'All');

            //} elseif ($column['type'] == 'select' || substr($fieldName, -3) == '_id') {
            //    $filterInputOptions['empty'] = __d('backend', 'All');
            } elseif ($column['type'] == 'date' || $column['type'] == 'datetime') {
                $filterInputOptions['type'] = 'hidden';
            } elseif ($column['type'] == 'text') {
                $filterInputOptions['type'] = 'text';
            }

            if ($Model instanceof \Banana\Model\TableInputDataSourceInterface) {
                $sources = $Model->getInputList($fieldName);
                if ($sources) {
                    $filterInputOptions['options'] = $sources;
                    //$filterInputOptions['empty'] = __d('backend', 'All');
                    $filterInputOptions['data-placeholder'] = __d('backend', 'All');
                }
            }
            $filterInput = $this->Form->input($fieldName, $filterInputOptions);
            //}
            $cells .= $this->templater()->format('rowCell', [
                'content' => (string)$filterInput,
                'attrs' => $this->templater()->formatAttributes([
                    //'class' => $field['class'],
                    //'title' => $field['title']
                ])
            ]);
        }

        $actionCell = $this->templater()->format('rowCell', [
            'content' => $this->Form->button(__d('backend', 'Filter'), ['class' => 'btn btn-default btn-xs']),
            'attrs' => $this->templater()->formatAttributes([
                'style' => 'text-align: right;',
                //'title' => $field['title']
            ])
        ]); // actions cell stub

        $row = $this->templater()->format('row', [
            'attrs' => '',
            'cells' => $cells,
            'actionscell' => $actionCell
        ]);

        return $row;
    }

    /**
     * @return string
     */
    protected function _renderReduceRow()
    {
        if (empty($this->_params['reduce'])) {
            return '';
        }

        $html = "<tr>";
        foreach ($this->_fields as $fieldName => $field) {
            $reducedData = null;
            if (isset($this->_params['reduce'][$fieldName])) {
                $reduce = $this->_params['reduce'][$fieldName] + ['formatter' => null, 'callable' => null];

                $rawData = null;
                if (isset($this->_reduceStack[$fieldName])) {
                    $rawData = $this->_reduceStack[$fieldName];
                }

                $reducedData = $this->_formatRowCellData($fieldName, $rawData, $reduce['formatter'], [], null);
            }

            $html .= $this->templater()->format('rowCell', [
                'content' => (string)$reducedData,
                'attrs' => ''
            ]);
        }
        $html .= $this->templater()->format('rowCell', ['content' => '']); // actions cell stub
        $html .= "</tr>";

        return $html;
    }

    /**
     * @param array $row Table row data
     * @return string
     */
    protected function _renderRow($row)
    {
        // data cells
        $cells = $this->_renderRowCells($row);

        // action cell
        $rowActionsCell = '';
        if ($this->_params['rowActions'] !== false) {
            $rowActionsCell = $this->_renderRowActionsCell($row);
        }

        // row
        $html = $this->templater()->format('row', [
            'cells' => $cells,
            'actionscell' => $rowActionsCell,
            'attrs' => $this->_buildRowAttributes($row)
        ]);

        return $html;
    }

    /**
     * @param array $row Table row data
     * @return string
     */
    protected function _renderRowActionsCell($row)
    {
        $rowActionsHtml = $this->_renderRowActions($row);

        return $this->templater()->format('rowActionsCell', [
            'actions' => $rowActionsHtml,
            'attrs' => $this->_buildFieldAttributes('_actions_', ['type' => 'actions', 'label' => 'Actions', 'formatter' => null, 'formatterArgs' => []])
        ]);
    }

    /**
     * @param array $row Table row data
     * @return string
     */
    protected function _renderRowActions($row)
    {
        $row = (is_object($row)) ? $row->toArray() : $row;
        $actions = [];

        foreach ($this->_rowCallbacks as $callback) {
            if ($result = call_user_func($callback, $row)) {
                foreach ($result as $actionId => $action) {
                    $actions[$actionId] = $action;
                }
            }
        }

        $icon = $this->Icon->create('gear');
        $button = $this->Button->create($icon, [
            'size' => 'xs',
            'dropdown' => $actions
        ]);

        return $button;
    }

    /**
     * @param array $row Table row data
     * @return string
     */
    protected function _renderRowCells($row)
    {
        $html = "";

        // multiselect checkbox
        $html .= $this->_renderRowSelectCell($row);

        foreach ($this->_fields as $fieldName => $field) {
            $cellData = Hash::get($row, $fieldName);

            // If formatter is passed as an array (and no callable construct)
            // extract formatter name and args
            // e.g. ['formatterName', 'arg1', 'arg2', ... ]
            //if (is_array($field['formatter']) && !is_object($field['formatter'][0])) {
            //    $field['formatterArgs'] = $field['formatter'];
            //    $field['formatter'] = array_pop($field['formatterArgs']);
            //}
            $cellData = $this->_formatRowCellData($fieldName, $cellData, $field['formatter'], $field['formatterArgs'], $row);

            $html .= $this->templater()->format('rowCell', [
                'content' => $cellData,
                'attrs' => $this->_buildFieldAttributes($fieldName, $field)
            ]);

            // reducer
            if (isset($this->_params['reduce'][$fieldName]) && isset($this->_params['reduce'][$fieldName]['callable'])) {
                $reducer = $this->_params['reduce'][$fieldName]['callable'];
                if (!is_callable($reducer)) {
                    debug("Reducer for $fieldName is not callable");
                } else {
                    call_user_func_array($reducer, [$cellData, $row, &$this->_reduceStack]);
                }
            }
        }

        return $html;
    }

    /**
     * @param array $row Table row data
     * @return string
     */
    protected function _renderRowSelectCell($row)
    {
        if (isset($this->_params['select']) && $this->_params['select'] === true) {
            $input = $this->Form->checkbox('multiselect_' . $row->id);

            return $this->templater()->format('rowCell', [
                'content' => $input
            ]);
        }
    }

    /**
     * @return string
     */
    protected function _renderPagination()
    {
        //if (!$this->_params['paginate']) {
        //    return '';
        //}

        return $this->_View->element('Backend.Pagination/default', [
            'counter' => ['format' => __d('backend', 'Page {{page}} of {{pages}} . Showing {{current}} records from row {{start}} to {{end}} of {{count}} records')],
            'numbers' => []
        ]);
    }

    /**
     * @param string $script Script source
     * @param array $options Script render options
     * @return string|void
     * @deprecated
     */
    public function script($script = null, $options = [])
    {
        if ($script === null) {
            return $this->_View->fetch('script_datatable');
        }

        $options['block'] = 'script_datatable';
        $this->Html->script($script, $options);
    }

    /**
     * @param string $script Script source
     * @param array $options Script render options
     * @return string|void
     * @deprecated
     */
    public function scriptBlock($script, $options = [])
    {
        $options['block'] = 'script_datatable';
        $this->Html->scriptBlock($script, $options);
    }

    /**
     * @param string $fieldName Field name
     * @param array $field Field config
     * @return string
     */
    protected function _buildPaginationFieldLabel($fieldName, $field)
    {
        if ($this->_params['paginate'] && $this->_params['sortable']) {
            return $this->Paginator->sort($fieldName, $field['label']);
        }

        return h($field['label']);
    }

    /**
     * @param array $row Table data row
     * @return string
     */
    protected function _buildRowAttributes($row)
    {
        $rowAttributes = [
            'data-id' => (isset($row['id'])) ? $row['id'] : null,
        ];

        return $this->templater()->formatAttributes($rowAttributes);
    }

    /**
     * @param string $fieldName Field name
     * @param array $field Field config
     * @return string
     */
    protected function _buildFieldAttributes($fieldName, $field)
    {
        $field['data-name'] = $fieldName;
        $field['data-label'] = $field['label'];
        $field['data-type'] = $field['type'];

        return $this->templater()->formatAttributes($field, array_keys($this->_defaultField));
    }

    /**
     * Format cell data
     *
     * If $formatter is FALSE, no formatting will be done
     * If $formatter is NULL, the default formatter will be used (escape text)
     *
     * @param string $fieldName Field name
     * @param mixed $cellData Cell data
     * @param array $formatter Formatter name
     * @param array $formatterArgs Formatter args
     * @param array $row Table row data
     * @return string
     */
    protected function _formatRowCellData($fieldName, $cellData, $formatter = null, $formatterArgs = [], $row = [])
    {
        return $this->Formatter->format($cellData, $formatter, $formatterArgs, $row);
    }

    /**
     * @return string
     */
    protected function _renderScript()
    {
        $script = <<<SCRIPT
<script type="text/javascript">
    $(document).ready(function() {

        var dtId = '__DATATABLE_ID__';
        var dtTable = '__DATATABLE_MODEL__';
        var dtSortUrl = '__DATATABLE_SORTURL__';
        var el = $('#' + dtId);

        console.log("loading datatable js for " + dtId);


        //originally from http://stackoverflow.com/questions/1307705/jquery-ui-sortable-with-table-and-tr-width/1372954#1372954
        var fixHelperModified = function(e, tr) {
            var originals = tr.children();
            var helper = tr.clone();
            helper.children().each(function(index)
            {
                $(this).width(originals.eq(index).width())
            });
            return helper;
        };

        //
        // Jquery UI Sortable DataTable
        //
        if (el.attr('data-ui-sortable') == 1) {

            console.log("init sortable for dt " + dtId);

            if (!$.fn.sortable) {
                console.warn("JqueryUI sortable not loaded");
            } else {
                console.log("initialize sortable")
                el.find("tbody").sortable({
                    placeholder: "ui-sortable-placeholder", // "ui-state-highlight",
                    helper: fixHelperModified,
                    update: function(event, ui) {
                        console.log(ui);
                        console.log(event);

                        var sibling = ui.item.prev();
                        var siblingId = 0;
                        if (sibling.length > 0) {
                            siblingId = sibling.data().id;
                        }

                        var updateData = { id: ui.item.data().id, after: siblingId, model: dtTable };
                        console.log(updateData);

                        if (dtTable && dtSortUrl) {
                            $.ajax({
                                type: 'POST',
                                url: dtSortUrl,
                                data: updateData,
                                dataType: 'json',
                                success: function(data, textStatus, xhr) {
                                    //console.log(textStatus);
                                    console.log(data);

                                    if (data.error !== undefined) {
                                        alert("Ups. Something went wrong! " + data.error);
                                        return;
                                    }
                                },
                                error: function(err) {
                                    alert("Ups. Something went wrong. Please try again");
                                    console.error(err);
                                }
                            });
                        }


                    }
                });
                //.disableSelection();
            }

        } else {
            console.log("Datatable " + dtId + " is not sortable");
        }

        //el.dataTable();

        /**
         * FILTER FORM
         * Auto-submit when select input changes
         */
        el.on('change', 'select.filter', function(ev) {
            //console.log("a filter select has changed");
            $(this).closest('form').submit();
        });

    });
</script>
SCRIPT;

        $replace = [
            '/__DATATABLE_ID__/' => $this->param('id'),
            '/__DATATABLE_MODEL__/' => $this->param('model'),
            '/__DATATABLE_SORTURL__/' => $this->Html->Url->build($this->param('sortable'))
        ];

        return preg_replace(array_keys($replace), array_values($replace), $script);
    }

    /**
     * @param array $rowActions List of row actions
     * @param array $row Table row data
     * @return array
     */
    protected function _applyRowActions(array $rowActions, $row = [])
    {
        $row = (is_object($row)) ? $row->toArray() : $row;
        // rowActions
        $actions = [];
        foreach ($rowActions as $actionId => $rowAction) {
            $title = $url = null;
            $attrs = [];

            if (count($rowAction) == 1) {
                list($title) = $rowAction;
            } elseif (count($rowAction) == 2) {
                list($title, $url) = $rowAction;
            } elseif (count($rowAction) >= 3) {
                list($title, $url, $attrs) = $rowAction;
            }

            $title = $this->_replaceTokens($title, $row);
            $url = $this->_replaceTokens($url, $row);
            $attrs = $this->_replaceTokens($attrs, $row);

            //$rowActionLink = $this->Html->link($title, $url, $attr);

            //$html .= $this->templater()->format('rowAction', [
            //    'content' => $rowActionLink
            //]);
            $actions[$actionId] = compact('title', 'url', 'attrs');
        }

        return $actions;
    }

    /**
     * @param array $rowActions List of row actions
     * @param array $row Table row data
     * @return string
     * @deprecated Unused
     */
    protected function _renderRowActionsOld(array $rowActions, $row = [])
    {
        $html = "";
        $row = (is_object($row)) ? $row->toArray() : $row;
        // rowActions
        foreach ($rowActions as $rowAction) {
            $title = $url = null;
            $attr = [];

            if (count($rowAction) == 1) {
                list($title) = $rowAction;
            } elseif (count($rowAction) == 2) {
                list($title, $url) = $rowAction;
            } elseif (count($rowAction) >= 3) {
                list($title, $url, $attr) = $rowAction;
            }

            $title = $this->_replaceTokens($title, $row);
            $url = $this->_replaceTokens($url, $row);
            $attr = $this->_replaceTokens($attr, $row);

            $rowActionLink = $this->Html->link($title, $url, $attr);

            $html .= $this->templater()->format('rowAction', [
                'content' => $rowActionLink
            ]);
        }

        return $html;
    }

    /**
     * @param string $tokenStr Template
     * @param array $data Data
     * @return string
     * @deprecated Unused
     */
    protected function _replaceTokens($tokenStr, $data = [])
    {
        if (is_array($tokenStr)) {
            foreach ($tokenStr as &$_tokenStr) {
                $_tokenStr = $this->_replaceTokens($_tokenStr, $data);
            }

            return $tokenStr;
        }

        // extract tokenized vars from data and cast them to their string representation
        preg_match_all('/\:([\w\.]+)/', $tokenStr, $matches);
        $inserts = array_fill_keys($matches[1], null);
        array_walk($inserts, function (&$val, $key) use ($data) {
            $val = Hash::get($data, $key);
        });

        return Text::insert($tokenStr, $inserts);
    }
}
