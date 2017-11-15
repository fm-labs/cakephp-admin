<?php

namespace Backend\Service;

use Backend\BackendService;
use Backend\View\BackendView;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Inflector;

class LayoutService extends BackendService
{
    public function implementedEvents()
    {
        return ['View.beforeLayout' => ['callable' => 'beforeLayout']];
    }

    public function beforeLayout(Event $event)
    {
        if ($event->subject() instanceof BackendView) {

            // title
            $title = $event->subject()->Blocks->get('title');
            if ($title === '') {
                $event->subject()->Blocks->set('title', Inflector::humanize(Inflector::tableize($event->subject()->request['controller'])));
            }

            // AdminLTE layout options
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
            $event->subject()->Blocks->set('sidebar', $event->subject()->element('Backend.Layout/admin/sidebar'));
            // content_header
            $event->subject()->Blocks->set('content_header', $event->subject()->element('Backend.Layout/admin/content_header'));
            // footer
            $event->subject()->Blocks->set('footer', $event->subject()->element('Backend.Layout/admin/footer'));
            // control_sidebar
            $event->subject()->Blocks->set('control_sidebar', $event->subject()->element('Backend.Layout/admin/control_sidebar'));


        }

    }
}