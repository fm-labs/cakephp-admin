<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Utility\Inflector;

class InlineEntityAction extends BaseEntityAction
{
    public $scope = [];
    protected $_attributes = [];
    protected $_callable;
    protected $_executed = false;
    protected $_filter;

    /**
     * @var string The action name or alias
     */
    public $action;

    /**
     * @var array Options for this action
     */
    public $options;

    public function __construct(Controller $controller, array $options = [], ?callable $callable = null, ?callable $filter = null)
    {
        parent::__construct([]);
        $options += ['action' => null, 'form' => null, 'label' => null, 'scope' => [], 'attrs' => []];

        $this->action = $options['action'];
        $this->options = $options;
        $this->scope = $this->options['scope'];
        $this->_attributes = $this->options['attrs'];

        if ($this->_callable === null) {
            $callable = [$controller, $this->action];
        }
        $this->_callable = $callable;
        $this->_filter = null;
    }

    public function getLabel(): string
    {
        if ($this->options['label']) {
            return $this->options['label'];
        }

        return Inflector::humanize(Inflector::underscore($this->action));
    }

    public function getAttributes(): array
    {
        return $this->_attributes;
    }

    public function getUrl($id) {
        return ['action' => $this->action, $id];
    }

    protected function _execute(Controller $controller)
    {
        //if ($this->_executed == true) {
        //    return;
        //}
//        if (!method_exists($controller, $this->action)) {
//            throw new MissingActionException([
//                'controller' => $controller->name,
//                'action' => $this->action,
//                'prefix' => isset($controller->getRequest()->getParam('prefix')) ? $controller->getRequest()->getParam('prefix') : '',
//                'plugin' => $controller->getRequest()->getParam('plugin'),
//            ]);
//        }
//
//        if (!is_callable($this->_callable)) {
//            throw new \InvalidArgumentException("InlineAction " . $this->action . " has no valid callback");
//        }

        //$this->_config['modelId'] = $controller->getRequest()->getParam('pass')[0];
        //$entity = $this->entity();
        //$controller->set('entity', $entity);
        //$this->_executed = true;
        return call_user_func_array($this->_callable, $controller->getRequest()->getParam('pass'));
    }
}
