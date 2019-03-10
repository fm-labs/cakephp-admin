<?php

namespace Backend\View\Helper;

use Cake\Event\Event;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;

/**
 * Class HtmlEditorHelper
 *
 * @package Backend\View\Helper
 * @property HtmlHelper $Html
 */
class HtmlEditorHelper extends Helper
{
    public $helpers = ['Html'];

    /**
     * @param Event $event The event object
     * @return void
     */
    public function beforeLayout(Event $event)
    {
        $this->Html->script('/backend/libs/tinymce/tinymce.min', ['block' => 'script']);
        $this->Html->script('/backend/libs/tinymce/jquery.tinymce.min', ['block' => 'script']);
        $this->Html->script('/backend/js/backend.htmleditor.js', ['block' => 'script']);
    }
}
