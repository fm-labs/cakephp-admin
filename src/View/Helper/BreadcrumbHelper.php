<?php

namespace Backend\View\Helper;

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

    /**
     * {@inheritDoc}
     */
    public function implementedEvents()
    {
        return [
            'View.beforeLayout' => ['callable' => 'beforeLayout']
        ];
    }

    /**
     * @param Event $event The event object
     * @return void
     */
    public function beforeLayout(Event $event)
    {
        //@TODO _no_breadcrumbs and layout_no_breadcrumbs are deprecated. Set `breadcrumbs` to FALSE instead
        if ($event->getSubject()->get('breadcrumbs') === false
            || $event->getSubject()->get('_no_breadcrumbs') === true
            || $event->getSubject()->get('layout_no_breadcrumbs') === true) {
            return;
        }

        if (empty($this->Breadcrumbs->getCrumbs()) && $event->getSubject()->get('breadcrumbs')) {
            foreach ((array)$event->getSubject()->get('breadcrumbs') as $breadcrumb) {
                $breadcrumb += ['title' => null, 'url' => null, 'options' => []];
                $this->Breadcrumbs->add($breadcrumb['title'], $breadcrumb['url'], $breadcrumb['options']);
            }
        } elseif (empty($this->Breadcrumbs->getCrumbs())) {
            if ($this->request->param('plugin') && $this->request->param('plugin') != $this->request->param('controller')) {
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

            //
            if (isset($this->request->param('pass')[0])) {
                $this->Breadcrumbs->add(Inflector::humanize(Inflector::singularize($this->request->param('controller'))), [
                    'plugin' => $this->request->param('plugin'),
                    'controller' => $this->request->param('controller'),
                    'action' => 'view',
                    $this->request->param('pass')[0]
                ]);
            }

            //if ($this->request->param('action') != 'index') {
                $this->Breadcrumbs->add(Inflector::humanize($this->request->param('action')));
            //}
        }

        // inject backend dashboard url on first position
        $this->Breadcrumbs->prepend($this->_View->get('be_title'), $this->_View->get('be_dashboard_url'));

        $breadcrumbsHtml = $this->Breadcrumbs->render($this->getConfig());
        $event->getSubject()->assign('breadcrumbs', $breadcrumbsHtml);
    }
}
