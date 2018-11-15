<?php

namespace Backend\View\Helper;

use Cake\Event\Event;
use Cake\View\Helper;
use Cake\View\Helper\FormHelper;
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

    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);

        $this->_View->Html->script('/backend/libs/underscore/underscore-min.js', ['block' => true]);
        $this->_View->Html->script('/backend/libs/backbone/backbone-min.js', ['block' => true]);
    }

    public function beforeLayout(Event $event)
    {
    }
}
