<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 2/15/15
 * Time: 11:16 PM
 */

namespace Backend\Controller\Admin;

use Backend\Controller\AppController;
use Backend\Lib\FileManager\FileManager;

class FileManagerController extends AppController
{
    public function index()
    {
        $d = ($this->request->query('d')) ?: '/';
        $f = ($this->request->query('f')) ?: null;

        $fm = new FileManager([
            'root' => DATA_PATH
        ]);

        $this->set('fm', $fm);
        $this->set('d', $fm->openDir(urldecode($d)));
        $this->set('f', $f);
    }
}
