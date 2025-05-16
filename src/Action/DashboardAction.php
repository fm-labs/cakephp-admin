<?php
declare(strict_types=1);

namespace Admin\Action;

use Admin\Action\Interfaces\ActionInterface;
use Cake\Controller\Controller;

class DashboardAction extends BaseAction
{
    //public ?string $template = "Admin.Action/dashboard";

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return __d('admin', 'Dashboard');
    }

    /**
     * @param \Cake\Controller\Controller $controller
     * @return void
     */
    public function execute(Controller $controller): ?\Cake\Http\Response
    {
        $controller->viewBuilder()->addHelper('Bootstrap.Button');
        $controller->viewBuilder()->addHelper('Sugar.Box');

        return null;
    }
}
