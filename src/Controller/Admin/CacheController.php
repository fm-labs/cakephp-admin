<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cake\Cache\Cache;
use Cake\Core\Plugin;
use Cake\Http\Exception\NotFoundException;
use DebugKit\Cache\Engine\DebugEngine;
use Exception;

/**
 * Class CacheController
 *
 * @package Admin\Controller\Admin
 */
class CacheController extends AppController
{
    public array $actions = []; //@TODO Disable ActionComponent

    /**
     * List cache configs
     *
     * @return void
     */
    public function index(): void
    {
        $caches = [];
        foreach (Cache::configured() as $alias) {
            $cache = Cache::getConfig($alias);
            if (isset($cache['className']) && is_object($cache['className'])) {
                if (
                    Plugin::isLoaded('DebugKit')
                    && $cache['className'] instanceof DebugEngine
                ) {
                    if (!$cache['className']->engine()) {
                        $cache['className']->init();
                    }
                    $cache['originalClassName'] = get_class($cache['className']->engine());
                }
                $cache['className'] = get_class($cache['className']);
            }
            $caches[$alias] = $cache;
        }

        //    foreach(Cache::configured() as $key) {
        //        $cache = Cache::config($key);
        //        if (is_object($cache['className'])) {
        //            if ($cache['className'] instanceof \DebugKit\Cache\Engine\DebugEngine && !$cache['className']->engine()) {
        //                $cache['className']->init();
        //            }
        //            debug($cache['className']->config());
        //        }
        //    }

        $this->set(compact('caches'));
    }

    /**
     * Clear cache by config name
     *
     * @param string|null $config Cache config name.
     * @return void
     */
    public function clear(?string $config = null): void
    {
        try {
            if ($config === null || Cache::getConfig($config) === null) {
                throw new NotFoundException('Cache config missing or not configured');
            }

            if (!Cache::clear($config)) {
                throw new Exception('Failed to clear cache');
            }

            $this->Flash->success(__d('admin', 'Cache cleared'));
        } catch (Exception $exception) {
            $this->Flash->error(__d('admin', $exception->getMessage()));
        }

        $this->redirect($this->referer(['action' => 'index']));
    }
}
