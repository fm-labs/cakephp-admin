<?php

namespace Backend\Action;
use Backend\Action\Interfaces\EntityActionInterface;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\Association;
use Cake\Utility\Inflector;

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
        if (isset($controller->viewVars['_entity']) && isset($controller->viewVars[$controller->viewVars['_entity']])) {
            $entity = $controller->viewVars[$controller->viewVars['_entity']];
        } else {
            $entity = $this->entity();
        }

        debug($this->_request->data);
        if ($this->_request->is(['put', 'post'])) {
            $entity = $this->model()->patchEntity($entity, $this->_request->data);
            if ($this->model()->save($entity)) {
                debug($entity->toArray());
                $this->_flashSuccess(__('Records updated'));
                //$this->_redirect(['action' => 'index']);
            } else {
                $this->_flashError();
            }
        }

        $controller->set('entity', $entity);
        $controller->set('modelClass', $controller->modelClass);

        // associated
        foreach ($this->model()->associations() as $assoc) {
            if ($assoc->type() == Association::MANY_TO_ONE) {
                $var = Inflector::pluralize($assoc->property());
                $list = $assoc->target()->find('list')->toArray();
                $controller->set($var, $list);
            }
        }

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
