<?php
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Log\Log;

if (!Plugin::loaded('User')) {
    die("User plugin missing");
}

Plugin::load('Bootstrap');
//Plugin::load('User');

/**
 * Logs
 */
Log::config('backend', [
    'className' => 'Cake\Log\Engine\FileLog',
    'path' => LOGS,
    'file' => 'backend',
    //'levels' => ['info'],
    'scopes' => ['admin', 'backend']
]);

/**
 * Cache config
 */
if (!Cache::config('backend')) {
    Cache::config('backend', [
        'className' => 'File',
        'duration' => '+1 hours',
        'path' => CACHE,
        'prefix' => 'backend_'
    ]);
}

Configure::load('Backend.layout/admin');
