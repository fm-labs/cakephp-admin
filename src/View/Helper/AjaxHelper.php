<?php

namespace Backend\View\Helper;

use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;

/**
 * Class AjaxHelper
 * @package Backend\View\Helper
 *
 * @property HtmlHelper $Html
 */
class AjaxHelper extends Helper
{
    public $helpers = ['Html'];

    /**
     * @param array|string $url URL for ajax content
     * @return string
     */
    public function content($url)
    {
        return $this->Html->div('ajax-content', 'Loading ...', [
            'id' => uniqid('ajaxcont'),
            'data-url' => $this->Html->Url->build($url),
        ]);
    }
}
