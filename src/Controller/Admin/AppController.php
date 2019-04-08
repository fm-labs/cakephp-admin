<?php

namespace Backend\Controller\Admin;
use Cake\ORM\Exception\MissingBehaviorException;

/**
 * Class AppController
 *
 * @package Backend\Controller\Admin
 */
class AppController extends \App\Controller\Admin\AppController
{
    /**
     * {@inheritDoc}
     */
    public function initialize()
    {
        parent::initialize();

        if (!$this->components()->has('Backend')) {
            throw new MissingBehaviorException(['behavior' => 'Backend']);
        }
    }
}
