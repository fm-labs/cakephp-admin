<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cake\Core\Configure;

class AdminController extends AppController
{
    public $actions = [
        'index' => 'Admin.Dashboard',
    ];

//    /**
//     * @inheritDoc
//     */
//    public function initialize(): void
//    {
//        parent::initialize();
//
//        if ($this->Authentication) {
//            $this->Authentication->allowUnauthenticated(['fallback']);
//        }
//    }

    /**
     * Default Dashboard
     *
     * @return void
     */
    public function index()
    {
        $this->set('dashboard.title', Configure::read('Admin.Dashboard.title', __d('admin', 'System Dashboard')));
        $this->set('dashboard.panels', Configure::read('Admin.Dashboard.panels', []));
        $this->Action->execute();
        $this->render('dashboard');
    }

//    /**
//     *
//     * @param string|null $path The unmatched uri path
//     * @return void
//     */
//    public function fallback(?string $path = null): void
//    {
//        $this->Flash->error(__d('admin', 'Sorry, this admin url you requested is not connected: {0}', h($path)));
//        //$this->redirect($this->referer(['action' => 'index']));
//        //$this->redirect($this->referer(['action' => 'index']));
//        //$this->setAction('index');
//        //$this->setResponse($this->getResponse()
//        //    ->withStatus(404));
//    }
}
