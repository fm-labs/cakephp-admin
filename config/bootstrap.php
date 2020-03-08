<?php
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Log\Log;

if (!Plugin::isLoaded('User')) {
    die("User plugin missing");
}

//Plugin::load('Bootstrap');
//Plugin::load('User');

/**
 * Logs
 */
if (!Log::getConfig('backend')) {
    Log::setConfig('backend', [
        'className' => 'Cake\Log\Engine\FileLog',
        'path' => LOGS,
        'file' => 'backend',
        //'levels' => ['info'],
        'scopes' => ['admin', 'backend'],
    ]);
}

/**
 * Cache config
 */
if (!Cache::getConfig('backend')) {
    Cache::setConfig('backend', [
        'className' => 'File',
        'duration' => '+1 hours',
        'path' => CACHE,
        'prefix' => 'backend_',
    ]);
}

Configure::load('Backend.layout/admin');
