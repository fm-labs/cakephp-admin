<?php

namespace Backend\View;

use Backend\View\Helper\AjaxHelper;
use Backend\View\Helper\BackendHelper;
use Backend\View\Helper\ToolbarHelper;
use Bootstrap\View\Helper\UiHelper;
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
            $this->Blocks->set('title', $this->request['controller']);
        }

        return parent::renderLayout($content, $layout);
    }
}
