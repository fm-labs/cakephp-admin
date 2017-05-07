<?php

namespace Backend\Action;


use Cake\Controller\Controller;

class SearchAction implements ActionInterface
{
    public function execute(Controller $controller)
    {
        $controller->set('foo', 'bar');
        $controller->render('index');
    }
}