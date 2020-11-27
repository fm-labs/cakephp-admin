<?php
declare(strict_types=1);

namespace Admin\Action\Interfaces;

use Cake\Controller\Controller;
use Cake\Http\Response;

/**
 * Interface ActionInterface
 *
 * @package Admin\Action\Interfaces
 */
interface ActionInterface
{
    public const SCOPE_INDEX = 'index';
    public const SCOPE_ENTITY = 'entity';

    /**
     * @return array List of scope strings
     */
    public function getScope(): array;

    /**
     * @return string The action link label
     */
    public function getLabel(): string;

    /**
     * @return mixed The action link attributes
     */
    public function getAttributes(): array;

    /**
     * @param \Cake\Controller\Controller $controller Active controller
     * @return null|\Cake\Http\Response
     */
    public function execute(Controller $controller);
}
