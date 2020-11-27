<?php
declare(strict_types=1);

namespace Admin\Action;

use Admin\Action\Interfaces\ActionInterface;
use Admin\Action\Interfaces\EntityActionInterface;
use Admin\Action\Interfaces\IndexActionInterface;
use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;

class DebugAction extends BaseEntityAction implements ActionInterface, IndexActionInterface, EntityActionInterface
{
    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return __d('admin', 'Debug');
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return ['data-icon' => 'wrench'];
    }

    /**
     * @inheritDoc
     */
    public function getScope(): array
    {
        return ['table', 'form'];
    }

    /**
     * @inheritDoc
     */
    public function isUsable(EntityInterface $entity)
    {
        return true;
    }

    /**
     * @param \Cake\Controller\Controller $controller
     * @return \Cake\Http\Response|void|null
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
                'scope' => $a instanceof BaseEntityAction ? $a->getScope() : [],
            ];
        });
        $controller->set('loaded_actions', $actionsLoaded);

        // Model
        if ($controller->modelClass) {
            $Model = $controller->loadModel($controller->modelClass);
            $schema = $Model->getSchema();
            $controller->set('model_class', $controller->modelClass);
            $controller->set('model_schema', $schema);
        }
    }
}
