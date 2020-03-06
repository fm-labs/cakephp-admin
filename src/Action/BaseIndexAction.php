<?php

namespace Backend\Action;

use Backend\Action\Interfaces\IndexActionInterface;
use Cake\Controller\Controller;

/**
 * Class BaseIndexAction
 *
 * @package Backend\Action
 * @deprecated Use IndexAction instead
 */
abstract class BaseIndexAction extends BaseAction implements IndexActionInterface
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'actions' => [],
        'rowActions' => [],
        'fields' => [],
        'fields.whitelist' => [],
        'fields.blacklist' => []
    ];

    /**
     * @var array List of enabled scopes
     */
    public $scope = ['table', 'form'];

    /**
     * {@inheritDoc}
     */
    public function execute(Controller $controller)
    {
        // read config from controller view vars
        foreach (array_keys($this->_defaultConfig) as $key) {
            $this->_config[$key] = (isset($controller->viewVars[$key]))
                ? $controller->viewVars[$key]
                : $this->_defaultConfig[$key];
        }

        // detect model class
        if (!isset($controller->viewVars['modelClass'])) {
            $this->_config['modelClass'] = $controller->modelClass;
        }

        // load helpers
        if (isset($controller->viewVars['helpers'])) {
            $controller->viewBuilder()->helpers($controller->viewVars['helpers'], true);
        }

        // custom template
        if (isset($controller->viewVars['template'])) {
            $this->template = $controller->viewVars['template'];
        }

        // fields
        //$cols = $this->_normalizeColumns($this->_config['fields']);

        // UGLY WORKAROUND TO PREVENT BREAKING OLDER ADMIN PAGES USING THE DEPRECATED CONFIG SCHEME
        $cols = [];
        // fields whitelist
        if ($this->_config['fields.whitelist'] === true) {
            $cols = $this->_normalizeColumns($this->_config['fields']);
        } elseif (!empty($this->_config['fields.whitelist'])) {
            foreach ($this->_config['fields.whitelist'] as $whiteListed) {
                if (!array_key_exists($whiteListed, $cols)) {
                    $cols[$whiteListed] = [];
                }
            }
        }

        $normalized = $this->_normalizeColumns($this->_config['fields']);
        foreach ($normalized as $name => $col) {
            $cols[$name] = $col;
        }
        // END OF WORKAROUND

        if (empty($cols)) {
            // if no fields are defined, use first 5 columns from table schema
            $cols = array_slice($this->model()->schema()->columns(), 0, 10);
        }
        $cols = $this->_normalizeColumns($cols);

        // fields blacklist
        if ($this->_config['fields.blacklist']) {
            foreach ($this->_config['fields.blacklist'] as $blackListed) {
                if (array_key_exists($blackListed, $cols)) {
                    unset($cols[$blackListed]);
                }
            }
        }
        $this->_config['fields'] = $cols;

        // actions
        if ($this->_config['actions'] !== false) {
            //$event = $controller->dispatchEvent('Backend.Controller.buildIndexActions', ['actions' => $this->_config['actions']]);
            //$this->_config['actions'] = (array)$event->getData('actions');
        }

        //if ($this->_config['rowActions'] !== false) {
        //    $event = $controller->dispatchEvent('Backend.Action.Index.getRowActions', ['actions' => $this->_config['rowActions']]);
        //    $this->_config['rowActions'] = (array)$event->getData('actions');
        //}

        $response = null;
        try {
            $response = $this->_execute($controller);
        } catch (\Exception $ex) {
            $controller->Flash->error($ex->getMessage());
        } finally {
        }

        return $response;
    }

    /**
     * @param Controller $controller Controller instance
     * @return null|void|\Cake\Http\Response
     */
    abstract protected function _execute(Controller $controller);

    /**
     * @param array $columns Columns schema
     * @return array
     */
    protected function _normalizeColumns(array $columns)
    {
        $normalized = [];
        foreach ($columns as $col => $conf) {
            if (is_numeric($col)) {
                $col = $conf;
                $conf = [];
            }

            $normalized[$col] = $conf;
        }

        return $normalized;
    }
}
