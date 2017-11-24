<?php

namespace Backend\Action;
use Cake\Controller\Controller;
use Cake\ORM\Association;
use Cake\Utility\Inflector;

/**
 * Class AddAction
 *
 * @package Backend\Action
 */
class AddAction extends BaseIndexAction
{

    /**
     * @var array
     */
    protected $_defaultConfig = [
        'actions' => [],
        'rowActions' => [],
        'fields' => [],
        'fields.whitelist' => []
    ];


    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __('Add');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'plus'];
    }

    /**
     *
     */
    public function _execute(Controller $controller)
    {
        if (isset($controller->viewVars['_entity']) && isset($controller->viewVars[$controller->viewVars['_entity']])) {
            $entity = $controller->viewVars[$controller->viewVars['_entity']];
        } else {
            $entity = $this->model()->newEntity();
        }

        if ($this->_request->is(['put', 'post'])) {
            $entity = $this->model()->patchEntity($entity, $this->_request->data);
            if ($this->model()->save($entity)) {
                $this->_flashSuccess(__('Record created'));
                $this->_redirect(['action' => 'index']);
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

        // config
        $controller->set($this->_config);
    }

}
