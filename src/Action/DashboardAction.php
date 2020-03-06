<?php
namespace Backend\Action;

use Backend\Action\Interfaces\ActionInterface;
use Cake\Controller\Controller;
use Cake\Http\Response;

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
     * @param Controller $controller
     * @return null|Response
     */
    public function execute(Controller $controller)
    {
    }
}
