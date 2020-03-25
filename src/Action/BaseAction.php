<?php
declare(strict_types=1);

namespace Backend\Action;

use Backend\Action\Interfaces\ActionInterface;
use Cake\Controller\Controller;
use Cake\Core\InstanceConfigTrait;
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
     * @var \Cake\Http\ServerRequest
     */
    public $request;

    /**
     * @var string
     */
    public $template = null;

    /**
     * @param \Cake\Controller\Controller $controller The controller instance
     * @param array $config Action config
     */
    public function __construct(Controller $controller, array $config = [])
    {
        $this->controller = $controller;
        $this->request = $controller->getRequest();
        $this->setConfig($config);
    }

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        $class = explode('\\', static::class);
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
        $modelClass = $this->_config['modelClass'] ?? $this->controller->modelClass;

        if (!$this->_table) {
            $this->_table = TableRegistry::getTableLocator()->get($modelClass);
        }

        return $this->_table;
    }

    /**
     * Convenience method for redirecting
     *
     * @param string|array|null $url Redirect URL
     * @return \Cake\Http\Response
     */
    public function redirect($url)
    {
        return $this->controller->redirect($url);
    }

    /**
     * @param \Cake\Controller\Controller $controller The controller instance
     * @return \Cake\Http\Response|null
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
        $msg = $msg ?: __d('backend', 'Ok');
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
        $msg = $msg ?: __d('backend', 'Failed');
        $this->controller->Flash->error($msg);
    }
}
