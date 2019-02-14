<?php

namespace Backend\Controller\Admin;

use Cake\Form\Form;
use Cake\ORM\Entity;

class DesignController extends AppController
{
    /**
     * Index action
     * @return void
     */
    public function index()
    {
        $section = $this->request->query('section');
        if ($section) {
            if (method_exists($this, $section)) {
                $this->setAction($section);

                return;
            }
        }

        $this->render($section);
    }

    public function form()
    {
        $form = new Form();
        $form->schema()
            ->addField('h_text', ['type' => 'string'])
            ->addField('h_text_error', ['type' => 'string'])
            ->addField('h_checkbox', ['type' => 'tinyint']);

        $form->validator()
            ->requirePresence('h_text_error', true)
            ->notEmpty('h_text_error');

        if  ($this->request->is(['post'])) {
            $form->execute($this->request->data);
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
}
