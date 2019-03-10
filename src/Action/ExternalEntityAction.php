<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Routing\Router;
use Cake\Utility\Inflector;

class ExternalEntityAction extends BaseEntityAction
{
    protected $_action = null;

    protected $_attributes = [];

    protected $_label = 'External';

    public $scope = ['table'];

    protected $_url = null;

    public function __construct($action, array $options = [])
    {
        $options += ['url' => null, 'label' => null, 'scope' => [], 'attrs' => []];
        $this->_action = $action;
        $this->_attributes = $options['attrs'];
        $this->scope = $options['scope'];
        $this->_url = $options['url'];
        $this->_label = ($options['label']) ?: Inflector::humanize($this->_action);
    }

    public function getLabel()
    {
        return $this->_label;
    }

    public function getAttributes()
    {
        return $this->_attributes;
    }

    protected function _execute(Controller $controller)
    {
        $entity = $this->entity();
        $controller->redirect($this->_buildUrl($this->_url, $entity->toArray()));
    }

    protected function _buildUrl($template, $entity)
    {
        return $this->_replaceTokens($template, $entity);
    }
}
