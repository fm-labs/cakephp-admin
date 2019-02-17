<?php

namespace Backend\Action;

use Backend\Action\Interfaces\ActionInterface;
use Backend\Action\Interfaces\EntityActionInterface;
use Backend\Action\Interfaces\IndexActionInterface;
use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;
use Cake\Network\Response;
use Cake\ORM\Table;

class DebugAction extends BaseEntityAction implements ActionInterface, IndexActionInterface, EntityActionInterface
{
    public $template;

    /**
     * @return string
     */
    public function getAlias()
    {
        return 'debug';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return __d('backend', 'Debug');
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return ['data-icon' => 'wrench'];
    }

    /**
     * @todo non-interface entity action method
     */
    public function getScope()
    {
        return ['table', 'form'];
    }

    /**
     * @todo non-interface entity action method
     */
    public function hasScope($scope)
    {
        return true;
    }

    /**
     * @todo non-interface entity action method
     */
    public function isUsable(EntityInterface $entity)
    {
        return true;
    }

    /**
     * @param Controller $controller
     * @return Response|null
     */
    protected function _execute(Controller $controller)
    {
        $controller->set('controller_actions', $controller->actions);

        // Actions
        $actionsLoaded = [];
        $actions = $controller->Action->listActions();
        array_walk($actions, function ($action) use (&$actionsLoaded, &$controller) {
            $a = $controller->Action->getAction($action);
            $actionsLoaded[$action] = [
                'class' => get_class($a),
                'action_iface' => ($a instanceof ActionInterface),
                'index_iface' => ($a instanceof IndexActionInterface),
                'entity_iface' => ($a instanceof EntityActionInterface),
                'scope' => ($a instanceof BaseEntityAction) ? $a->getScope() : [],
            ];
        });
        $controller->set('loaded_actions', $actionsLoaded);

        // Model
        if ($controller->modelClass) {
            $Model = $controller->loadModel($controller->modelClass);
            $schema = $Model->schema();
            $controller->set('model_class', $controller->modelClass);
            $controller->set('model_schema', $schema);
        }
    }
}
