<?php

namespace Backend\Controller\Admin;

use Cake\Cache\Cache;
use Cake\Http\Exception\BadRequestException;

/**
 * Class CacheController
 *
 * @package Backend\Controller\Admin
 */
class CacheController extends AppController
{
    /**
     * List cache configs
     */
    public function index()
    {
    }

    /**
     * Clear cache by config name
     *
     * @param null $config
     * @param bool|false $check
     */
    public function clear($config = null, $check = false)
    {
        if ($config === null && Cache::getConfig($config) === null) {
            throw new BadRequestException('Cache config missing or not configured');
        }

        if (Cache::clear(false, $config)) {
            $this->Flash->success(__d('backend', 'Cache cleared'));
        } else {
            $this->Flash->error(__d('backend', 'Failed to clear cache'));
        }
        $this->redirect($this->referer(['action' => 'default']));
    }
}
