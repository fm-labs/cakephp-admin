<?php

namespace Backend\View\Helper\Layout;

use Cake\Event\Event;
use Cake\Utility\Inflector;
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

        if (empty($this->Breadcrumbs->getCrumbs())) {

            if ($this->request->param('plugin') && $this->request->param('plugin') != $this->request->param('controller'))  {
                $this->Breadcrumbs->add(Inflector::humanize($this->request->param('plugin')), [
                    'plugin' => $this->request->param('plugin'),
                    'controller' => $this->request->param('plugin'),
                    'action' => 'index'
                ]);
            }

            $this->Breadcrumbs->add(Inflector::humanize($this->request->param('controller')), [
                'plugin' => $this->request->param('plugin'),
                'controller' => $this->request->param('controller'),
                'action' => 'index'
            ]);

            //if ($this->request->param('action') != 'index') {
                $this->Breadcrumbs->add(Inflector::humanize($this->request->param('action')));
            //}
        }

        // inject backend dashboard url on first position
        $this->Breadcrumbs->prepend($this->_View->get('be_title'), $this->_View->get('be_dashboard_url'));

        $breadcrumbsHtml = $this->Breadcrumbs->render($this->config());
        $event->subject()->Blocks->set('breadcrumbs', $breadcrumbsHtml);
    }
}