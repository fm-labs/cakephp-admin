<?php
declare(strict_types=1);

namespace Backend\View\Helper;

use Cake\View\Helper;

/**
 * Class ChosenHelper
 * @package Backend\View\Helper
 *
 * @property \Cake\View\Helper\HtmlHelper $Html
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
