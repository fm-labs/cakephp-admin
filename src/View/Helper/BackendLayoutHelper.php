<?php

namespace Backend\View\Helper;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Inflector;
use Cake\View\Helper;

class BackendLayoutHelper extends Helper
{
    public function initialize(array $config = [])
    {
        $this->_View->loadHelper('LayoutBreadcrumb', ['className' => 'Backend.Layout/Breadcrumb']);
        $this->_View->loadHelper('LayoutHeader', ['className' => 'Backend.Layout/Header']);
        $this->_View->loadHelper('LayoutContentHeader', ['className' => 'Backend.Layout/ContentHeader']);
        $this->_View->loadHelper('LayoutFooter', ['className' => 'Backend.Layout/Footer']);
        $this->_View->loadHelper('Toolbar', ['className' => 'Backend.Layout/Toolbar']);
        $this->_View->loadHelper('Sidebar', ['className' => 'Backend.Layout/Sidebar']);
        $this->_View->loadHelper('ControlSidebar', ['className' => 'Backend.Layout/ControlSidebar']);
    }

    public function implementedEvents()
    {
        return [
            'View.beforeLayout' => ['callable' => 'beforeLayout', 'priority' => 2]
        ];
    }

    public function beforeLayout(Event $event)
    {
        // title
        $title = $event->subject()->Blocks->get('title');
        if ($title === '') {
            $event->subject()->Blocks->set('title', Inflector::humanize(Inflector::tableize($event->subject()->request['controller'])));
        }

        // AdminLTE layout options @TODO Move to AdminLTE Backend Theme
        //$themeSkinClass = (Configure::read('Backend.AdminLte.skin_class')) ?: 'skin-blue';
        //$themeLayoutClass = (Configure::read('Backend.AdminLte.layout_class')) ?: '';
        //$themeSidebarClass = (Configure::read('Backend.AdminLte.sidebar_class')) ?: 'sidebar-mini';

        //$event->subject()->set('be_adminlte_skin_class', $themeSkinClass);
        //$event->subject()->set('be_adminlte_layout_class', $themeLayoutClass);
        //$event->subject()->set('be_adminlte_sidebar_class', $themeSidebarClass);
        //$event->subject()->set('be_layout_body_class',
        //    trim(join(' ', [$themeSkinClass, $themeSidebarClass, $themeLayoutClass])));

        // css
        //$event->subject()->Html->css('/backend/libs/adminlte/bootstrap/css/bootstrap.min.css', ['block' => true]);
        $event->subject()->Html->css('/backend/libs/bootstrap/dist/css/bootstrap.min.css', ['block' => true]);
        $event->subject()->Html->css('/backend/libs/fontawesome/css/font-awesome.min.css', ['block' => true]);
        $event->subject()->Html->css('/backend/libs/ionicons/css/ionicons.min.css', ['block' => true]);
        $event->subject()->Html->css('/backend/libs/flag-icon-css/css/flag-icon.min.css', ['block' => true]);
        //$event->subject()->Html->css('/backend/libs/adminlte/dist/css/AdminLTE.min.css', ['block' => true]);
        //$event->subject()->Html->css('/backend/libs/adminlte/dist/css/skins/'.$themeSkinClass.'.min.css', ['block' => true]);

        //$event->subject()->Html->css('Backend.layout/default', ['block' => true]);

        // Backend css injected after css block, as a dirty workaround to override styles of vendor css injected from views
        $event->subject()->Html->css('Backend.backend', ['block' => true]);

        // scripts
        //$event->subject()->Html->script('/backend/libs/adminlte/bootstrap/js/bootstrap.min.js', ['block' => true]);
        //$event->subject()->Html->script('/backend/libs/adminlte/dist/js/app.js', ['block' => true]);
        $event->subject()->Html->script('/backend/libs/bootstrap/dist/js/bootstrap.min.js', ['block' => true]);
        $event->subject()->Html->script('/backend/libs/underscore/underscore-min.js', ['block' => true]);
        $event->subject()->Html->script('/backend/libs/backbone/backbone-min.js', ['block' => true]);
        $event->subject()->Html->script('/backend/js/momentjs/moment.min.js', ['block' => true]);
        $event->subject()->Html->script('/backend/js/backend.js', ['block' => true]);
        $event->subject()->Html->script('/backend/js/iconify.js', ['block' => true]);
    }
}