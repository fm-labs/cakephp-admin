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
        $this->_View->loadHelper('Toolbar', ['className' => 'Backend.Layout/Toolbar']);
        $this->_View->loadHelper('Sidebar', ['className' => 'Backend.Layout/Sidebar']);
    }

    public function implementedEvents()
    {
        return [
            'View.beforeRender' => ['callable' => 'beforeRender', 'priority' => 2],
            'View.beforeLayout' => ['callable' => 'beforeLayout', 'priority' => 2]
        ];
    }

    public function beforeRender(Event $event)
    {
    }

    public function beforeLayout(Event $event)
    {
        // title
        $title = $event->subject()->Blocks->get('title'); // check the title block
        $title = ($title) ?: $event->subject()->get('page.title'); // check the 'page.title' view var
        $title = ($title) ?: Inflector::humanize(Inflector::tableize($event->subject()->request['controller'])); // inflected controller name
        $event->subject()->Blocks->set('title', $title);

        // AdminLTE layout options @TODO Move to AdminLTE Backend Theme
        //$themeSkinClass = (Configure::read('Backend.AdminLte.skin_class')) ?: 'skin-blue';
        //$themeLayoutClass = (Configure::read('Backend.AdminLte.layout_class')) ?: '';
        //$themeSidebarClass = (Configure::read('Backend.AdminLte.sidebar_class')) ?: 'sidebar-mini';
        //$event->subject()->set('be_adminlte_skin_class', $themeSkinClass);
        //$event->subject()->set('be_adminlte_layout_class', $themeLayoutClass);
        //$event->subject()->set('be_adminlte_sidebar_class', $themeSidebarClass);
        //$event->subject()->set('be_layout_body_class',
        //    trim(join(' ', [$themeSkinClass, $themeSidebarClass, $themeLayoutClass])));
        //$event->subject()->Html->css('/backend/libs/adminlte/bootstrap/css/bootstrap.min.css', ['block' => true]);
        //$event->subject()->Html->css('/backend/libs/adminlte/dist/css/AdminLTE.min.css', ['block' => true]);
        //$event->subject()->Html->css('/backend/libs/adminlte/dist/css/skins/'.$themeSkinClass.'.min.css', ['block' => true]);
        //$event->subject()->Html->script('/backend/libs/adminlte/bootstrap/js/bootstrap.min.js', ['block' => true]);
        //$event->subject()->Html->script('/backend/libs/adminlte/dist/js/app.js', ['block' => true]);

        // css
        $event->subject()->Html->css('/backend/libs/bootstrap/dist/css/bootstrap.min.css', ['block' => true]);
        $event->subject()->Html->css('/backend/libs/fontawesome/css/font-awesome.min.css', ['block' => true]);
        $event->subject()->Html->css('/backend/libs/ionicons/css/ionicons.min.css', ['block' => true]);
        $event->subject()->Html->css('/backend/libs/flag-icon-css/css/flag-icon.min.css', ['block' => true]);

        // Backend css injected after css block, as a dirty workaround to override styles of vendor css injected from views
        $event->subject()->Html->css('Backend.backend', ['block' => true]);

        // scripts
        $event->subject()->Html->script('/backend/libs/bootstrap/dist/js/bootstrap.min.js', ['block' => true]);
        $event->subject()->Html->script('/backend/libs/underscore/underscore-min.js', ['block' => true]);
        $event->subject()->Html->script('/backend/libs/backbone/backbone-min.js', ['block' => true]);
        $event->subject()->Html->script('/backend/js/momentjs/moment.min.js', ['block' => true]);
        $event->subject()->Html->script('/backend/js/backend.js', ['block' => true]);
        $event->subject()->Html->script('/backend/js/iconify.js', ['block' => true]);

        //@TODO Move layout blocks to config
        //@TODO Fallback to default block element
        //@TODO Add support for blocks with sub-elements
        $blocks = [
            'top' => [
                [
                    'element' => 'Backend.Layout/admin/content_header'
                ]
            ],
            'footer' => [
                [
                    'element' => 'Backend.Layout/admin/footer'
                ]
            ],
            'control_sidebar' => [
                [
                    'element' => 'Backend.Layout/admin/control_sidebar'
                ]
            ]
        ];
        foreach ($blocks as $block => $contents) {
            foreach ($contents as $content) {
                $event->subject()->Blocks->set($block, $event->subject()->element($content['element']));
            }
        }
    }
}
