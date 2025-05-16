<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Http\Response;

class RedirectAction extends BaseEntityAction
{
    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return __d('admin', 'Redirect');
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return ['data-icon' => 'trash', 'class' => 'action-danger'];
    }

    /**
     * @inheritDoc
     */
    protected function _execute(Controller $controller): ?Response
    {
        debug(get_class($controller));
        $redirect = $controller->redirect($controller->referer(['action' => 'index']));
        debug(get_class($redirect));

        return $redirect;
    }
}
