<?php

namespace Backend\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * Class BackendHelper
 *
 * @package Backend\View\Helper
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class BackendHelper extends Helper
{
    public $helpers = ['Html', 'Url'];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config)
    {
        // 3rd party dependencies
        $this->_View->loadHelper('Backbone', ['className' => 'Backend.Backbone']);
        $this->Html->css('/backend/libs/bootstrap/dist/css/bootstrap.min.css', ['block' => true]);
        $this->Html->script('/backend/libs/bootstrap/dist/js/bootstrap.min.js', ['block' => true]);
        $this->Html->script('/backend/js/momentjs/moment.min.js', ['block' => true]);
        $this->Html->css('/backend/libs/fontawesome/css/font-awesome.min.css', ['block' => true]);
        $this->Html->css('/backend/libs/ionicons/css/ionicons.min.css', ['block' => true]);
        $this->_View->loadHelper('Bootstrap.Ui');

        // Backend css injected after css block, as a dirty workaround to override styles of vendor css injected from views
        $this->Html->css('Backend.backend', ['block' => true]);

        // Inject backendjs init script
        $backendjs = [
            'rootUrl' => $this->Url->build('/', true),
            'adminUrl' => $this->Url->build('/admin/', true),
            'debug' => Configure::read('debug')
        ];
        //$script = sprintf('console.log("INIT", window.Backend); if (window.Backend !== undefined) { console.log("INIT2");  Backend.initialize(%s); }', json_encode($backendjs));

        $script = sprintf('var BackendSettings = window.BackendSettings = %s; console.log("[backendjs] SETTIGNS", window.BackendSettings)', json_encode($backendjs));
        $this->Html->scriptBlock($script, ['block' => true, 'safe' => false]);

        $this->Html->script('/backend/js/backend.js', ['block' => true]);
        //$this->Html->script('/backend/js/backend.alert.js', ['block' => true]);
        $this->Html->script('/backend/js/backend.iconify.js', ['block' => true]);
        $this->Html->script('/backend/js/backend.tooltip.js', ['block' => true]);
    }
}
