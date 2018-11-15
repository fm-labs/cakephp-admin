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
            'rootUrl' => $this->Url->build('/', true),
            'adminUrl' => $this->Url->build('/admin/', true),
            'debug' => Configure::read('debug')
        ];
        //$script = sprintf('console.log("INIT", window.Backend); if (window.Backend !== undefined) { console.log("INIT2");  Backend.initialize(%s); }', json_encode($backendjs));

        $script = sprintf('var BackendSettings = window.BackendSettings = %s; console.log("[backendjs] SETTIGNS", window.BackendSettings)', json_encode($backendjs));
        $this->Html->scriptBlock($script, ['block' => true, 'safe' => false]);
    }

}
