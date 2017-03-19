<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/25/15
 * Time: 6:05 PM
 */

namespace Backend\View;

use Cake\Event\Event;
use Cake\View\View;

class BackendView extends View
{
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
