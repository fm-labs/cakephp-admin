<?php

namespace Backend\View;

use Banana\View\ViewModuleTrait;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\View\View;

/**
 * Class BackendView
 *
 * @package Backend\View
 */
class BackendView extends View
{
    use ViewModuleTrait;

    /**
     * {@inheritDoc}
     */
    public function initialize()
    {
        $this->eventManager()->dispatch(new Event('Backend.View.initialize', $this));
    }
}
