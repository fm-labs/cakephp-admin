<?php

namespace Backend\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * Class BackendHelper
 *
 * @package Backend\View\Helper
 */
class BackendHelper extends Helper
{
    public $helpers = ['Html', 'Url'];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config)
    {
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
