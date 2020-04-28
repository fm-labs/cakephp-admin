<?php
declare(strict_types=1);

namespace Admin\Action\Traits;

use Cake\Datasource\EntityInterface;

trait EntityActionFilterTrait
{
    protected $_filter;

    /**
     * @return bool
     */
    public function isUsable(EntityInterface $entity)
    {
        if (is_callable($this->_filter)) {
            return call_user_func($this->_filter, $entity);
        }

        return true;
    }

    public function setFilter($filter)
    {
        if (!is_callable($filter)) {
            throw new \InvalidArgumentException("The filter MUST be a valid callable");
        }
        $this->_filter = $filter;

        return $this;
    }

    public function addFilter($filter)
    {
        return $this->setFilter($filter);
    }
}
