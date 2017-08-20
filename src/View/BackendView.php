<?php

namespace Backend\View;

use Backend\View\Helper\AjaxHelper;
use Backend\View\Helper\BackendHelper;
use Backend\View\Helper\ToolbarHelper;
use Bootstrap\View\Helper\UiHelper;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Inflector;
use Cake\View\View;
use Banana\View\ViewModuleTrait;

/**
 * Class BackendView
 * @package Backend\View
 *
 * @property BackendHelper $Backend
 * @property AjaxHelper $Ajax
 * @property ToolbarHelper $Toolbar
 * @property UiHelper $Ui
 */
class BackendView extends View
{
    use ViewModuleTrait;

    public function initialize()
    {

        $this->loadHelper('Html', [
            //'className' => 'Backend\View\Helper\BackendHtmlHelper'
        ]);
        $this->loadHelper('Form', [
            'className' => 'Backend\View\Helper\BackendFormHelper',
        ]);

        $this->loadHelper('Backend.Backend', []);
        $this->loadHelper('Backend.Ajax', []);
        $this->loadHelper('Backend.Toolbar', []); //@todo Remove hard depdency
        $this->loadHelper('Bootstrap.Ui', []);

        $this->eventManager()->dispatch(new Event('Backend.View.initialize', $this));
    }

    public function render($view = null, $layout = null)
    {
        return parent::render($view, $layout);
    }

    public function renderLayout($content, $layout = null)
    {

        $title = $this->Blocks->get('title');
        if ($title === '') {
            $this->Blocks->set('title', Inflector::humanize(Inflector::tableize($this->request['controller'])));
        }

        // AdminLTE layout options
        $themeSkinClass = (Configure::read('Backend.AdminLte.skin_class')) ?: 'skin-blue';
        $themeLayoutClass = (Configure::read('Backend.AdminLte.layout_class')) ?: '';
        $themeSidebarClass = (Configure::read('Backend.AdminLte.sidebar_class')) ?: 'sidebar-mini';

        $this->set('be_adminlte_skin_class', $themeSkinClass);
        $this->set('be_adminlte_layout_class', $themeLayoutClass);
        $this->set('be_adminlte_sidebar_class', $themeSidebarClass);
        $this->set('be_layout_body_class',
            trim(join(' ', [$themeSkinClass, $themeSidebarClass, $themeLayoutClass])));

        $this->Html->css('/backend/css/adminlte/skins/'.$themeSkinClass.'.min.css', ['block' => true]);

        return parent::renderLayout($content, $layout);
    }
}
