<?php

namespace Backend\Mod;

use Backend\View\BackendView;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Utility\Inflector;

class ModUi implements EventListenerInterface
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

            $event->subject()->Html->css('/backend/css/adminlte/skins/'.$themeSkinClass.'.min.css', ['block' => true]);
        }

    }
}