<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

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

    public $modelClass = false;

    public $actions = [];

    /**
     * @return void
     */
    public function index(): void
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
        }

        $this->render($section);
    }

    /**
     * @return void
     */
    public function form(): void
    {
        $form = new Form();
        $form->schema()
            ->addField('h_text', ['type' => 'string'])
            ->addField('h_text_error', ['type' => 'string'])
            ->addField('h_checkbox', ['type' => 'tinyint'])
            ->addField('text_error', ['type' => 'string'])
            ->addField('checkbox', ['type' => 'tinyint'])
        ;

        $form->getValidator()
            ->requirePresence('h_text_error', true)
            ->notEmptyString('h_text_error')

            ->requirePresence('text_error', true)
            ->notEmptyString('text_error')

            ->requirePresence('checkbox', true)
            ->equals('checkbox', 1)
        ;

        if ($this->request->is(['post'])) {
            $form->execute($this->request->getData());
        } else {
            $form->validate([]);
        }

        $selectOptions = [
            'apples' => 'Apples',
            'cupcakes' => 'Cupcakes',
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
