<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Utility\Inflector;

class ExternalEntityAction extends BaseEntityAction
{
    protected $_action = null;

    protected $_attributes = [];

    protected $label = 'External';

    public $scope = ['table'];

    protected $_url = null;

    public function __construct($action, array $options = [])
    {
        $options += ['url' => null, 'label' => null, 'scope' => [], 'attrs' => []];
        $this->_action = $action;
        $this->_attributes = $options['attrs'];
        $this->scope = $options['scope'];
        $this->_url = $options['url'];
        $this->label = $options['label'] ?: Inflector::humanize($this->_action);
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getAttributes(): array
    {
        return $this->_attributes;
    }

    protected function _execute(Controller $controller)
    {
        $entity = $this->entity();

        return $controller->redirect($this->_buildUrl($this->_url, $entity->toArray()));
    }

    protected function _buildUrl($template, $entity)
    {
        return $this->_replaceTokens($template, $entity);
    }
}
