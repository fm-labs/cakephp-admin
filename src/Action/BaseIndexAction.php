<?php
declare(strict_types=1);

namespace Admin\Action;

use Admin\Action\Interfaces\IndexActionInterface;
use Cake\Controller\Controller;

/**
 * Class BaseIndexAction
 *
 * @package Admin\Action
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
        'include' => [],
        'exclude' => [],
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
        parent::execute($controller);

        // read config from controller view vars
        foreach (array_keys($this->_defaultConfig) as $key) {
            $this->_config[$key] = $controller->viewVars[$key] ?? $this->_defaultConfig[$key];
        }

        // detect model class
        if (!isset($controller->viewVars['modelClass'])) {
            $this->_config['modelClass'] = $controller->modelClass;
        }

        // load helpers
        if (isset($controller->viewVars['helpers'])) {
            $controller->viewBuilder()->setHelpers($controller->viewVars['helpers'], true);
        }

        // custom template
        if (isset($controller->viewVars['template'])) {
            $this->template = $controller->viewVars['template'];
        }

        // fields
        $cols = $this->_normalizeColumns($this->_config['fields']);

        if (empty($cols)) {
            // if no fields are defined, use first 10 columns from table schema
            $cols = array_slice($this->model()->getSchema()->columns(), 0, 10);
            $cols = $this->_normalizeColumns($cols);
        }

        // fields whitelist
        if ($this->_config['include'] === true) {
            $this->_config['include'] = array_keys($cols);
        }

        if (!empty($this->_config['include'])) {
            $cols = array_filter($cols, function ($key) {
                return in_array($key, $this->_config['include']);
            }, ARRAY_FILTER_USE_KEY);
        }

        // fields blacklist
        if ($this->_config['exclude']) {
            $cols = array_filter($cols, function ($key) {
                return !in_array($key, $this->_config['exclude']);
            }, ARRAY_FILTER_USE_KEY);
        }

        $this->_config['fields'] = $cols;

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
     * @param \Cake\Controller\Controller $controller Controller instance
     * @return null|void|\Cake\Http\Response
     */
    abstract protected function _execute(Controller $controller);

}
