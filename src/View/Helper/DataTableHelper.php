<?php

namespace Backend\View\Helper;


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

    public function init($params = [])
    {
        $params += ['id' => null, 'fields' => null, 'sortable' => false, 'select' => false, 'paginate' => false];
        $this->_params = $params;
        $this->_parseParams();
        $this->_parseFields();
    }

    public function param($key)
    {
        if (isset($this->_params[$key])) {
            return $this->_params[$key];
        }
        return null;
    }

    public function create($class = null)
    {
        $this->templater()->add([
            'table' => '<table{{attrs}}>'
        ]);

        $tableAttributes = [
            'id' => $this->id(),
            'class' => $this->_tableClass($class)
        ];

        $html = $this->templater()->format('table', [
            'attrs' => $this->templater()->formatAttributes($tableAttributes),
            //'cells' => $this->renderRows($row),
        ]);

        return $html;

    }

    public function end()
    {
        return '</table>';
    }

    public function id()
    {
        if (!$this->_id) {
            $this->_id = uniqid('dt');
        }
        return $this->_id;
    }

    protected function _tableClass($defaults = '')
    {
        $class = $defaults;
        if ($this->_params['sortable']) {
            $class .= ' sortable';
        }

        if ($this->_params['select']) {
            $class .= ' select';
        }
        return trim($class);
    }

    protected function _parseParams()
    {
        if (!$this->_params['id']) {
            $this->_id = $this->_params['id'];
        }
        if ($this->_params['sortable']) {
            //$this->_params = $this->Html->addClass('sortable', $this->_params);
            //$this->scriptBlock('$("#' . $this->id() . ' tr").sortable()');
        }
    }

    protected function _parseFields()
    {

        $_defaultFieldConfig = ['title' => null, 'type' => 'string', 'formatter' => null];
        foreach ($this->_params['fields'] as $field => $conf)
        {
            if (is_numeric($field)) {
                $field = $conf;
                $conf = [];
            }

            $conf += $_defaultFieldConfig;


            if ($conf['title'] === null) {
                $conf['title'] = Inflector::humanize($field);
            }

            $this->_fields[$field] = $conf;
        }
    }

    public function renderHead()
    {
        $this->templater()->add([
            'head' => '<thead><tr>{{cells}}{{actionscell}}</tr></thead>'
        ]);

        $actionsCell = '';
        if ($this->_params['rowActions'] !== false) {
            $actionsCell = '<th class="actions">' . __('Actions') . '</th>';
        }

        $html = $this->templater()->format('head', [
            'cells' => $this->renderHeadCells(),
            'actionscell' => $actionsCell
        ]);
        return $html;
    }

    public function renderHeadCells()
    {
        $this->templater()->add([
            'headCell' => '<th>{{content}}</th>'
        ]);

        $html = "";
        foreach ($this->_fields as $fieldName => $field)
        {
            if ($this->_params['paginate']) {
                $header = $this->Paginator->sort($fieldName);
            } else {
                $header = h($field['title']);
            }

            $html .= $this->templater()->format('headCell', [
                'content' => $header
            ]);
        }
        return $html;
    }

    public function renderBody()
    {

        $this->templater()->add([
            'head' => '<tbody>{{rows}}</tbody>'
        ]);

        $formattedRows = "";
        foreach ($this->_params['data'] as $row) {
            $formattedRows .= $this->renderRow($row);
        }

        $html = $this->templater()->format('head', [
            'rows' => $formattedRows,
        ]);
        return $html;
    }

    public function renderRow($row)
    {

        $this->templater()->add([
            'row' => '<tr{{attrs}}>{{cells}}{{actionscell}}</tr>',
            'rowActionsCell' => '<td class="actions">
                <div class="dropdown">
                    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Actions
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                         {{actions}}
                    </ul>
                </div>
            </td>'
        ]);


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
            'data-id' => $row['id'],
            'class' => ''
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
        $this->templater()->add([
            'rowCell' => '<td{{attrs}}>{{content}}</td>'
        ]);

        $html = "";

        // multiselect checkbox
        $html .= $this->_renderRowSelectCell($row);

        foreach ($this->_fields as $fieldName => $field)
        {
            $cellData = Hash::get($row, $fieldName);

            $formattedCellData = $this->_formatRowCellData($cellData, $field['formatter'], $row, $fieldName);
            $cellAttributes = [];

            $html .= $this->templater()->format('rowCell', [
                'content' => $formattedCellData,
                'attrs' => $this->templater()->formatAttributes($cellAttributes)
            ]);
        }
        return $html;
    }

    protected function _renderRowSelectCell($row) {

        if (isset($this->_params['select']) && $this->_params['select'] === true) {

            $this->templater()->add([
                'rowSelectCell' => '<td>{{content}}</td>'
            ]);

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
     * @param null|boolean|callable $formatter
     * @param array $rowData
     * @return string
     */
    protected function _formatRowCellData($cellData, $formatter = null, $rowData = [], $fieldName)
    {
        /*
        if ($formatter === false) {
            return $cellData;
        }

        if ($formatter === null) {
            return h($cellData);
        }

        if (is_callable($formatter)) {
            return call_user_func_array($formatter, [$cellData, $rowData, $fieldName]);
        }
        */
        return $this->Formatter->format($cellData, $formatter, $rowData);
    }

    public function renderRowActions(array $rowActions, $row = [])
    {

        $this->templater()->add([
            'rowAction' => '<li>{{content}}</li>'
        ]);

        $html = "";
        $row = (is_object($row)) ? $row->toArray() : $row;
        // rowActions
        foreach ($rowActions as $rowAction) {
            $title = $url = $attr = null;
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
            debug($this->_params);
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
        array_walk($inserts, function(&$val, $key) {
            $val = (string) $val;
        });

        return Text::insert($tokenStr, $inserts);
    }
}