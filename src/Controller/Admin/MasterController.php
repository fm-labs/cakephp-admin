<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 3/20/16
 * Time: 1:07 PM
 */

namespace Backend\Controller\Admin;


class MasterController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function index()
    {
        $this->Flash->set("Flash Default!", ['params' => ['title' => 'This is a default flash message']]);
        /*
        $this->Flash->success("Flash Success!");
        $this->Flash->info("Flash Info!");
        $this->Flash->warning("Flash Warning!");
        $this->Flash->error("Flash Error!", ['params' => ['title' => 'Error:']]);
        $this->Flash->error("Flash Error!", ['params' => ['title' => 'Not Dismissable!', 'dismiss' => false]]);
        */

        $this->viewBuilder()->layout('master');
    }
}