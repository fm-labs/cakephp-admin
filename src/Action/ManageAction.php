<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

class ManageAction extends BaseEntityAction implements EventListenerInterface
{
    public $scope = ['table', 'form'];

    protected $_defaultConfig = [
        'entity' => null,
        'entityOptions' => [],
        'modelClass' => null,
        'modelId' => null,
        'actions' => [],
        'tabs' => [],
    ];

    public $template = "Admin.manage";

    /**
     * {@inheritDoc}
     */
    public function getLabel(): string
    {
        return __d('admin', 'Manage');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes(): array
    {
        return ['data-icon' => 'file-o'];
    }

    /**
     * {@inheritDoc}
     */
    public function _execute(Controller $controller)
    {
        $entity = $this->entity();

        if (empty($this->_config['tabs'])) {
            $this->_config['tabs']['view'] = [
                'title' => __d('admin', 'Data'),
                'url' => ['action' => 'view', $entity->id],
            ];
        }

        $controller->set('tabs', $this->_config['tabs']);
        $controller->set('entity', $entity);
        $controller->set('_serialize', ['entity']);
    }

    public function beforeRender(Event $event)
    {
        $entity = $event->getSubject()->viewVars['entity'];
        $modelClass = $event->getSubject()->viewVars['modelClass'];

        $event->getSubject()->viewVars['tabs']['data'] = [
            'title' => __d('admin', 'Data'),
            'url' => ['plugin' => 'Admin', 'controller' => 'Entity', 'action' => 'view', $modelClass, $entity->id],
        ];
    }

    public function implementedEvents(): array
    {
        return [
            //'Controller.beforeRender' => 'beforeRender'
        ];
    }
}
