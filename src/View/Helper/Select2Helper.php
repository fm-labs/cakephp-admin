<?php

namespace Backend\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\Helper\FormHelper;
use Cake\View\Helper\HtmlHelper;

/**
 * Class Select2Helper
 * @package Backend\View\Helper
 *
 * @property HtmlHelper $Html
 * @property FormHelper $Form
 */
class Select2Helper extends Helper
{
    public $helpers = ['Html', 'Form'];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config)
    {
        if (Configure::read('debug')) {
            $this->Html->css('/backend/libs/select2/dist/css/select2.css', ['block' => true]);
            $this->Html->css('/backend/libs/select2-bootstrap-theme/dist/select2-bootstrap.css', ['block' => true]);
            $this->Html->script('/backend/libs/select2/dist/js/select2.js', ['block' => true]);
        } else {
            $this->Html->css('/backend/libs/select2/dist/css/select2.min.css', ['block' => true]);
            $this->Html->css('/backend/libs/select2-bootstrap-theme/dist/select2-bootstrap.min.css', ['block' => true]);
            $this->Html->script('/backend/libs/select2/dist/js/select2.min.js', ['block' => true]);
        }

        $this->Form->addWidget('select2', ['Backend\View\Widget\Select2Widget', '_view']);
    }
}
