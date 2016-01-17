<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 9/5/15
 * Time: 5:19 PM
 */

namespace Backend\Controller\Admin;

use Backend\Controller\Admin\AppController;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Routing\Router;
use Media\Lib\Media\MediaManager;

class MediaBrowserController extends AppController
{

    /**
     * @var MediaManager
     */
    protected $_mm;

    /**
     * @var string Name of media configuration
     */
    protected $_mediaConfig;

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');

        //$this->layout = "Backend.media_browser";

        $config = $this->request->param('config');
        if (!$config) {
            $config = $this->request->query('config');
        }
        if (!$config) {
            $config = "default";
        }
        $configKey = 'Media.'.$config;
        if (!Plugin::loaded('Media') || !Configure::check($configKey)) {
            $this->request->params['action'] = 'noconfig';
            $this->request->params['config'] = $config;
            //$this->viewBuilder()->view('notfound');
        } else {
            $this->_mediaConfig = $config;
            $this->_mm = MediaManager::get($config);
        }
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        if ($this->_mediaConfig) {
            $this->set('cfg', $this->_mediaConfig);
            $this->set('currentPath', $this->_mm->getPath());
            $this->set('parentPath', $this->_mm->getParentPath());
        }
    }

    public function noconfig()
    {
        $pluginLoaded = Plugin::loaded('Media');
        $config = $this->request->params['config'];
        if ($pluginLoaded) {
            $configExample = @file_get_contents(Plugin::path('Media') . DS . 'config' . DS . 'media.default.php');
        } else {
            $configExample = "Media plugin must be loaded to show example configuration";
        }

        $this->set('pluginLoaded', $pluginLoaded);
        $this->set('configName', $config);
        $this->set('configExample', $configExample);
    }


    public function index()
    {
        $this->redirect(['action' => 'browse']);
    }

    public function browse()
    {
        $path = $this->request->query('path');
        $file = $this->request->query('file');
        $this->_mm->open($path);

        $this->set('directories', $this->_mm->listFolders());
        $this->set('files', $this->_mm->listFiles());
        $this->render('index');
    }

    public function treeData()
    {
        $this->viewBuilder()->className('Json');

        $id = $this->request->query('id');
        $path = ($id == '#') ? '/' : $id;
        $treeData = [];

        $mm =& $this->_mm;
        $mm->open($path);

        $folders = $mm->listFoldersRecursive(0);
        array_walk($folders, function ($val) use (&$treeData, &$id) {
            $treeData[] = [
                'id' => $val,
                'text' => basename($val),
                'children' => true,
                'type' => 'folder',
                'parent' => $id
            ];
        });

        /*
        $files = $mm->listFiles();
        array_walk($files, function ($val) use (&$treeData, &$mm, &$parent) {
            $treeData[] = ['id' => $val, 'text' => basename($val), 'children' => false, 'type' => 'file', 'icon' => $mm->getFileUrl($val)];
        });
        */


        $this->set('treeData', $treeData);
        $this->set('_serialize', 'treeData');
    }


    public function filesData()
    {
        $this->viewBuilder()->className('Json');

        $id = $this->request->query('id');
        $path = ($id == '#') ? '/' : $id;
        $treeData = [];

        $mm =& $this->_mm;
        $mm->open($path);

        $files = $mm->listFiles();
        array_walk($files, function ($val) use (&$treeData, &$mm, &$parent) {

            $icon = true;
            $filename = basename($val);
            if (preg_match('/^(.*)\.(jpg|gif|jpeg|png)$/i', $filename)) {
                // use thumbnail as icon
                $icon = $mm->getFileUrl($val);
            } elseif (preg_match('/^\./', $filename)) {
                // ignore dot-files
                return;
            }

            $treeData[] = [
                'id' => $val,
                'text' => basename($val),
                'children' => false,
                'type' => 'file',
                'icon' => $icon,
                'actions' => [
                    //['title' => 'View', 'icon' => 'eye', 'url' => Router::url(['action' => 'view', 'path' => $val ])],
                    //['title' => 'Edit', 'icon' => 'edit', 'url' => Router::url(['action' => 'edit', 'path' => $val ])],
                    ['title' => 'Download', 'icon' => 'download', 'url' => Router::url(['action' => 'download', 'path' => $val ])],
                    //['title' => 'Download', 'icon' => 'download', 'action' => 'download' ])]
                ]
            ];
        });


        $this->set('treeData', $treeData);
        $this->set('_serialize', 'treeData');
    }


    public function filepicker()
    {
        $path = $this->request->query('path');
        $file = $this->request->query('file');
        $this->_mm->open($path);

        $this->set('folders', $this->_mm->listFolders());
        $this->set('files', $this->_mm->listFiles());
    }


    /**
     * @deprecated
     */
    public function treeFiles()
    {
        $this->viewBuilder()->className('Json');

        $files = [];
        $selectedDirs = $this->request->data('selected');
        foreach ($selectedDirs as $dir) {
            $this->_mm->open($dir);
            $files += $this->_mm->listFileUrls();
        }

        $treeData = [];
        array_walk($files, function ($val) use (&$treeData) {
            $treeData[] = ['id' => $val, 'text' => basename($val), 'icon' => 'file'];
        });

        $this->set('treeData', $treeData);
        $this->set('_serialize', 'treeData');
    }

}
