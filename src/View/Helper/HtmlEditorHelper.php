<?php
declare(strict_types=1);

namespace Admin\View\Helper;

use Cake\Event\Event;
use Cake\View\Helper;

/**
 * Class HtmlEditorHelper
 *
 * @package Admin\View\Helper
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class HtmlEditorHelper extends Helper
{
    public $helpers = ['Html'];

    /**
     * @param \Cake\Event\Event $event The event object
     * @return void
     */
    public function beforeLayout(Event $event)
    {
        $this->Html->script('/admin/libs/tinymce/tinymce.min', ['block' => 'script']);
        $this->Html->script('/admin/libs/tinymce/jquery.tinymce.min', ['block' => 'script']);
        $this->Html->script('/admin/js/admin.htmleditor.js', ['block' => 'script']);
    }
}
