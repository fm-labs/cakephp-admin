<?php
declare(strict_types=1);

namespace Backend\Controller\Admin;

use Cake\Form\Form;

class DesignController extends AppController
{
    public $sections = [
        'form',
        'table',
        'box',
        'component',
        'tabs',
    ];

    /**
     * Index action
     * @return void
     */
    public function index()
    {
        $section = $this->request->getQuery('section');
        if ($section && in_array($section, $this->sections)) {
            if (method_exists($this, $section)) {
                $this->setAction($section);

                return;
            }
        }

        $flash = $this->request->getQuery('flash');
        if ($flash) {
            $this->Flash->{$flash}('Awesome!', ['title' => 'My title']);
            $this->Flash->set('Realy cool!', ['title' => 'Just another flash message']);
        }

        $this->render($section);
    }

    public function form()
    {
        $form = new Form();
        $form->getSchema()
            ->addField('h_text', ['type' => 'string'])
            ->addField('h_text_error', ['type' => 'string'])
            ->addField('h_checkbox', ['type' => 'tinyint']);

        $form->getValidator()
            ->requirePresence('h_text_error', true)
            ->notEmptyString('h_text_error');

        if ($this->request->is(['post'])) {
            $form->execute($this->request->getData());
        } else {
            $form->validate([]);
        }

        $selectOptions = [
            'apples' => 'Apples',
            'bananas' => 'Bananas',
            'coconut' => 'Coconut',
        ];

        $this->set(compact('form', 'selectOptions'));
    }

    public function daterange()
    {
    }

    public function ajaxTest()
    {
    }
}
