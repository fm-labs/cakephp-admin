<?php
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;

/**
 * Automatically load app's backend configuration
 *
 * Copy backend.default.php to your app's config folder,
 * rename to backend.php and adjust contents
 */
Configure::load('Backend.backend');

Plugin::load('Bootstrap');
Plugin::load('AdminLte');

/*
Cache::config('backend', [
    'className' => 'File',
    'duration' => '+1 hours',
    'path' => CACHE,
    'prefix' => 'backend_'
]);
*/