<?php
declare(strict_types=1);

namespace Admin\Action;

use Admin\Action\Interfaces\ActionInterface;
use Admin\Action\Interfaces\EntityActionInterface;
use Admin\Action\Interfaces\IndexActionInterface;
use Cake\Controller\Controller;
use Cake\Core\InstanceConfigTrait;
use Cake\ORM\Table;
use Cake\Utility\Inflector;

abstract class BaseAction implements ActionInterface
{
    use InstanceConfigTrait;

    /**
     * @var string Action label
     */
    protected $label;

    /**
     * @var string Action plugin
     */
    protected $plugin = "Admin";

    /**
     * @var \Cake\Controller\Controller Active controller
     */
    protected $controller;

    /**
     * @var \Cake\Http\ServerRequest Active request
     */
    protected $request;

    /**
     * @var array List of action scopes
     */
    protected $scope = [];

    /**
     * @var string Name of the action view template
     */
    protected $template = null;

    /**
     * @var null Action view template path
     */
    protected $templatePath = "Action";

    /**
     * @var \Cake\ORM\Table
     */
    protected $table;

    /**
     * @var array Default configuration
     */
    protected $_defaultConfig = [];

    /**
     * @param array $config Action config
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }

    /**
     * Execute the action in context of controller.
     * Subclasses SHOULD call this parent method in order to properly use the convenience methods in this class.
     *
     * @param \Cake\Controller\Controller $controller Active controller
     * @return \Cake\Http\Response|void|null
     */
    public function execute(Controller $controller)
    {
        $this->controller = $controller;
        $this->request = $controller->getRequest();

        $this->mergeControllerVars();
    }

    /**
     * Imports all controller view vars to action config.
     *
     * @return void
     */
    protected function mergeControllerVars(): void
    {
        $viewVars = $this->controller()->viewBuilder()->getVars();

        // read config from controller view vars
        foreach (array_keys($this->_defaultConfig) as $key) {
            $this->_config[$key] = $viewVars[$key] ?? $this->_defaultConfig[$key];
        }
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        if (!$this->label) {
            $class = explode('\\', static::class);
            $class = array_pop($class);
            $alias = Inflector::underscore(substr($class, 0, -6));

            return Inflector::humanize($alias);
        }

        return $this->label;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return ['data-icon' => 'list']; //@todo Remove default value
    }

    /**
     * @inheritDoc
     */
    public function getScope(): array
    {
        if (empty($this->scope)) {
            $scope = [];
            if ($this instanceof IndexActionInterface) {
                $scope[] = ActionInterface::SCOPE_INDEX;
            }
            if ($this instanceof EntityActionInterface) {
                $scope[] = ActionInterface::SCOPE_ENTITY;
            }

            return $scope;
        }

        return $this->scope;
    }

    /**
     * @return string
     */
    public function getPlugin()
    {
        return $this->plugin;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        if ($this->template === null) {
            $className = static::class;
            [$ns, $actionClass] = namespaceSplit($className);
            $actionClass = substr($actionClass, 0, -strlen("Action"));
            $actionClass = Inflector::underscore($actionClass);

            return $this->plugin ? $this->plugin . '.' . $actionClass : $actionClass;
        }

        return $this->template;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * @param string $template Action template name
     * @param string|null $templatePath Action template path
     * @return $this
     */
    public function setTemplate(string $template, ?string $templatePath = null)
    {
        [$plugin, $template] = pluginSplit($template);
        $this->plugin = $plugin;
        $this->template = $template;
        $this->templatePath = $templatePath;

        return $this;
    }

    /**
     * Controller accessor.
     *
     * @return \Cake\Controller\Controller
     */
    public function controller(): Controller
    {
        if (!$this->controller) {
            throw new \RuntimeException(
                "Controller not loaded. The action must be executed in order to access the controller object."
            );
        }

        return $this->controller;
    }

    /**
     * Model accessor.
     *
     * @return bool|\Cake\ORM\Table
     */
    public function model(): Table
    {
        if (!$this->table) {
            $this->table = $this->controller()->loadModel();
        }

        return $this->table;
    }

    /**
     * Convenience method for redirecting
     *
     * @param string|array|null $url Redirect URL
     * @return \Cake\Http\Response
     */
    protected function redirect($url)
    {
        return $this->controller()->redirect($url);
    }

    /**
     * Convenience method for flashing success messages
     *
     * @param string $msg The flash message
     * @return void
     */
    protected function flashSuccess($msg = null): void
    {
        $msg = $msg ?: __d('admin', 'Ok');
        $this->controller()->Flash->success($msg);
    }

    /**
     * Convenience method for flashing success messages
     *
     * @param string $msg The flash message
     * @return void
     */
    protected function flashError($msg = null): void
    {
        $msg = $msg ?: __d('admin', 'Failed');
        $this->controller()->Flash->error($msg);
    }
}
