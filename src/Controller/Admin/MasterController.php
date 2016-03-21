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
        $this->viewBuilder()->layout('master');
    }
}