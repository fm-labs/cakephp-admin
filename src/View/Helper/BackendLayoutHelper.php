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
        $this->_View->loadHelper('Backend.Backend');
        $this->_View->loadHelper('Navigation', ['className' => 'Backend.Layout/Navigation']);
        $this->_View->loadHelper('Header', ['className' => 'Backend.Layout/Header']);
        $this->_View->loadHelper('Toolbar', ['className' => 'Backend.Layout/Toolbar']);
        $this->_View->loadHelper('Sidebar', ['className' => 'Backend.Layout/Sidebar']);
        $this->_View->loadHelper('User.UserSession', [
            'sessionKey' => 'Backend.UserSession',
            'loginUrl' => ['_name' => 'backend:admin:user:login'],
            'checkUrl' => ['_name' => 'backend:admin:user:checkauth']
        ]);

        if (Configure::read('Backend.Theme.enableJsFlash')) {
            $this->_View->loadHelper('Backend.Toastr');
        }

        if (Configure::read('Backend.Theme.enableJsAlerts')) {
            $this->_View->loadHelper('Backend.SweetAlert2');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function implementedEvents()
    {
        return [
            'View.beforeRender' => ['callable' => 'beforeRender'/*, 'priority' => 2*/],
            'View.beforeLayout' => ['callable' => 'beforeLayout'/*, 'priority' => 2*/]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function beforeRender(Event $event)
    {
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
            'flash' => [
                [
                    'element' => 'Backend.Layout/admin/flash'
                ]
            ],
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
