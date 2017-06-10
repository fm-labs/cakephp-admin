<?php

namespace Backend\Action\Interfaces;

use Cake\Controller\Controller;

interface ActionInterface
{
    public function execute(Controller $controller);
}
