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
 */
class DataTableHelper extends Helper
{
    use StringTemplateTrait;

    public $helpers = ['Html', 'Form', 'Paginator'];

    protected $_params = [];

    protected $_fields = [];

    public function create($params = [])
    {
        $this->_params = $params;
        $this->_parseFields();
    }

    private function _parseFields()
    {

        $_defaultHeader = ['title' => null, 'type' => 'string', 'formatter' => null];
        foreach ($this->_params['fields'] as $field => $conf)
        {
            if (is_numeric($field)) {
                $field = $conf;
                $conf = [];
            }

            $conf += $_defaultHeader;


            if ($conf['title'] === null) {
                $conf['title'] = Inflector::humanize($field);
            }

            $this->_fields[$field] = $conf;
        }
    }

    public function renderHead()
    {
        $this->templater()->add([
            'fieldCell' => '<th>{{content}}</th>'
        ]);

        $html = "";
        foreach ($this->_fields as $fieldName => $field)
        {
            if ($this->_params['paginate']) {
                $header = $this->Paginator->sort($fieldName);
            } else {
                $header = h($field['title']);
            }

            $html .= $this->templater()->format('fieldCell', [
                'content' => $header
            ]);
        }
        return $html;
    }

    public function renderRow($row)
    {
        $this->templater()->add([
            'rowCell' => '<td>{{content}}</td>'
        ]);

        $html = "";

        // multiselect checkbox
        $html .= $this->_renderRowSelectCell($row);

        foreach ($this->_fields as $fieldName => $field)
        {
            $cellData = Hash::get($row, $fieldName);

            $formattedCellData = $this->_formatRowCellData($cellData, $field['formatter'], $row, $fieldName);

            $html .= $this->templater()->format('rowCell', [
                'content' => $formattedCellData
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
        if ($formatter === false) {
            return $cellData;
        }

        if ($formatter === null) {
            return h($cellData);
        }

        if (is_callable($formatter)) {
            return call_user_func_array($formatter, [$cellData, $rowData, $fieldName]);
        }
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

    public function debug()
    {
        if (isset($this->_params['debug']) && $this->_params['debug']) {
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