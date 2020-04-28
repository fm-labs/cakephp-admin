<?php

namespace AdminLteTheme\View\Helper;

use Cake\Core\Configure;
use Cake\Event\Event;

class AdminLteLayoutHelper extends Helper
{

    public function beforeLayout(Event $event)
    {
        // AdminLTE layout options @TODO Move to AdminLTE Admin Theme
        $themeSkinClass = (Configure::read('AdminLteTheme.skin_class')) ?: 'skin-blue';
        $themeLayoutClass = (Configure::read('AdminLteTheme.layout_class')) ?: '';
        $themeSidebarClass = (Configure::read('AdminLteTheme.sidebar_class')) ?: 'sidebar-mini';
        $event->getSubject()->set('be_adminlte_skin_class', $themeSkinClass);
        $event->getSubject()->set('be_adminlte_layout_class', $themeLayoutClass);
        $event->getSubject()->set('be_adminlte_sidebar_class', $themeSidebarClass);
        $event->getSubject()->set(
            'be_layout_body_class',
            trim(join(' ', [$themeSkinClass, $themeSidebarClass, $themeLayoutClass]))
        );
        $event->getSubject()->Html->css('/admin_lte_theme/lib/bootstrap/css/bootstrap.min.css', ['block' => true]);
        $event->getSubject()->Html->css('/admin_lte_theme/lib/dist/css/AdminLTE.min.css', ['block' => true]);
        $event->getSubject()->Html->css('/admin_lte_theme/lib/dist/css/skins/' . $themeSkinClass . '.min.css', ['block' => true]);
        $event->getSubject()->Html->script('/admin_lte_theme/lib/bootstrap/js/bootstrap.min.js', ['block' => true]);
        $event->getSubject()->Html->script('/admin_lte_theme/lib/dist/js/app.js', ['block' => true]);
    }
}
