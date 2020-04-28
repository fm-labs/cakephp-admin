<?php
declare(strict_types=1);

namespace Admin\Action\Interfaces;

use Cake\Controller\Controller;

/**
 * Interface ActionInterface
 * @package Admin\Action\Interfaces
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
    //public function getAlias();

    /**
     * @return string The action link label
     */
    public function getLabel();

    /**
     * @return mixed The action link attributes
     */
    public function getAttributes();

    /**
     * @param \Cake\Controller\Controller $controller
     * @return null|\Cake\Http\Response
     */
    public function execute(Controller $controller);
}
