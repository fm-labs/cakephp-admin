<?php

namespace Backend\View\Helper;


use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\View;

/**
 * Class BackendHelper
 *
 * @package Backend\View\Helper
 */
class BackendHelper extends Helper
{
    public $helpers = ['Html', 'Url'];

    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);

        // Load backendjs script
        //$this->Html->script('Backend.backend', ['block' => 'script']);

        // Inject backendjs init script
        $backendjs = [
            'rootUrl' => $this->Url->build('/'),
            'debug' => Configure::read('debug')
        ];
        $script = sprintf('if (window.Backend !== undefined) { Backend.init(%s); }', json_encode($backendjs));
        $this->Html->scriptBlock($script, ['block' => 'script']);
    }
}