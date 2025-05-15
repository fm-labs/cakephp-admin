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
    public string $plugin = 'Admin';

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Admin';
    }

    /**
     * @return array
     */
    public function data(): array
    {
        $config = Configure::read();
        $plugins = Admin::getPlugins();
        $locale = ['default' => I18n::getDefaultLocale(), 'current' => I18n::getLocale()];

        return compact('config', 'plugins', 'locale');
    }

    /**
     * @return string
     */
    public function elementName(): string
    {
        return $this->plugin . '.DebugKit/admin_panel';
    }
}
