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
if (!Log::getConfig('admin')) {
    Log::setConfig('admin', [
        'className' => 'Cake\Log\Engine\FileLog',
        'path' => LOGS,
        'file' => 'admin',
        //'levels' => ['info'],
        'scopes' => ['admin', 'admin'],
    ]);
}

/**
 * Cache config
 */
if (!Cache::getConfig('admin')) {
    Cache::setConfig('admin', [
        'className' => 'File',
        'duration' => '+1 hours',
        'path' => CACHE,
        'prefix' => 'admin_',
    ]);
}

Configure::load('Admin.layout/admin');
