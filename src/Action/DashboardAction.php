<?php
namespace Backend\Action;

use Backend\Action\Interfaces\ActionInterface;
use Cake\Controller\Controller;
use Cake\Network\Response;

class DashboardAction implements ActionInterface
{

    /**
     * @return string
     */
    public function getAlias()
    {
        return 'dashboard';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return __('Dashboard');
    }

    /**
     * @return mixed
     */
    public function getAttributes()
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