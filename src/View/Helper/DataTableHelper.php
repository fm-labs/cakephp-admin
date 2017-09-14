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
use Composer\Script\ScriptEvents;

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

    protected $_rowCallbacks = [];

    protected $_setup = null;

    protected function _initialize()
    {
        if ($this->_setup === null) {
            $event = new Event('Backend.DataTable.setup', $this);
            $event = $this->_View->eventManager()->dispatch($event);
        }
    }

    public function param($key)
    {
        if (isset($this->_params[$key])) {
            return $this->_params[$key];
        }

        return null;
    }

    public function addRowCallback(callable $callable)
    {
        $this->_rowCallbacks[] = $callable;
        return $this;
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
        /*
        if (isset($this->_setup[$modelName[1]])) {
            $setup = $this->_setup[$modelName[1]];
            if (isset($setup['rowActions'])) {
                array_walk($setup['rowActions'], function ($action) {
                    $this->_params['rowActions'][] = $action;
                });
            }
        }
        */

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

        // callback listeners
        if (isset($this->_params['rowActionCallbacks'])) {
            foreach ($this->_params['rowActionCallbacks'] as $callback) {
                $this->addRowCallback($callback);
            }
        }

        // check model
        //if (!$this->_params['model']) {
        //    throw new \InvalidArgumentException('Missing parameter \'model\'');
        //}

        // apply data
        $this->data($data);

        $this->templater()->add([
            'table' => '<table class="table table-striped"{{attrs}}>{{head}}{{body}}</table>',
            'head' => '<thead><tr>{{cellheads}}{{actionshead}}</tr></thead>',
            'headCell' => '<th{{attrs}}>{{content}}</th>',
            'headCellActions' => '<th{{attrs}} style="text-align: right;">{{content}}</th>',
            'body' => '<tbody>{{rows}}</tbody>',
            'row' => '<tr{{attrs}}>{{cells}}{{actionscell}}</tr>',
            'rowCell' => '<td{{attrs}}>{{content}}</td>',
            'rowActionsCell' => '<td class="actions" style="text-align: right;">{{actions}}</td>',
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

        $this->_initialize();
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
        $class = 'table data-table' . $class;
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

    public function beforeRenderFile(Event $event)
    {
        //debug("beforeRenderFile");
    }

    public function beforeRender(Event $event)
    {
        //debug("beforeRender");
    }

    public function beforeLayout(Event $event)
    {
        //debug("beforeLayout");
    }

    public function renderAll()
    {
        $out = "";
        $out .= $this->pagination();
        $out .= $this->render();
        $out .= $this->pagination();
        $out .= $this->_renderScript();
        $out .= $this->debug();
        return $out;
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
                $header = $this->Paginator->sort($fieldName, $field['label']);
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
            $rowActionsHtml = $this->renderRowActions($row);
            $rowActionsCell = $this->templater()->format('rowActionsCell', [
                'actions' => $rowActionsHtml,
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
            $cellAttributes['data-row-id'] = $row->id;
            $cellAttributes['data-field'] = $fieldName;
            $cellAttributes['data-label'] = $field['label'];

            $html .= $this->templater()->format('rowCell', [
                'content' => $formattedCellData,
                'attrs' => $this->templater()->formatAttributes($cellAttributes, ['label', 'formatter', 'formatterArgs'])
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

    public function renderRowActions($row = [])
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

        //$this->_View->loadHelper('Bootstrap.Button');
        //$this->_View->loadHelper('Bootstrap.Ui');

        $icon = $this->Icon->create('gear');
        $button = $this->Button->create($icon, [
            'size' => 'xs',
            'dropdown' => $actions
        ]);

        return $button;
    }

    public function renderRowActionsOld(array $rowActions, $row = [])
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
        if (el.attr('data-sortable') == 1) {

            console.log("init sortable for dt " + dtId);

            if (!$.fn.sortable) {
                console.warn("JqueryUI sortable not loaded");
            } else {
                console.log("initialize sortable")
                el.find(".dtable-body").sortable({
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

    });
</script>
SCRIPT;

        $replace = [
            '/__DATATABLE_ID__/' => $this->id(),
            '/__DATATABLE_MODEL__/' => $this->param('model'),
            '/__DATATABLE_SORTURL__/' => $this->Html->Url->build($this->param('sortable'))
        ];

        return preg_replace(array_keys($replace), array_values($replace), $script);
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
