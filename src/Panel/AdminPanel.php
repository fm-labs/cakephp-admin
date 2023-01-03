<?php
declare(strict_types=1);

namespace Admin\Panel;

use Admin\Admin;
use Cake\Core\Configure;
use Cake\I18n\I18n;
use DebugKit\DebugPanel;

/**
 * @codeCoverageIgnore
 */
class AdminPanel extends DebugPanel
{
    public $plugin = 'Admin';

    /**
     * @return string
     */
    public function title()
    {
        return "Admin";
    }

    public function data()
    {
        $config = Configure::read();
        $plugins = Admin::getPlugins();
        $locale = ['default' => I18n::getDefaultLocale(), 'current' => I18n::getLocale()];
        return compact('config', 'plugins', 'locale');
    }

    /**
     * @return string
     */
    public function elementName()
    {
        return $this->plugin . '.DebugKit/admin_panel';
    }
}
