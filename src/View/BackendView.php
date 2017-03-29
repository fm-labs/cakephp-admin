<?php

namespace Backend\View;

use Cake\Event\Event;
use Cake\View\View;
use Banana\View\ViewModuleTrait;

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
        $this->loadHelper('Backend.Toolbar', []); //@TODO Remove hard dependency
        $this->loadHelper('Bootstrap.Ui', []);

        $this->eventManager()->dispatch(new Event('Backend.View.initialize', $this));
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
