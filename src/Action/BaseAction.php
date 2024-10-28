<?php
declare(strict_types=1);

namespace Admin\Action;

use Admin\Action\Interfaces\ActionInterface;
use Admin\Action\Interfaces\EntityActionInterface;
use Admin\Action\Interfaces\IndexActionInterface;
use Cake\Controller\Controller;
use Cake\Core\Exception\CakeException;
use Cake\Core\InstanceConfigTrait;
use Cake\ORM\Table;
use Cake\Utility\Inflector;

abstract class BaseAction implements ActionInterface
{
    use InstanceConfigTrait;

    protected $_defaultConfig = [];

    /**
     * @var string Action label
     */
    protected $label;

    /**
     * @var string|null Action plugin
     */
    protected ?string $plugin = "Admin";

    /**
     * @var Controller|null Active controller. Available only after setController() has been called.
     */
    protected ?Controller $controller = null;

    /**
     * @var \Cake\Http\ServerRequest|null Active request. Available only after setController() has been called.
     */
    protected ?\Cake\Http\ServerRequest $request = null;

    /**
     * @var array List of action scopes
     */
    protected $scope = [];

    /**
     * @var string Name of the action view template
     */
    protected $template = null;

    /**
     * @var string|null Action view template path
     */
    protected ?string $templatePath = "Action";

    /**
     * @var \Cake\ORM\Table|null
     */
    protected ?Table $table = null;

    /**
     * @param array $config Action config
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }

//    /**
//     * @return \Cake\Http\Response|void|null
//     */
//    public function __invoke()
//    {
//        $this->execute($this->getController());
//    }

    /**
     * Execute the action in context of controller.
     * Subclasses SHOULD call this parent method in order to properly use the convenience methods in this class.
     *
     * @param Controller $controller Active controller
     * @return \Cake\Http\Response|void|null
     */
    public function execute(Controller $controller)
    {
        //$this->mergeControllerVars();
        $this->setController($controller);
    }

    /**
     * Imports all controller view vars to action config.
     *
     * @return void
     */
    protected function mergeControllerVars(): void
    {
        //$controllerVars = ['modelClass'];
        if (isset($this->getController()->modelClass) && !$this->get('modelClass')) {
            $this->setConfig('modelClass', $this->getController()->modelClass);
        }
        if (isset($this->getController()->defaultTable) && !$this->get('modelClass')) {
            $this->setConfig('modelClass', $this->getController()->defaultTable);
        }

        // read config from controller view vars
        $viewVars = $this->getController()->viewBuilder()->getVars();
        foreach (array_keys($this->_defaultConfig) as $key) {
            $this->_config[$key] = $viewVars[$key] ?? $this->_defaultConfig[$key];
        }

//        // apply config vars as controller view vars, if not set
//        foreach ($this->_config as $key => $val) {
//            if ($this->get($key) === null) {
//                $this->set($key, $val);
//            }
//        }
    }

    public function getName()
    {
        return $this->getController()->getRequest()->getParam('action');
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getVar(string $key): mixed
    {
        return $this->getController()->viewBuilder()->getVar($key);
    }

    /**
     * @return array
     */
    public function getVars(): array
    {
        return $this->getController()->viewBuilder()->getVars();
    }

    /**
     * Alias for getVar().
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->getVar($key);
    }

    /**
     * @param string $key
     * @param mixed $val
     * @return $this
     */
    public function setVar(string $key, mixed $val): static
    {
        $this->getController()->viewBuilder()->setVar($key, $val);
        return $this;
    }

    /**
     * @param array $data
     * @param bool $merge
     * @return $this
     */
    public function setVars(array $data, bool $merge = true): static
    {
        $this->getController()->viewBuilder()->setVars($data, $merge);
        return $this;
    }

    /**
     * Alias for setVar().
     *
     * @param string $key
     * @param mixed $val
     * @return $this
     */
    public function set(string $key, mixed $val): static
    {
        // legacy workaround
        if ($key === "fields.whitelist") {
            \Cake\Core\deprecationWarning("The `fields.whitelist` option is deprecated. Use `include` instead.");
            $key = "include";
        } elseif ($key === "fields.blacklist") {
            \Cake\Core\deprecationWarning("The `fields.blacklist` option is deprecated. Use `exclude` instead.");
            $key = "exclude";
        }

        if (in_array($key, array_keys($this->_defaultConfig))) {
            //debug("Trying to set config variable as template variable: {$key}");
            deprecationWarning("Trying to set config variable as template variable: {$key}");
            //$this->_config[$key] = $val;
            $this->setConfig($key, $val);
        }

        return $this->setVar($key, $val);
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
    public function getPlugin(): string
    {
        return $this->plugin;
    }

    /**
     * @return string|null
     */
    public function getTemplate(): ?string
    {
//        if ($this->template === null) {
//            $className = static::class;
//            [$ns, $actionClass] = namespaceSplit($className);
//            $actionClass = substr($actionClass, 0, -strlen("Action"));
//            $actionClass = Inflector::underscore($actionClass);
//
//            return $this->plugin ? $this->plugin . '.' . $actionClass : $actionClass;
//        }

        return $this->template;
    }

    /**
     * @return string|null
     */
    public function getTemplatePath(): ?string
    {
        return $this->templatePath;
    }

    /**
     * @param string $template Action template name
     * @param string|null $templatePath Action template path
     * @return $this
     */
    public function setTemplate(string $template, ?string $templatePath = null): static
    {
        //[$plugin, $template] = pluginSplit($template);
        //$this->plugin = $plugin;
        $this->template = $template;
        $this->templatePath = $templatePath;

        return $this;
    }

    /**
     * Controller accessor.
     *
     * @return Controller
     * @deprecated Use getController() instead.
     */
    public function controller(): Controller
    {
        deprecationWarning("BaseAction::controller() is deprecated. Use getController() instead.");

        return $this->getController();
    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        if (!$this->controller) {
            throw new \RuntimeException(
                "Controller not loaded. The action must be executed in order to access the controller object."
            );
        }

        return $this->controller;
    }

    /**
     * @param Controller|null $controller
     * @return $this
     */
    public function setController(?Controller $controller): static
    {
        $this->controller = $controller;
        $this->request = $controller->getRequest();

        $this->mergeControllerVars();

        return $this;
    }

    /**
     * Model accessor.
     *
     * @return \Cake\ORM\Table
     */
    public function model(): Table
    {
        if (!$this->table) {
            $modelClass = $this->detectModelClass();
            $this->table = $this->getController()->fetchTable($modelClass);
        }

        return $this->table;
    }

    protected function detectModelClass() {
        $modelClass = $this->getConfig('modelClass');
        if (!$modelClass) {
            $modelClass = $this->get('modelClass');
        }

        if (!$modelClass && $this->controller) {
            // get protected defaultTable member via reflection
            //$modelClass = $this->controller->defaultTable ?: null;
            $reflection = new \ReflectionClass($this->controller);
            if ($reflection->hasProperty('defaultTable')) {
                $property = $reflection->getProperty('defaultTable');
                $property->setAccessible(true);
                $modelClass = $property->getValue($this->controller);
                $property->setAccessible(false);
            }
        }
        if (!$modelClass && $this->controller) {
            //$modelClass = $this->controller->modelClass ?: null;
            $reflection = new \ReflectionClass($this->controller);
            if ($reflection->hasProperty('modelClass')) {
                $property = $reflection->getProperty('modelClass');
                $property->setAccessible(true);
                $modelClass = $property->getValue($this->controller);
                $property->setAccessible(false);
            }
        }
        return $modelClass;
    }

    /**
     * Convenience method for redirecting
     *
     * @param string|array|null $url Redirect URL
     * @return \Cake\Http\Response
     */
    protected function redirect($url)
    {
        return $this->getController()->redirect($url);
    }

    /**
     * Convenience method for flashing success messages
     *
     * @param string|null $msg The flash message
     * @return void
     */
    protected function flashSuccess(string $msg = null): void
    {
        $msg = $msg ?: __d('admin', 'Ok');
        $this->getController()->Flash->success($msg);
    }

    /**
     * Convenience method for flashing success messages
     *
     * @param string|null $msg The flash message
     * @return void
     */
    protected function flashError(string $msg = null): void
    {
        $msg = $msg ?: __d('admin', 'Failed');
        $this->getController()->Flash->error($msg);
    }

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
