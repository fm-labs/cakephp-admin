<?php

namespace Backend\Action;

use Backend\Action\Interfaces\EntityActionInterface;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Network\Exception\BadRequestException;
use Cake\ORM\Association;

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

    public $template = "Backend.manage";

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __d('backend', 'Manage');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
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
                'title' => __d('backend', 'Data'),
                'url' => ['action' => 'view', $entity->id]
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
            'title' => __d('backend', 'Data'),
            'url' => ['plugin' => 'Backend', 'controller' => 'Entity', 'action' => 'view', $modelClass, $entity->id]
        ];
    }

    public function implementedEvents()
    {
        return [
            //'Controller.beforeRender' => 'beforeRender'
        ];
    }
}
