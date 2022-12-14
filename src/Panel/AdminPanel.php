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

    /**
     * @return string
     */
    public function title()
    {
        return "Admin";
    }

//    public function data()
//    {
//        $plugins = Admin::getPlugins();
//
//        return compact('plugins');
//    }

    /**
     * @return string
     */
    public function elementName()
    {
        return $this->plugin . '.debug_kit/admin_panel';
    }
}
