<?php
declare(strict_types=1);

namespace Backend\View\Helper;

use Cake\Event\Event;
use Cake\View\View;

/**
 * ! Experimental / Unused !
 * @codeCoverageIgnore
 */
class FooTableHelper extends DataTableHelper
{
    //protected $_defaultField = ['type' => 'string', 'label' => null, 'class' => null, 'formatter' => null, 'formatterArgs' => []];

    /**
     * List of options that will be passed to the footable javascript
     */
    protected $_jsOptions = [];

    /**
     * {@inheritDoc}
     */
    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);
    }

    /**
     * @param \Cake\Event\Event $event The event object
     * @return void
     */
    public function beforeLayout(Event $event)
    {
        parent::beforeLayout($event);

        $this->Html->css('Backend./js/footable-bootstrap/css/footable.bootstrap.css', ['block' => true]);
        $this->Html->script('Backend./js/footable-bootstrap/js/footable.js', ['block' => true]);
        $this->Html->script('Backend./js/backend.footable.js', ['block' => true]);
    }

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
        $extra = $this->_params['extra'];

        $this->_tableArgs['data-empty'] = "Loading ...";

        // paging component
        //@todo Record start and -end in paging count format
        if ($this->_params['paginate']) {
            $this->_tableArgs['data-paging'] = 'true';
            $this->_tableArgs['data-paging-count-format'] = 'Page {CP} of {TP}'; // 'Page {CP} of {TP} . Showing {PF} - {PL} of {TR} records';
            $this->_tableArgs['data-paging-size'] = 15;
        }
        if ($this->_params['pagingLimit']) {
            $this->_tableArgs['data-paging-size'] = $this->_params['pagingLimit'];
        }

        // sorting component
        if ($this->_params['sortable']) {
            //$this->_params = $this->Html->addClass($this->_params, 'sortable');
            //$this->Html->script('$("#' . $this->id() . ' .dtable-row").sortable()', ['block' => true]);
            $this->_tableArgs['data-sorting'] = "true";
        }

        // filtering component
        if ($this->_params['filter']) {
            $this->_tableArgs['data-filtering'] = "true";
        }

        $this->options($extra);
    }

    /**
     * {@inheritDoc}
     */
    protected function _formatRowCellData($fieldName, $cellData, $formatter = null, $formatterArgs = [], $row = [])
    {
        return $cellData;
    }

    /**
     * {@inheritDoc}
     */
    protected function _renderFilterRow()
    {
        // Search is injected by FooTable JS
        return '';
    }

    /*
    protected function _renderRowActions($row)
    {
        if ($this->_params['ajax']) {
            return '';
        }
        return parent::_renderRowActions($row);
    }

    protected function _renderRowActionsCell($row)
    {
        if ($this->_params['ajax']) {
            return '';
        }
        return parent::_renderRowActionsCell($row);
    }
    */

    /**
     * {@inheritDoc}
     */
    protected function _renderRowActions($row)
    {
        $row = is_object($row) ? $row->toArray() : $row;
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
            'dropdown' => $actions,
        ]);

        return $button;
    }

    /**
     * {@inheritDoc}
     */
    protected function _renderRowActionsCell($row)
    {
        //$rowActionsHtml = $this->_renderRowActions($row);
        $actions = $row['_actions_'] ?? [];

        return $this->templater()->format('rowActionsCell', [
            'actions' => json_encode($actions),
            'attrs' => $this->_buildFieldAttributes('_actions_', [
                'type' => 'object',
                'label' => 'Actions',
                'formatter' => 'actions',
                'formatterArgs' => [],
                'data-formatter' => 'actions',
                //'data-actions' => json_encode($actions)
            ]),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    protected function _renderPagination()
    {
        // Pagination is injected by FooTable JS
        return '';
    }

    /**
     * {@inheritDoc}
     */
    protected function _renderScript($block = null)
    {
        $domId = $this->getParam('id');
        $opts = $this->_jsOptions;

        if (isset($opts['_dataUrl'])) {
            $dataUrl = $opts['_dataUrl'];
            unset($opts['_dataUrl']);

            $template = '$(document).ready(function(){ var opts = %s; opts.rows = $.getJSON("%s"); $("#%s").footable(opts); });';
            $script = sprintf($template, json_encode($opts), $dataUrl, $domId);
        } else {
            $template = '$(document).ready(function(){ $("#%s").footable(%s); });';
            $script = sprintf($template, $domId, json_encode($opts));
        }

        return $this->Html->scriptBlock($script, ['safe' => false, 'block' => $block]);
    }

    protected function _buildPaginationFieldLabel($fieldName, $field)
    {
        // Pagination is injected by FooTable JS
        return h($field['label']);
    }

    protected function _buildRowAttributes($row)
    {
        $rowAttributes = [
            'data-id' => $row['id'] ?? null,
        ];

        return $this->templater()->formatAttributes($rowAttributes);
    }

    protected function _buildFieldAttributes($fieldName, $field)
    {
        $field['data-name'] = $fieldName;
        $field['data-label'] = $field['label'];
        $field['data-type'] = $field['type'];
        $field['data-sortable'] = "true";
        $field['data-filterable'] = "true";

        if (!$field['formatter']) {
            $field['formatter'] = $field['type'];
        }

        // If formatter is passed as an array (and no callable construct)
        // extract formatter name and args
        // e.g. ['formatterName', 'arg1', 'arg2', ... ]
        if (is_array($field['formatter']) && !is_object($field['formatter'][0])) {
            $field['formatterArgs'] = $field['formatter'];
            $field['formatter'] = array_shift($field['formatterArgs']);
        }
        if (is_string($field['formatter'])) {
            $field['data-formatter'] = 'Backend.FooTable.Formatters.' . $field['formatter'];
        }
        if (isset($field['formatterArgs'])) {
            foreach ($field['formatterArgs'] as $k => $v) {
                $field['data-formatter-' . $k] = $v;
            }
        }
        if (isset($field['class'])) {
            $field['data-classes'] = $field['class'];
        }

        return $this->templater()->formatAttributes($field, array_keys($this->_defaultField));
    }
}
