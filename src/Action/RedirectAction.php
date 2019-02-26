<?php

namespace Backend\Action;

use Cake\Controller\Controller;

class RedirectAction extends BaseEntityAction
{

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __d('backend', 'Redirect');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'trash', 'class' => 'action-danger'];
    }

    /**
     * {@inheritDoc}
     */
    protected function _execute(Controller $controller)
    {
        debug(get_class($controller));
        $redirect = $controller->redirect($controller->referer(['action' => 'index']));
        debug(get_class($redirect));
        return $redirect;
    }
}
