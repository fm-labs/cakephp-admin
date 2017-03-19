<?php
use Cake\Core\Configure;
use Cake\Core\Plugin;

/**
 * Automatically load app's backend configuration
 *
 * Copy backend.default.php to your app's config folder,
 * rename to backend.php and adjust contents
 */
Configure::load('Backend.backend');
try { Configure::load('backend'); } catch (\Exception $ex) {}
try { Configure::load('local/backend'); } catch (\Exception $ex) {}

Plugin::load('Bootstrap');
Plugin::load('AdminLte');

\Cake\Event\EventManager::instance()->on(new \Backend\Event\BackendEventListener());