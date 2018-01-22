<?php

namespace Backend\View\Helper\Layout;

use Cake\Event\Event;
use Cake\View\Helper;
use Cake\View\Helper\BreadcrumbsHelper;

/**
 * @property BreadcrumbsHelper $Breadcrumbs
 */
class BreadcrumbHelper extends Helper
{
    public $helpers = ['Breadcrumbs'];

    protected $_defaultConfig = [
        'class' => 'breadcrumb'
    ];

    public function implementedEvents()
    {
        return [
            'View.beforeLayout' => ['callable' => 'beforeLayout']
        ];
    }

    public function beforeLayout(Event $event)
    {
        //@TODO _no_breadcrumbs is deprecated. Use layout_no_breadcrumbs instead
        if ($event->subject()->get('layout_no_breadcrumbs') === true || $event->subject()->get('_no_breadcrumbs') === true) {
            return;
        }

        $breadcrumbsHtml = $this->Breadcrumbs->render($this->config());
        $event->subject()->Blocks->set('breadcrumbs', $breadcrumbsHtml);
    }
}