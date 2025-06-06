<?php
declare(strict_types=1);

namespace Admin\View\Helper;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Inflector;
use Cake\View\Helper;

/**
 * @property \Cake\View\Helper\BreadcrumbsHelper $Breadcrumbs
 */
class BreadcrumbHelper extends Helper
{
    public array $helpers = ['Breadcrumbs'];

    protected array $_defaultConfig = [
        'class' => 'breadcrumb',
    ];

    public function initialize(array $config): void
    {
        $this->Breadcrumbs->setTemplates([
            'wrapper' => '<nav aria-label="breadcrumb"><ol{{attrs}}>{{content}}</ol></nav>',
            //'item' => '<li{{attrs}}><a href="{{url}}"{{innerAttrs}}>{{title}}</a></li>{{separator}}',
            //'itemWithoutLink' => '<li{{attrs}}><span{{innerAttrs}}>{{title}}</span></li>{{separator}}',
            //'separator' => '<li{{attrs}}><span{{innerAttrs}}>{{separator}}</span></li>'
        ]);
    }

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'View.beforeLayout' => ['callable' => 'beforeLayout'],
        ];
    }

    /**
     * @param \Cake\Event\Event $event The event object
     * @return void
     */
    public function beforeLayout(Event $event): void
    {
        $request = $this->getView()->getRequest();
        //@TODO _no_breadcrumbs and layout_no_breadcrumbs are deprecated. Set `breadcrumbs` to FALSE instead
        if (
            $event->getSubject()->get('breadcrumbs') === false
            || $event->getSubject()->get('layout_no_breadcrumbs') === true
            || $event->getSubject()->get('_no_breadcrumbs') === true
            || $event->getSubject()->get('breadcrumbs_disable') === true
        ) {
            return;
        }

        if (empty($this->Breadcrumbs->getCrumbs()) && $event->getSubject()->get('breadcrumbs')) {
            foreach ((array)$event->getSubject()->get('breadcrumbs') as $breadcrumb) {
                $breadcrumb += ['title' => null, 'url' => null, 'options' => []];
                $this->Breadcrumbs->add((string)$breadcrumb['title'], $breadcrumb['url'], $breadcrumb['options']);
            }
        } elseif (empty($this->Breadcrumbs->getCrumbs())) {
            if ($request->getParam('plugin') && $request->getParam('plugin') != $request->getParam('controller')) {
                $this->Breadcrumbs->add(Inflector::humanize($request->getParam('plugin')), [
                    'plugin' => $request->getParam('plugin'),
                    'controller' => $request->getParam('plugin'),
                    'action' => 'index',
                ]);
            }

            $this->Breadcrumbs->add(Inflector::humanize($request->getParam('controller')), [
                'plugin' => $request->getParam('plugin'),
                'controller' => $request->getParam('controller'),
                'action' => 'index',
            ]);

            if (isset($request->getParam('pass')[0])) {
                $this->Breadcrumbs->add(Inflector::humanize(Inflector::singularize($request->getParam('controller'))), [
                    'plugin' => $request->getParam('plugin'),
                    'controller' => $request->getParam('controller'),
                    'action' => 'view',
                    $request->getParam('pass')[0],
                ]);
            }

            //if ($request->getParam('action') != 'index') {
                $this->Breadcrumbs->add(Inflector::humanize($request->getParam('action')));
            //}
        }

        // inject admin dashboard url on first position
        $dashboardTitle = Configure::read('Admin.Dashboard.title');
        $dashboardUrl = Configure::read('Admin.Dashboard.url', '/admin');

        $rootCrumbTitle = $this->_View->get('admin_breadcrumb_root_title', __d('admin', 'Administration'));
        $rootCrumbUrl = $this->_View->get('admin_breadcrumb_root_url', $dashboardUrl);
        $this->Breadcrumbs->prepend($rootCrumbTitle, $rootCrumbUrl);

        // add custom class to each item
        $crumbs = $this->Breadcrumbs->getCrumbs();
        $crumbs = collection($crumbs)->map(function ($crumb) {
            $crumb['options']['class'] = 'breadcrumb-item';

            return $crumb;
        })->toArray();
        $this->Breadcrumbs->reset()->add($crumbs);

        $breadcrumbsHtml = $this->Breadcrumbs->render($this->getConfig());
        $event->getSubject()->assign('breadcrumbs', $breadcrumbsHtml);
    }
}
