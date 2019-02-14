<?php

namespace Backend\View\Helper;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Inflector;
use Cake\View\Helper;

class BackendLayoutHelper extends Helper
{
    /**
     * {@inheritDoc}
     */
    public function initialize(array $config = [])
    {
        $this->_View->loadHelper('Backbone', ['className' => 'Backend.Backbone']);
        $this->_View->loadHelper('LayoutBreadcrumb', ['className' => 'Backend.Layout/Breadcrumb']);
        $this->_View->loadHelper('LayoutHeader', ['className' => 'Backend.Layout/Header']);
        $this->_View->loadHelper('Toolbar', ['className' => 'Backend.Layout/Toolbar']);
        $this->_View->loadHelper('Sidebar', ['className' => 'Backend.Layout/Sidebar']);
    }

    /**
     * {@inheritDoc}
     */
    public function implementedEvents()
    {
        return [
            'View.beforeRender' => ['callable' => 'beforeRender', 'priority' => 2],
            'View.beforeLayout' => ['callable' => 'beforeLayout', 'priority' => 2]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function beforeRender(Event $event)
    {
        // css
        $event->subject()->Html->css('/backend/libs/bootstrap/dist/css/bootstrap.min.css', ['block' => true]);
        $event->subject()->Html->css('/backend/libs/fontawesome/css/font-awesome.min.css', ['block' => true]);
        $event->subject()->Html->css('/backend/libs/ionicons/css/ionicons.min.css', ['block' => true]);
        $event->subject()->Html->css('/backend/libs/flag-icon-css/css/flag-icon.min.css', ['block' => true]);

        // Backend css injected after css block, as a dirty workaround to override styles of vendor css injected from views
        $event->subject()->Html->css('Backend.backend', ['block' => true]);

        // scripts
        $event->subject()->Html->script('/backend/libs/bootstrap/dist/js/bootstrap.min.js', ['block' => true]);
        $event->subject()->Html->script('/backend/js/momentjs/moment.min.js', ['block' => true]);
        $event->subject()->Html->script('/backend/js/backend.js', ['block' => true]);
        //$event->subject()->Html->script('/backend/js/backend.alert.js', ['block' => true]);
        $event->subject()->Html->script('/backend/js/backend.iconify.js', ['block' => true]);
        $event->subject()->Html->script('/backend/js/backend.tooltip.js', ['block' => true]);
    }

    /**
     * {@inheritDoc}
     */
    public function beforeLayout(Event $event)
    {
        // title
        $title = $event->subject()->Blocks->get('title'); // check the title block
        $title = ($title) ?: $event->subject()->get('page.title'); // check the 'page.title' view var
        $title = ($title) ?: Inflector::humanize(Inflector::tableize($event->subject()->request['controller'])); // inflected controller name
        $event->subject()->Blocks->set('title', $title);

        $themeClass = (Configure::read('Backend.Theme.name')) ?: 'theme-default';
        $themeSkinClass = (Configure::read('Backend.Theme.skin')) ?: 'skin-default';
        $themeBodyClass = (Configure::read('Backend.Theme.bodyClass')) ?: '';

        if (Configure::read('Backend.Theme.darkmode')) {
            $event->subject()->Html->css('/backend/css/layout/dark.min.css', ['block' => true]);
            $themeBodyClass = trim($themeBodyClass . ' darkmode');
        }

        $event->subject()->set(
            'be_layout_body_class',
            trim(join(' ', [$themeClass, $themeSkinClass, $themeBodyClass]))
        );
        //$event->subject()->Html->css('/backend/css/skins/'.$themeSkinClass.'.min.css', ['block' => true]);

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
