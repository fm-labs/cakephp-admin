<?php

namespace Backend\Action\Interfaces;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Table;

/**
 * Interface EntityActionInterface
 *
 * @package Backend\Action\Interfaces
 */
interface EntityActionInterface extends ActionInterface
{
    /**
     * @return bool
     */
    public function isUsable(EntityInterface $entity);

    /**
     * @return Table
     */
    public function model();

    /**
     * @return EntityInterface
     */
    public function entity();

    /**
     * @return array
     */
    public function getScope();
}
