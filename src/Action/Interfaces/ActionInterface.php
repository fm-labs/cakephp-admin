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
     * @return array List of scope strings
     */
    public function getScope();

    /**
     * @return string
     */
    public function getAlias();

    /**
     * @return string The action link label
     */
    public function getLabel();

    /**
     * @return mixed The action link attributes
     */
    public function getAttributes();

    /**
     * @param Controller $controller
     * @return null|Response
     */
    public function execute(Controller $controller);
}
