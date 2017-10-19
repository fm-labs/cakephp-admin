<?php

namespace Backend\Action;

use Backend\Action\Interfaces\EntityActionInterface;
use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Network\Exception\NotImplementedException;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Cake\Utility\Text;

abstract class BaseEntityAction implements EntityActionInterface
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'actions' => []
    ];

    /**
     * @var array
     */
    protected $_config = [];

    /**
     * @var EntityInterface
     */
    protected $_entity;

    /**
     * @var array List of enabled scopes
     */
    protected $_scope = [];

    public function __construct(Controller $controller, array $config = [])
    {

    }

    public $template = null;

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        $class = explode('\\', get_class($this));
        $class = array_pop($class);
        return substr($class, 0, -6);
    }

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        $alias = $this->getAlias();
        return Inflector::humanize($alias);
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return [];
    }

    public function hasForm()
    {
        return false;
    }

    public function setScope($scope)
    {
        $this->_scope = (array) $scope;
    }

    public function getScope()
    {
        return $this->_scope;
    }

    public function hasScope($scope)
    {
        return in_array($scope, $this->_scope);
    }

    public function isUsable(EntityInterface $entity)
    {
        return true;
    }

    public function execute(Controller $controller)
    {
        // read config from controller view vars
        foreach (array_keys($this->_defaultConfig) as $key) {
            $this->_config[$key] = (isset($controller->viewVars[$key]))
                ? $controller->viewVars[$key]
                : $this->_defaultConfig[$key];
        }

        // detect model class and load entity
        if (!isset($controller->viewVars['modelClass'])) {
            $this->_config['modelClass'] = $controller->modelClass;
        }
        if (!isset($controller->viewVars['modelId'])) {
            $modelId = $controller->request->param('id');
            $modelId = ($modelId) ?: $controller->request->param('pass')[0]; // @TODO request param 'pass' might be empty or unset
            $this->_config['modelId'] = $modelId;
        }
        if (isset($controller->viewVars['entity'])) {
            $this->_entity = $controller->viewVars['entity'];
        }

        try {
            $entity = $this->entity();

            // load helpers
            if (isset($controller->viewVars['helpers'])) {
                $controller->viewBuilder()->helpers($controller->viewVars['helpers'], true);
            }


            $controller->set('entity', $entity);
            return $this->_execute($controller);

        } catch (\Exception $ex) {
            $controller->Flash->error($ex->getMessage());
            //$controller->redirect($controller->referer());
        }
    }

    abstract protected function _execute(Controller $controller);
    //{
    //    throw new NotImplementedException(get_class($this) . ' has no _execute() method implemented');
    //}

    public function model()
    {
        if (!$this->_config['modelClass']) {
            //throw new \Exception(get_class($this) . ' has no model class defined');
            return false;
        }

        return TableRegistry::get($this->_config['modelClass']);
    }

    public function entity()
    {
        if (!$this->_entity) {

            if (!$this->_config['modelId']) {
                throw new \Exception(get_class($this) . ' has no model ID defined');
            }

            $this->_entity = $this->model()->get($this->_config['modelId']);
        }

        return $this->_entity;
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

    public function beforeRender(Event $event) {

        debug("before Render");

        /*
        // actions
        $controller = $event->subject();
        $entity = $this->entity();

        if ($this->_config['actions'] !== false) {
            //$event = $controller->dispatchEvent('Backend.Controller.buildEntityActions', [
            //    'entity' => $entity,
            //    'actions' => (array) $this->_config['actions']
            //]);
            //$this->_config['actions'] = (array)$event->data['actions'];

            foreach ($this->_config['actions'] as $idx => &$action) {
                list($title, $url, $attr) = $action;
                $url = $this->_replaceTokens($url, $entity->toArray());
                $action = [$title, $url, $attr];
            }

            $controller->set('actions', $this->_config['actions']);
        }
        */
    }

}
