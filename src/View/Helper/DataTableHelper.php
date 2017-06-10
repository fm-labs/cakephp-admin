<?php

namespace Backend\View\Helper;

use Cake\Collection\CollectionInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Utility\Text;
use Cake\View\Helper;
use Cake\View\Helper\FormHelper;
use Cake\View\Helper\HtmlHelper;
use Cake\View\Helper\PaginatorHelper;
use Cake\View\StringTemplateTrait;

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

    public $helpers = ['Html', 'Form', 'Paginator', 'Backend.Formatter'];

    protected $_params = [];

    protected $_fields = [];

    protected $_id;

    protected $_defaultParams = [
        'model' => null,
        'data' => null,
        'id' => null,
        'class' => '',
        'fields' => [],
        'fieldsWhitelist' => [],
        'fieldsBlacklist' => [],
        'exclude' => [], //@deprecated Use Blacklist instead
        'actions' => [],
        'rowActions' => [],
        'paginate' => false,
        'select' => false,
        'sortable' => false,
        'reduce' => [],
        'filter' => true
    ];

    protected $_defaultFieldParams = ['label' => null, 'class' => '', 'formatter' => null, 'formatterArgs' => []];

    protected $_tableArgs = [];

    protected $_reduceStack = [];

    protected $_setup = null;

    protected function _setup()
    {
        if ($this->_setup === null) {
            $event = new Event('Backend.DataTable.setup');
            $event = $this->_View->eventManager()->dispatch($event);
            $this->_setup = (array)$event->data();
        }

        return $this->_setup;
    }

    public function param($key)
    {
        if (isset($this->_params[$key])) {
            return $this->_params[$key];
        }

        return null;
    }

    /**
     * @param array $params
     * - `model` string Model name
     * - `data` array|Collection Table data
     * - `id` string Html element id attribute
     * - `class` string Html element class attribute
     * - `fields` string|array List of entity fields used as columns. Accepts '*' as wildcard field. MUST BE used as
     *      string parameter or as first item in list
     * - `exclude` array List of entity fields to ignore.
     * - `actions` array List of actions link. Accepts entity fields as string templates in url arrays (:[field])
     *      e.g. [__('Edit'), ['action' => 'edit', 'id' => ':id']]
     * - `paginate` boolean
     * - `sortable` boolean
     * - `filter` boolean
     * - `reduce` array
     *
     */
    public function create($params = [], $data = [])
    {
        $this->_setup();

        if (isset($params['data']) && $params['data']) {
            $data = $params['data'];
            unset($params['data']);
        }

        // parse params
        $this->_params = $params + $this->_defaultParams;
        if (!$this->_params['id']) {
            $this->_id = $this->_params['id'];
        }
        if ($this->_params['sortable']) {
            //$this->_params = $this->Html->addClass($this->_params, 'sortable');
            //$this->Html->script('$("#' . $this->id() . ' .dtable-row").sortable()', ['block' => true]);
            $this->_tableArgs['data-sortable'] = 1;
        }
        if ($this->_params['select']) {
            $this->_tableArgs['data-selectable'] = 1;
        }
        $modelName = pluginSplit($this->_params['model']);
        if (isset($this->_setup[$modelName[1]])) {
            $setup = $this->_setup[$modelName[1]];
            if (isset($setup['rowActions'])) {
                array_walk($setup['rowActions'], function ($action) {
                    $this->_params['rowActions'][] = $action;
                });
            }
        }

        // @TODO Deprecated. Use white- and blacklist instead
        if (isset($this->_params['exclude']) && !empty($this->_params['exclude'])) {
            $this->_params['fieldsBlacklist'] = $this->_params['exclude'];
            $this->_params['exclude'] = [];
        }
        // @TODO Deprecated. Use white- and blacklist instead
        if (isset($this->_params['fields']['*'])) {
            $this->_params['fieldsWhitelist'] = [];
            unset($this->_params['fields']['*']);
        }

        if ($this->_params['fieldsWhitelist'] === true) {
            $this->_params['fieldsWhitelist'] = array_keys($this->_params['fields']);
        }

        // check model
        //if (!$this->_params['model']) {
        //    throw new \InvalidArgumentException('Missing parameter \'model\'');
        //}

        // apply data
        $this->data($data);

        /*
        $this->templater()->add([
            'table' => '<table{{attrs}}>{{head}}{{body}}</table>',
            'head' => '<thead><tr>{{cellheads}}{{actionshead}}</tr></thead>',
            'headCell' => '<th{{attrs}}>{{content}}</th>',
            'headCellActions' => '<th{{attrs}} style="text-align: right;">{{content}}</th>',
            'body' => '<tbody>{{rows}}</tbody>',
            'row' => '<tr{{attrs}}>{{cells}}{{actionscell}}</tr>',
            'rowCell' => '<td{{attrs}}>{{content}}</td>',
            'rowActionsCell' => '<td class="actions">
                <div class="dropdown pull-right">
                    <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
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
        */

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
    }

    public function id()
    {
        if (!$this->_id) {
            $this->_id = uniqid('dt');
        }

        return $this->_id;
    }

    public function data($data = null)
    {
        if ($data === null) {
            return $this->_params['data'];
        }

        $this->_params['data'] = $data;

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
        $this->_parseFields();

        return $this;
    }

    protected function _table()
    {
        if ($this->_table === null && $this->_params['model']) {
            $this->_table = TableRegistry::get($this->_params['model']);
        }

        return $this->_table;
    }

    protected function _tableClass($class = '')
    {
        $class = 'data-table ' . $class;
        if ($this->_params['sortable']) {
            $class .= ' sortable';
        }

        if ($this->_params['select']) {
            $class .= ' select';
        }

        return trim($class);
    }

    protected function _parseFields()
    {
        $fields = (array)$this->_params['fields'];

        // configure each white-listed field
        foreach ($this->_params['fieldsWhitelist'] as $field) {
            // check black list
            if (in_array($field, $this->_params['fieldsBlacklist'])) {
                continue;
            }

            $fieldConfig = (isset($fields[$field])) ? $fields[$field] : [];
            $this->_configureField($field, $fieldConfig);
        }
    }

    protected function _configureField($field, array $conf = [])
    {
        if ($field == '*' || !is_string($field)) {
            throw new \InvalidArgumentException('Field parameter MUST be a string value');
        };

        $conf += $this->_defaultFieldParams;

        if (isset($conf['title']) && !isset($conf['label'])) {
            $conf['label'] = $conf['title'];
        }
        if ($conf['label'] === null) {
            $conf['label'] = Inflector::humanize($field);
        }

        $this->_fields[$field] = $conf;
    }

    protected function _getField($fieldName)
    {
        if (isset($this->_fields[$fieldName])) {
            return $this->_fields[$fieldName];
        }

        return $this->_defaultFieldParams;
    }

    public function render()
    {
        $tableAttributes = $this->_tableArgs + ['id' => $this->id()];

        //$entity = null;
        //if ($this->_params['model']) {
        //    $entity = TableRegistry::get($this->_params['model'])->newEntity();
        //}

        $formStart = $this->Form->create(null, ['method' => 'GET', 'novalidate' => true, 'context' => false]);
        $table = $this->templater()->format('table', [
            'attrs' => $this->templater()->formatAttributes($tableAttributes),
            'head' => $this->renderHead(),
            'body' => $this->renderBody(),
        ]);
        $formEnd = $this->Form->end();

        return $formStart . $table . $formEnd;
    }

    public function renderHead()
    {
        $headCellActions = '';
        if ($this->_params['rowActions'] !== false) {
            $headCellActions = $this->templater()->format('headCellActions', [
               'content' => __('Actions')
            ]);
        }

        $html = $this->templater()->format('head', [
            'cellheads' => $this->renderHeadCells(),
            'actionshead' => $headCellActions
        ]);

        return $html;
    }

    public function renderHeadCells()
    {
        $html = "";
        foreach ($this->_fields as $fieldName => $field) {
            if ($this->_params['paginate']) {
                $header = $this->Paginator->sort($fieldName);
            } else {
                $header = h($field['label']);
            }

            $html .= $this->templater()->format('headCell', [
                'content' => $header,
                'attrs' => $this->templater()->formatAttributes([
                    'class' => $field['class'],
                    'title' => $field['label']
                ])
            ]);
        }

        return $html;
    }

    public function renderBody()
    {
        $rows = "";

        // filter cells
        $rows .= $this->renderFilterRow();

        // data cells
        foreach ($this->_params['data'] as $row) {
            $rows .= $this->renderRow($row);
        }

        // reduce row
        $rows .= $this->renderReduceRow();

        return $this->templater()->format('body', [
            'rows' => $rows,
        ]);
    }

    public function renderFilterRow()
    {
        if (!$this->_params['filter'] || empty($this->_params['filter'])) {
            return '';
        }

        $cells = "";
        foreach ($this->_fields as $fieldName => $field) {
            $filterInput = '';

            if ($this->_params['filter'] == true || in_array($fieldName, $this->_params['filter'])) {
                $filterInputOptions = ['label' => false];

                // get current filter value from request query
                $filterInputOptions['value'] = $this->_View->request->query($fieldName);
                $column = ['type' => 'string', 'null' => true, 'default' => null];

                $Model = $this->_table();
                if ($Model) {
                    $column = $Model->schema()->column($fieldName);
                }
                //$column['null'] = true;
                //$column['default'] = null;

                if ($column['type'] == 'boolean') {
                    $filterInputOptions['type'] = 'select';
                    $filterInputOptions['options'] = [ 0 => __('No'), 1 => __('Yes')];
                    $filterInputOptions['empty'] = __('All');

                //} elseif ($column['type'] == 'select' || substr($fieldName, -3) == '_id') {
                //    $filterInputOptions['empty'] = __('All');
                } elseif ($column['type'] == 'date' || $column['type'] == 'datetime') {
                    $filterInputOptions['type'] = 'hidden';
                } elseif ($column['type'] == 'text') {
                    $filterInputOptions['type'] = 'text';
                }

                if ($Model && method_exists($Model, 'sources')) {
                    $sources = call_user_func([$Model, 'sources'], $fieldName);
                    if ($sources) {
                        $filterInputOptions['empty'] = __('All');
                        $filterInputOptions['options'] = $sources;
                    }
                }
                $filterInput = $this->Form->input($fieldName, $filterInputOptions);
            }
            $cells .= $this->templater()->format('rowCell', [
                'content' => (string)$filterInput,
                'attrs' => $this->templater()->formatAttributes([
                    //'class' => $field['class'],
                    //'title' => $field['title']
                ])
            ]);
        }

        $actionCell = $this->templater()->format('rowCell', [
            'content' => $this->Form->button(__('Filter'), ['class' => 'btn btn-sm']),
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

    public function renderReduceRow()
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
                'attrs' => $this->templater()->formatAttributes([
                    //'class' => $field['class'],
                    //'title' => $field['title']
                ])
            ]);
        }
        $html .= $this->templater()->format('rowCell', ['content' => '']); // actions cell stub
        $html .= "</tr>";

        return $html;
    }

    public function renderRow($row)
    {
        // data cells
        $cells = $this->renderRowCells($row);

        // action cell
        $rowActions = $rowActionsCell = '';
        if ($this->_params['rowActions'] !== false) {
            $rowActions = $this->renderRowActions($this->_params['rowActions'], $row);
            $rowActionsCell = $this->templater()->format('rowActionsCell', [
                'actions' => $rowActions,
            ]);
        }

        // row
        $rowAttributes = [
            'data-id' => (isset($row['id'])) ? $row['id'] : null,
            //'class' => ''
        ];
        $html = $this->templater()->format('row', [
            'attrs' => $this->templater()->formatAttributes($rowAttributes),
            'cells' => $cells,
            'actionscell' => $rowActionsCell
        ]);

        return $html;
    }

    public function renderRowCells($row)
    {
        $html = "";

        // multiselect checkbox
        $html .= $this->_renderRowSelectCell($row);

        foreach ($this->_fields as $fieldName => $field) {
            $cellData = Hash::get($row, $fieldName);

            $formatter = $field['formatter'];
            unset($field['formatter']);
            $formatterArgs = $field['formatterArgs'];
            unset($field['formatterArgs']);

            $formattedCellData = $this->_formatRowCellData($fieldName, $cellData, $formatter, $formatterArgs, $row);
            $cellAttributes = $field;

            $html .= $this->templater()->format('rowCell', [
                'content' => $formattedCellData,
                'attrs' => $this->templater()->formatAttributes($cellAttributes)
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
     * Format cell data
     *
     * If $formatter is FALSE, no formatting will be done
     * If $formatter is NULL, the default formatter will be used (escape text)
     *
     * @param $cellData
     * @param bool $cellData
     * @param array $formatter
     * @param array $formatterArgs
     * @return string
     */
    protected function _formatRowCellData($fieldName, $cellData, $formatter = null, $formatterArgs = [], $row = [])
    {
        return $this->Formatter->format($cellData, $formatter, $formatterArgs, $row);
    }

    public function renderRowActions(array $rowActions, $row = [])
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

    public function pagination()
    {
        if (!$this->_params['paginate']) {
            return;
        }

        return $this->_View->element('Backend.Pagination/default');
    }

    /**
     * @param $script
     * @param array $options
     * @deprecated
     * @return string
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
     * @param $script
     * @param array $options
     * @deprecated
     */
    public function scriptBlock($script, $options = [])
    {
        $options['block'] = 'script_datatable';
        $this->Html->scriptBlock($script, $options);
    }

    public function debug()
    {
        if (isset($this->_params['debug']) && $this->_params['debug'] === true) {
            //debug($this->_params);
        }
    }

    protected function _replaceTokens($tokenStr, $data = [])
    {
        if (is_array($tokenStr)) {
            foreach ($tokenStr as &$_tokenStr) {
                $_tokenStr = $this->_replaceTokens($_tokenStr, $data);
            }

            return $tokenStr;
        }

        // extract tokenized vars from data and cast them to their string representation
        preg_match_all('/\:(\w+)/', $tokenStr, $matches);
        $inserts = array_intersect_key($data, array_flip(array_values($matches[1])));
        array_walk($inserts, function (&$val, $key) {
            $val = (string)$val;
        });

        return Text::insert($tokenStr, $inserts);
    }
}
