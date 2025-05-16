<?php
declare(strict_types=1);

namespace Admin\Action\Interfaces;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Table;

/**
 * Interface EntityActionInterface
 *
 * @package Admin\Action\Interfaces
 */
interface EntityActionInterface extends ActionInterface
{
    /**
     * @param mixed $id The entity id
     * @return array|string
     */
    public function getUrl(mixed $id): string|array;

    /**
     * @return bool
     */
    public function isUsable(EntityInterface $entity): bool;

    /**
     * @return \Cake\ORM\Table
     */
    public function model(): Table;

    /**
     * @return \Cake\Datasource\EntityInterface
     */
    public function entity(): EntityInterface;
}
