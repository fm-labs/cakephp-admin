<?php

namespace Backend\View\Helper;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Inflector;
use Cake\View\Helper;

class BackendLayoutHelper extends Helper
{
    protected $_blocks = [];

    protected $_themeConfig = ['name' => null, 'skin' => null, 'bodyClass' => null, 'darkmode' => null];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config = [])
    {
        $this->_View->loadHelper('Backend.Backend');
        $this->_View->loadHelper('Breadcrumb', ['className' => 'Backend.Breadcrumb']);
        $this->_View->loadHelper('Toolbar', ['className' => 'Backend.Toolbar']);
        /*
        $this->_View->loadHelper('User.UserSession', [
            'sessionKey' => 'Backend.UserSession',
            'loginUrl' => ['_name' => 'admin:backend:user:login'],
            'checkUrl' => ['_name' => 'admin:backend:user:checkauth']
        ]);
        */

        if (Configure::read('Backend.Theme.enableJsFlash')) {
            $this->_View->loadHelper('Backend.Toastr');
        }

        if (Configure::read('Backend.Theme.enableJsAlerts')) {
            $this->_View->loadHelper('Backend.SweetAlert2');
        }

        $this->_blocks = (array)Configure::read('Backend.Layout.admin.blocks');
        $this->_themeConfig = (array)Configure::read('Backend.Theme') + $this->_themeConfig;
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

        $themeClass = ($this->_themeConfig['name']) ?: 'theme-default';
        $themeSkinClass = ($this->_themeConfig['skin']) ?: 'skin-default';
        $themeBodyClass = ($this->_themeConfig['bodyClass']) ?: '';

        if ($this->_themeConfig['darkmode']) {
            $event->subject()->Html->css('/backend/css/layout/dark.min.css', ['block' => true]);
            $themeBodyClass = trim($themeBodyClass . ' darkmode');
        }

        $event->subject()->set(
            'be_layout_body_class',
            trim(join(' ', [$themeClass, $themeSkinClass, $themeBodyClass]))
        );
        //$event->subject()->Html->css('/backend/css/skins/'.$themeSkinClass.'.min.css', ['block' => true]);


        /*
        foreach ($this->_blocks as $block => $contents) {
            foreach ($contents as $content) {
                $_block = (isset($content['block'])) ? $content['block'] : $block;
                if (isset($content['element'])) {
                    $event->subject()->append($_block, $event->subject()->element($content['element']));
                } elseif (isset($content['cell'])) {
                    $event->subject()->append($_block, $event->subject()->cell($content['cell']));
                }
            }
        }
        */
    }
}
