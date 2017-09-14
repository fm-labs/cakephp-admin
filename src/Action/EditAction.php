<?php

namespace Backend\Action;
use Backend\Action\Interfaces\EntityActionInterface;
use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Class EditAction
 *
 * @package Backend\Action
 */
class EditAction extends BaseEntityAction
{
    protected $_scope = ['table', 'form'];

    //public $noTemplate = true;

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __('Edit');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'pencil'];
    }

    protected function _execute(Controller $controller)
    {
        //return $controller->render();
        if (isset($controller->viewVars['_entity']) && isset($controller->viewVars[$controller->viewVars['_entity']])) {
            $controller->set('entity', $controller->viewVars[$controller->viewVars['_entity']]);
        }

        $controller->set('modelClass', $controller->modelClass);

        /*
        foreach ($controller->Action->listActions() as $actionName) {
            $action = $controller->Action->getAction($actionName);
            if ($action instanceof EntityActionInterface && $action->hasForm()) {
                $tabs = (isset($controller->viewVars['tabs'])) ? $controller->viewVars['tabs'] : [];
                $tabs[$actionName] = ['title' => $action->getLabel(), 'url' => ['action' => $actionName, $controller->viewVars['entity']->id]];
                $controller->set('tabs', $tabs);
            }
        }
        */
    }
}
