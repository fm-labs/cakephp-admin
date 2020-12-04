<?php
declare(strict_types=1);

namespace Admin\Panel;

use Admin\Admin;
use DebugKit\DebugPanel;

/**
 * @codeCoverageIgnore
 */
class AdminPanel extends DebugPanel
{
    public $plugin = 'Admin';

    public function data()
    {
        $plugins = Admin::getPlugins();

        return compact('plugins');
    }
}
