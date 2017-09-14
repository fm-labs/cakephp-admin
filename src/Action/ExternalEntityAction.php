<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Routing\Router;
use Cake\Utility\Inflector;

class ExternalEntityAction extends BaseEntityAction
{
    protected $_scope = ['table'];

    public $action;
    public $url;

    public $noTemplate = true;

    public function __construct($action, array $options = [])
    {

        $options += ['url' => null, 'label' => null, 'scope' => [], 'attrs' => []];
        $this->options = $options;
        $this->action = $action;
        $this->url = $options['url'];
        $this->_scope = $this->options['scope'];
        $this->_attributes = $this->options['attrs'];
    }

    public function getAlias()
    {
        return Inflector::humanize($this->action);
    }

    public function getLabel()
    {
        if ($this->options['label']) {
            return $this->options['label'];
        }
        return Inflector::humanize(Inflector::underscore($this->action));
    }

    public function getAttributes()
    {
        return $this->_attributes;
    }

    protected function _execute(Controller $controller)
    {
        $entity = $this->entity();
        $controller->redirect($this->_buildUrl($this->url, $entity->toArray()));
    }

    protected function _buildUrl($template, $entity)
    {
        return $this->_replaceTokens($template, $entity);
    }
}