<?php
declare(strict_types=1);

// phpcs:ignoreFile

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Seo\Sitemap\Sitemap;
use Seo\Test\App\Error\ExceptionRenderer;

$findRoot = function ($root) {
    do {
        $lastRoot = $root;
        $root = dirname($root);
        if (is_dir($root . '/vendor/cakephp/cakephp')) {
            return $root;
        }
    } while ($root !== $lastRoot);
    throw new Exception('Cannot find the root of the application, unable to run tests');
};
$root = $findRoot(__FILE__);
unset($findRoot);
chdir($root);

require_once $root . '/vendor/autoload.php';
require_once $root . '/vendor/cakephp/cakephp/tests/bootstrap.php';

/*
define('ROOT', $root . DS . 'tests' . DS . 'test_app' . DS);
define('APP', ROOT . 'App' . DS);
define('CONFIG', APP);
define('TMP', sys_get_temp_dir() . DS);
define('CACHE', TMP . 'cache' . DS);

//used by Cake\Command\HelpCommand
define('CORE_PATH', $root . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS);
define('CAKE', CORE_PATH . 'src' . DS);

require CORE_PATH . 'config/bootstrap.php';

Configure::write('debug', true);
Configure::write('App', [
    'debug' => true,
    'namespace' => 'App',
    'paths' => [
        'plugins' => [ROOT . 'Plugin' . DS],
        'templates' => [ROOT . 'templates' . DS]
    ],
    'encoding' => 'UTF-8'
]);
Configure::write('Error', [
    'errorLevel' => E_ALL,
    'exceptionRenderer' => ExceptionRenderer::class,
    'skipLog' => [],
    'log' => true,
    'trace' => true,
]);

Cache::setConfig([
    '_cake_core_' => [
        'engine' => 'File',
        'prefix' => 'cake_core_',
        'serialize' => true,
        'path' => CACHE,
    ],
]);

if (!getenv('db_dsn')) {
    putenv('db_dsn=sqlite:///:memory:');
}
ConnectionManager::setConfig('test', ['url' => getenv('db_dsn')]);

//Plugin::getCollection()->add(new \Admin\Plugin());
*/