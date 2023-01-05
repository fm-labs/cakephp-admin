<?php
declare(strict_types=1);

namespace Admin\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * Class AdminHelper
 *
 * @package Admin\View\Helper
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\UrlHelper $Url
 */
class AdminJsHelper extends Helper
{
    public $helpers = ['Html', 'Url'];

    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        // Inject adminjs init script
        $adminjs = [
            'rootUrl' => $this->Url->build('/', ['fullBase' => true]),
            'adminUrl' => $this->Url->build('/' . \Admin\Admin::$urlPrefix, ['fullBase' => true]),
            'debug' => Configure::read('debug'),
        ];

        $script = sprintf('var AdminJsSettings = window.AdminJsSettings = %s;', json_encode($adminjs));
        if (Configure::read('debug')) {
            $script .= 'console.log("[adminjs] global settings", window.AdminJsSettings);';
        }
        $this->Html->scriptBlock($script, ['block' => true, 'safe' => false]);

        $this->Html->script('Admin.admin.js', ['block' => true]);
        //$this->Html->script('Admin.admin.iconify.js', ['block' => true]);
        //$this->Html->script('Admin.admin.tooltip.js', ['block' => true]);
        //$this->Html->script('Admin.admin.checkauth.js', ['block' => true]);
        //$this->Html->script('Admin.admin.alert.js', ['block' => true]);
    }
}
