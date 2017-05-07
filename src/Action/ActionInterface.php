<?php

namespace Backend\Action;


use Cake\Controller\Controller;

interface ActionInterface
{
    public function execute(Controller $controller);
}