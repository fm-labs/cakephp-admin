<?php

namespace Backend\Action\Interfaces;

use Cake\Controller\Controller;
use Cake\Network\Response;

/**
 * Interface ActionInterface
 * @package Backend\Action\Interfaces
 */
interface ActionInterface
{
    /**
     * @return string
     */
    public function getAlias();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return mixed
     */
    public function getAttributes();

    /**
     * @param Controller $controller
     * @return null|Response
     */
    public function execute(Controller $controller);
}
