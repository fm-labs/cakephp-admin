<?php

namespace Backend\Controller\Admin;

use Cake\Core\Configure;

class DashboardController extends AppController
{
    /**
     * Default Dashboard
     */
    public function index()
    {
        $this->Flash->set('Hello');
        $this->Flash->success('Success');
        $this->Flash->warning('Warning');
        $this->Flash->error('Error');
        $this->Flash->info('Info');
    }

    /**
     * Backend Dashboard
     */
    public function backend()
    {
    }
}
