<?php

namespace Backend\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;

/**
 * Class SweetAlert2Helper
 * @package Backend\View\Helper
 *
 * @property HtmlHelper $Html
 */
class SweetAlert2Helper extends Helper
{
    public $helpers = ['Html'];

    public function initialize(array $config)
    {
        if (Configure::read('debug')) {
            $this->Html->css('/backend/libs/sweetalert2/sweetalert2.css', ['block' => true]);
            $this->Html->script('/backend/libs/sweetalert2/sweetalert2.js', ['block' => true]);
        } else {
            $this->Html->css('/backend/libs/sweetalert2/sweetalert2.min.css', ['block' => true]);
            $this->Html->script('/backend/libs/sweetalert2/sweetalert2.min.js', ['block' => true]);
        }
    }
}
