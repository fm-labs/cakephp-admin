<?php

namespace Backend\Action;


use Backend\Action\Interfaces\ActionInterface;
use Cake\Controller\Controller;
use Cake\Core\InstanceConfigTrait;
use Cake\Network\Exception\NotImplementedException;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

abstract class BaseAction implements ActionInterface
{
    use InstanceConfigTrait;

    /**
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * @var \Cake\Controller\Controller
     */
    protected $_controller;

    /**
     * @var \Cake\ORM\Table
     */
    protected $_table;

    /**
     * @var \Cake\Network\Request
     */
    protected $_request;

    /**
     * @var array List of enabled scopes
     */
    protected $_scope = [];

    /**
     * @var string
     */
    protected $_label;

    /**
     * @var string
     */
    public $template = null;

    /**
     * @param Controller $controller
     * @param array $config
     */
    public function __construct(Controller $controller, array $config = [])
    {
        $this->_controller = $controller;
        $this->_request =& $controller->request;
        $this->config($config);
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        $class = explode('\\', get_class($this));
        $class = array_pop($class);

        return Inflector::underscore(substr($class, 0, -6));
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'list'];
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
    public function getScope()
    {
        return $this->_scope;
    }

    /**
     * Set scope list for action.
     * @return $this
     */
    public function setScope($scope)
    {
        $this->_scope = (array) $scope;

        return $this;
    }

    /**
     * Check if action has given scope
     * @return boolean
     */
    public function hasScope($scope)
    {
        return in_array($scope, $this->_scope);
    }

    /**
     * @return bool|\Cake\ORM\Table
     */
    public function model()
    {
        if (!$this->_config['modelClass']) {
            //throw new \Exception(get_class($this) . ' has no model class defined');
            return false;
        }

        if (!$this->_table) {
            $this->_table = TableRegistry::get($this->_config['modelClass']);
        }

        return $this->_table;
    }

    /**
     * @param Controller $controller
     * @return Response|null
     */
    abstract protected function _execute(Controller $controller);

    protected function _flashSuccess($msg = null)
    {
        $msg = ($msg) ?: __d('backend', 'Ok');
        $this->_controller->Flash->success($msg);
    }

    protected function _flashError($msg = null)
    {
        $msg = ($msg) ?: __d('backend', 'Failed');
        $this->_controller->Flash->error($msg);
    }

    protected function _redirect($url)
    {
        return $this->_controller->redirect($url);
    }
}