<?php
declare(strict_types=1);

namespace Admin\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * Class SweetAlert2Helper
 * @package Admin\View\Helper
 *
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class SweetAlert2Helper extends Helper
{
    public $helpers = ['Html'];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        if (Configure::read('debug')) {
            $this->Html->css('/admin/libs/sweetalert2/sweetalert2.css', ['block' => true]);
            $this->Html->script('/admin/libs/sweetalert2/sweetalert2.js', ['block' => true]);
        } else {
            $this->Html->css('/admin/libs/sweetalert2/sweetalert2.min.css', ['block' => true]);
            $this->Html->script('/admin/libs/sweetalert2/sweetalert2.min.js', ['block' => true]);
        }
    }
}
