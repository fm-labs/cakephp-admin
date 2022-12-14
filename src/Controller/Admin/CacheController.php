<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cake\Cache\Cache;
use Cake\Http\Exception\BadRequestException;

/**
 * Class CacheController
 *
 * @package Admin\Controller\Admin
 */
class CacheController extends AppController
{
    public $actions = []; //@TODO Disable ActionComponent

    public $modelClass = false;

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
            if (is_object($cache['className'])) {
                if (\Cake\Core\Plugin::isLoaded('DebugKit') && $cache['className'] instanceof \DebugKit\Cache\Engine\DebugEngine) {
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
     * @param null|string $config Cache config name.
     * @return void
     */
    public function clear(?string $config = null): void
    {
        if ($config === null || Cache::getConfig($config) === null) {
            throw new BadRequestException('Cache config missing or not configured');
        }

        if (Cache::clear($config)) {
            $this->Flash->success(__d('admin', 'Cache cleared'));
        } else {
            $this->Flash->error(__d('admin', 'Failed to clear cache'));
        }
        $this->redirect($this->referer(['action' => 'index']));
    }
}
