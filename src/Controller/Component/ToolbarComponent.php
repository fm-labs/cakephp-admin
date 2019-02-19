<?php

namespace Backend\Controller\Component;

use Backend\Action\Interfaces\ActionInterface;
use Backend\Action\Interfaces\EntityActionInterface;
use Backend\Action\Interfaces\IndexActionInterface;
use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;

/**
 * Class ToolbarComponent
 *
 * @package Backend\Controller\Component
 */
class ToolbarComponent extends Component
{
    /**
     * Initialize actions
     *
     * @param array $config Component configuration
     * @return void
     */
    public function initialize(array $config)
    {
    }

    /**
     * Startup event handler
     *
     * @return void
     */
    public function startup()
    {
    }

    /**
     * @param Event $event The event object
     * @return void
     */
    public function beforeRender(Event $event)
    {
        $controller = $event->subject();

        // actions
        $toolbarActions = (isset($controller->viewVars['toolbar.actions'])) ? $controller->viewVars['toolbar.actions'] : [];
//        $actions = ($this->_registry->has('Action')) ? $this->_registry->get('Action')->actions : [];
//
//        foreach ($actions as $actionName => $actionConfig) {
//            if ($actionConfig['type'] == 'table' && $this->request->param('action') == 'index') {
//                $url = ['action' => $actionName];
//                $toolbarActions[$actionName] = [$actionConfig['label'], $url, $actionConfig['attrs']];
//            } else {
//                $url = ['action' => $actionName];
//                $toolbarActions[$actionName] = [$actionConfig['label'], $url, $actionConfig['attrs']];
//            }
//        }

//        if ($this->_action) {
//            if ($this->_action instanceof EntityActionInterface) {
//                $entity = $this->_action->entity();
//
//                // first add the primary action
//                /*
//                try {
//                    $actions['primary'] = $this->_buildToolbarAction('view', $this->getAction('view'), $entity);
//                } catch (\Exception $ex) {
//                    debug($ex->getMessage());
//                }
//                */
//
//                foreach ($this->listActions() as $actionName) {
//                    /*
//                    if ($actionName == 'view') {
//                        continue;
//                    }
//                    */
//
//                    $actionObj = $this->getAction($actionName);
//                    if ($actionObj == $this->_action) {
//                        continue;
//                    }
//
//                    /*
//                    if ($action == "index") {
//                        $actions[$action] = [$_action->getLabel(), ['action' => $action], $_action->getAttributes()];
//                    }
//                    else*/
//                    if ($actionObj instanceof EntityActionInterface && in_array('form', $actionObj->getScope()) && $actionObj->isUsable($entity)) {
//                        $actions[$actionName] = $this->_buildToolbarAction($actionName, $actionObj, $entity);
//                    }
//                }
//            } elseif ($this->_action instanceof IndexActionInterface) {
//                foreach ($this->listActions() as $actionName) {
//                    $actionObj = $this->getAction($actionName);
//                    if ($actionObj == $this->_action) {
//                        continue;
//                    }
//                    if (!in_array('index', $actionObj->getScope())) {
//                        continue;
//                    }
//                    $actions[$actionName] = $this->_buildToolbarAction($actionName, $actionObj, null);
//                }
//            }
//        }

        $controller->set('toolbar.actions', $toolbarActions);
    }

    /**
     * @param string $alias Action alias
     * @param ActionInterface $action The action instance
     * @param EntityInterface $entity The entity instance
     * @return array
     */
    protected function _buildToolbarAction($alias, ActionInterface $action, EntityInterface $entity = null)
    {
        if ($entity === null) {
            return [$action->getLabel(), ['action' => $alias], $action->getAttributes()];
        }

        return [$action->getLabel(), ['action' => $alias, $entity->id], $action->getAttributes()];
    }

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Controller.startup' => 'startup',
            'Controller.beforeRender' => 'beforeRender'
        ];
    }
}
