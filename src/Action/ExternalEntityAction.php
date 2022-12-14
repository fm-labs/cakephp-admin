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

    /**
     * @inheritDoc
     *
     * The $id parameter is ignored here. The entity ID is always used instead.
     */
    public function getUrl($id)
    {
        return $this->_buildUrl(['id' => $id]);
    }

    protected function _execute(Controller $controller)
    {
        $redirectUrl = $this->_buildUrl();
        return $controller->redirect($redirectUrl);
    }

    protected function _buildUrl($data = null)
    {
        if ($data === null) {
            $data = $this->entity()->toArray();
        }
        return $this->_replaceTokens($this->_url, $data);
    }
}
