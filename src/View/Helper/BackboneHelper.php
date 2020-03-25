<?php

namespace Backend\View\Helper;

use Cake\Event\Event;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;
use Cake\View\View;

/**
 * Class ChosenHelper
 * @package Backend\View\Helper
 *
 * @property HtmlHelper $Html
 */
class BackboneHelper extends Helper
{
    public $helpers = ['Html', 'Form'];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        $this->_View->Html->script('/backend/libs/underscore/underscore-min.js', ['block' => true]);
        $this->_View->Html->script('/backend/libs/backbone/backbone-min.js', ['block' => true]);
    }
}
