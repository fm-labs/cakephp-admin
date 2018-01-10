<?php

namespace Backend\Service;

use Backend\BackendService;
use Backend\Event\LocalEventManager;
use Backend\View\BackendView;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Utility\Inflector;

class LayoutService extends BackendService
{
    /**
     * Event manager for sub services
     * @var EventManager
     */
    protected $_localEventManager;

    /**
     * Layout sub services
     * @var ServiceRegistry
     */
    protected $_services;

    public function initialize()
    {
        $this->_localEventManager = new LocalEventManager();
        $this->_services = new ServiceRegistry($this->_localEventManager);
        $this->_loadServices();
    }

    public function getSubservices() {
        return [
            'Backend.Layout/LayoutHeaderNavbar',
            'Backend.Layout/LayoutSidebar',
            'Backend.Layout/LayoutToolbar',
        ];
    }

    protected function _loadServices()
    {
        foreach ($this->getSubservices() as $service => $enabled) {
            list($service, $enabled) = (is_numeric($service)) ? [$enabled, true] : [$service, $enabled];
            if ($enabled) {
                $service = $this->_services->load($service);
                $service->initialize();
            }
        }
    }

    public function implementedEvents()
    {
        return ['View.beforeLayout' => ['callable' => 'beforeLayout', 'priority' => 10]];
    }

    public function beforeLayout(Event $event)
    {
        // delete event to sub services first
        $this->_localEventManager->dispatch($event);

        if ($event->subject() instanceof BackendView) {

            // title
            $title = $event->subject()->Blocks->get('title');
            if ($title === '') {
                $event->subject()->Blocks->set('title', Inflector::humanize(Inflector::tableize($event->subject()->request['controller'])));
            }

            // AdminLTE layout options @TODO Move to AdminLTE Backend Theme
            $themeSkinClass = (Configure::read('Backend.AdminLte.skin_class')) ?: 'skin-blue';
            $themeLayoutClass = (Configure::read('Backend.AdminLte.layout_class')) ?: '';
            $themeSidebarClass = (Configure::read('Backend.AdminLte.sidebar_class')) ?: 'sidebar-mini';

            $event->subject()->set('be_adminlte_skin_class', $themeSkinClass);
            $event->subject()->set('be_adminlte_layout_class', $themeLayoutClass);
            $event->subject()->set('be_adminlte_sidebar_class', $themeSidebarClass);
            $event->subject()->set('be_layout_body_class',
                trim(join(' ', [$themeSkinClass, $themeSidebarClass, $themeLayoutClass])));

            // css
            $event->subject()->Html->css('/backend/css/adminlte/skins/'.$themeSkinClass.'.min.css', ['block' => true]);

            $event->subject()->Html->css('/backend/css/adminlte/bootstrap/css/bootstrap.min.css', ['block' => true]);
            $event->subject()->Html->css('/backend/libs/fontawesome/css/font-awesome.min.css', ['block' => true]);
            $event->subject()->Html->css('/backend/libs/ionicons/css/ionicons.min.css', ['block' => true]);
            $event->subject()->Html->css('/backend/libs/flag-icon-css/css/flag-icon.min.css', ['block' => true]);
            $event->subject()->Html->css('/backend/css/adminlte/AdminLTE.min.css', ['block' => true]);
            //$event->subject()->Html->css('/backend/css/adminlte/skins/skin-blue.min.css', ['block' => true]);
            $event->subject()->Html->css('Backend.layout/default', ['block' => true]);

            // Backend css injected after css block, as a dirty workaround to override styles of vendor css injected from views
            $event->subject()->Html->css('Backend.backend', ['block' => true]);


            // scripts
            $event->subject()->Html->script('/backend/js/adminlte/bootstrap/bootstrap.min.js', ['block' => true]);
            $event->subject()->Html->script('/backend/js/adminlte/app.js', ['block' => true]);
            $event->subject()->Html->script('/backend/libs/underscore/underscore-min.js', ['block' => true]);
            $event->subject()->Html->script('/backend/js/momentjs/moment.min.js', ['block' => true]);
            $event->subject()->Html->script('/backend/js/backend.js', ['block' => true]);
            $event->subject()->Html->script('/backend/js/iconify.js', ['block' => true]);

            // header
            $event->subject()->Blocks->set('header', $event->subject()->element('Backend.Layout/admin/header'));
            // toolbar
            //$event->subject()->Blocks->set('toolbar', $event->subject()->element('Backend.Layout/admin/toolbar'));
            // sidebar
            //$event->subject()->Blocks->set('sidebar', $event->subject()->element('Backend.Layout/admin/sidebar'));
            // content_header
            $event->subject()->Blocks->set('content_header', $event->subject()->element('Backend.Layout/admin/content_header'));
            // footer
            $event->subject()->Blocks->set('footer', $event->subject()->element('Backend.Layout/admin/footer'));
            // control_sidebar
            $event->subject()->Blocks->set('control_sidebar', $event->subject()->element('Backend.Layout/admin/control_sidebar'));

        }


    }
}