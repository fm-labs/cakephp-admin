<?php

namespace Backend\Action;
use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Class MediaAction
 *
 * @package Backend\Action
 */
class MediaAction extends BaseEntityAction
{
    protected $_scope = [];

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __('Media');
    }

    public function hasForm()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'media'];
    }

    protected function _execute(Controller $controller)
    {

        $fields = [];

        if ($controller->modelClass) {
            $Model = $controller->loadModel($controller->modelClass);
            if ($Model->hasBehavior('Media')) {
                $fields = $Model->getMediaFields();
            }
        }

        //debug($fields);

        $controller->set('fields', $fields);
        $controller->set('modelClass', $controller->modelClass);
        $controller->set('entity', $this->entity());
    }
}
