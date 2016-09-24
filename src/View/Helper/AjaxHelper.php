<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 9/16/16
 * Time: 9:36 PM
 */

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

    public function content($url)
    {
        return $this->Html->div('ajax-content', 'Loading ...', [
            'id' => uniqid('ajaxcont'),
            'data-url' => $this->Html->Url->build($url)
        ]);
    }
}