<?php

namespace Backend\Action;

use Backend\Action\Interfaces\TableActionInterface;
use Cake\Controller\Controller;

class SearchAction implements TableActionInterface
{
    public function execute(Controller $controller)
    {
        $controller->set('foo', 'bar');
        $controller->render('index');
    }
}
