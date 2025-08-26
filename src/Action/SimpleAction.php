<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Http\Response;
use Cake\Utility\Inflector;

class SimpleAction extends BaseAction
{
    protected array $_defaultConfig = [];

    protected array $scope = ['index'];
    protected array $_attributes = [];
    protected $_callable;
    protected bool $_executed = false;

    /**
     * @var string The action name or alias
     */
    public string $action;

    /**
     * @var array Options for this action
     */
    public array $options;

    public function __construct(Controller $controller, array $options = [], ?callable $callable = null)
    {
        parent::__construct([]);
        $options += ['action' => null, 'form' => null, 'label' => null, 'scope' => [], 'attrs' => []];

        $this->action = $options['action'];
        $this->options = $options;
        $this->scope = $this->options['scope'] ?: $this->scope;
        $this->_attributes = $this->options['attrs'];

        if ($this->_callable === null) {
            $callable = [$controller, $this->action];
        }
        $this->_callable = $callable;
        $this->_filter = null;
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        if ($this->options['label']) {
            return $this->options['label'];
        }

        return Inflector::humanize(Inflector::underscore($this->action));
    }

    /**
     * @inheritDoc
     */
    public function getScope(): array
    {
        return $this->scope;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->_attributes;
    }

    /**
     * @inheritDoc
     */
    public function execute(Controller $controller): ?Response
    {
        return call_user_func_array($this->_callable, []);
    }
}
