<?php

namespace Backend\Action;


use Cake\Controller\Controller;
use Cake\Network\Exception\NotImplementedException;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

abstract class BaseAction
{
    /**
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * @var array
     */
    protected $_config = [];

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
    }

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
        $msg = ($msg) ?: __d('backend','Ok');
        $this->_controller->Flash->success($msg);
    }

    protected function _flashError($msg = null)
    {
        $msg = ($msg) ?: __d('backend','Failed');
        $this->_controller->Flash->error($msg);
    }

    protected function _redirect($url)
    {
        return $this->_controller->redirect($url);
    }
}