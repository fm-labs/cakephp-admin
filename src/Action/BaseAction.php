<?php

namespace Backend\Action;

use Backend\Action\Interfaces\ActionInterface;
use Cake\Controller\Controller;
use Cake\Core\InstanceConfigTrait;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

abstract class BaseAction /*extends Controller*/ implements ActionInterface
{
    use InstanceConfigTrait;

    /**
     * @var \Cake\ORM\Table
     */
    protected $_table;

    /**
     * @var array List of enabled scopes
     */
    public $scope = [];

    /**
     * @var string
     */
    protected $_label;

    /**
     * @var \Cake\Controller\Controller
     */
    public $controller;

    /**
     * @var \Cake\Network\Request
     */
    public $request;

    /**
     * @var string
     */
    public $template = null;

    /**
     * @param Controller $controller The controller instance
     * @param array $config Action config
     */
    public function __construct(Controller $controller, array $config = [])
    {
        $this->controller = $controller;
        $this->request =& $controller->request;
        $this->setConfig($config);
    }

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        $class = explode('\\', get_class($this));
        $class = array_pop($class);
        $alias = Inflector::underscore(substr($class, 0, -6));

        return Inflector::humanize($alias);
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
    public function getScope()
    {
        return $this->scope;
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
            $this->_table = TableRegistry::getTableLocator()->get($this->_config['modelClass']);
        }

        return $this->_table;
    }

    /**
     * Convenience method for redirecting
     *
     * @param string|array|null $url Redirect URL
     * @return Response
     */
    public function redirect($url)
    {
        return $this->controller->redirect($url);
    }

    /**
     * @param Controller $controller The controller instance
     * @return Response|null
     */
    //abstract protected function _execute(Controller $controller);

    /**
     * Convenience method for flashing success messages
     *
     * @param string $msg The flash message
     * @return void
     */
    protected function _flashSuccess($msg = null)
    {
        $msg = ($msg) ?: __d('backend', 'Ok');
        $this->controller->Flash->success($msg);
    }

    /**
     * Convenience method for flashing success messages
     *
     * @param string $msg The flash message
     * @return void
     */
    protected function _flashError($msg = null)
    {
        $msg = ($msg) ?: __d('backend', 'Failed');
        $this->controller->Flash->error($msg);
    }
}
