<?php
declare(strict_types=1);

namespace Backend\Action\Interfaces;

use Cake\Datasource\EntityInterface;

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
     * @return \Cake\ORM\Table
     */
    public function model();

    /**
     * @return \Cake\Datasource\EntityInterface
     */
    public function entity();
}
