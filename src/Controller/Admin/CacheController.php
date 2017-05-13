<?php

namespace Backend\Controller\Admin;


use Cake\Cache\Cache;
use Cake\Network\Exception\BadRequestException;

class CacheController extends AppController
{
    public function index()
    {
    }

    public function clear($config = null, $check = false)
    {
        if ($config === null && Cache::config($config) === null) {
            throw new BadRequestException('Cache config missing or not configured');
        }

        if (Cache::clear(false, $config)) {
            $this->Flash->success(__('Cache cleared'));
        } else {
            $this->Flash->error(__('Failed to clear cache'));
        }
        $this->redirect($this->referer(['action' => 'default']));
    }
}