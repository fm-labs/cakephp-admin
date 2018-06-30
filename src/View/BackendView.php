<?php

namespace Backend\View;

use Backend\View\Helper\AjaxHelper;
use Backend\View\Helper\BackendHelper;
use Backend\View\Helper\FormatterHelper;
use Backend\View\Helper\Layout\ToolbarHelper;
use Bootstrap\View\Helper\UiHelper;
use Cake\Core\Configure;
use Cake\Event\Event;
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
        $this->loadHelper('Html', [/*'className' => 'Backend\View\Helper\BackendHtmlHelper'*/]);
        $this->loadHelper('Form', ['className' => 'Backend\View\Helper\BackendFormHelper']);

        //@todo Remove hard dependencies of Backend helpers
        $this->loadHelper('Bootstrap.Ui', []);
        $this->loadHelper('Bootstrap.Button', []);
        $this->loadHelper('Backend.Backend', []);
        $this->loadHelper('Backend.Ajax', []);
        $this->loadHelper('Backend.FooTable', []);
        $this->loadHelper('Backend.BackendLayout', []);

        $this->loadHelper('Banana.Status', []); //@TODO Remove this hard dependency
        FormatterHelper::register('status', function($val, $extra, $params) {
            return $this->Status->label($val);
        });

        $this->eventManager()->dispatch(new Event('Backend.View.initialize', $this));
    }

}
