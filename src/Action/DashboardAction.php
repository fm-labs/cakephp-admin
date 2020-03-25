<?php
declare(strict_types=1);

namespace Backend\Action;

use Backend\Action\Interfaces\ActionInterface;
use Cake\Controller\Controller;

class DashboardAction implements ActionInterface
{
    /**
     * @return string
     */
    public function getLabel()
    {
        return __d('backend', 'Dashboard');
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getScope()
    {
        return [];
    }

    /**
     * @param \Cake\Controller\Controller $controller
     * @return null|\Cake\Http\Response
     */
    public function execute(Controller $controller)
    {
    }
}
