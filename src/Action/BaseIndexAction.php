<?php

namespace Backend\Action;

use Backend\Action\Interfaces\IndexActionInterface;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Network\Exception\NotImplementedException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

/**
 * Class BaseIndexAction
 *
 * @package Backend\Action
 */
abstract class BaseIndexAction extends BaseAction implements IndexActionInterface
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'actions' => [],
        'rowActions' => []
    ];

    /**
     * @var array List of enabled scopes
     */
    protected $_scope = ['table','form'];

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
        $cols = $this->model()->schema()->columns();
        if (!empty($this->_config['fields'])) {
            foreach ($this->_config['fields'] as $field => $fieldConfig) {
                if (is_numeric($field)) {
                    $field = $fieldConfig;
                    $fieldConfig = [];
                }
                $cols[$field] = $fieldConfig;
            }
        }
        $this->_config['fields'] = $cols;

        // actions
        if ($this->_config['actions'] !== false) {
            //$event = $controller->dispatchEvent('Backend.Controller.buildIndexActions', ['actions' => $this->_config['actions']]);
            //$this->_config['actions'] = (array)$event->data['actions'];
        }


        //if ($this->_config['rowActions'] !== false) {
        //    $event = $controller->dispatchEvent('Backend.Action.Index.getRowActions', ['actions' => $this->_config['rowActions']]);
        //    $this->_config['rowActions'] = (array)$event->data['actions'];
        //}

        try {
            $response = $this->_execute($controller);
            //return $response;

        } catch (\Exception $ex) {
            $controller->Flash->error($ex->getMessage());
            //$controller->redirect($controller->referer());
        }
    }




}
