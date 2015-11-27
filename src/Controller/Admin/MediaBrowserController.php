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

    public function beforeRender(Event $event)
    {
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
        $path = $this->request->query('path');
        $file = $this->request->query('file');
        $this->_mm->open($path);

        $this->set('directories', $this->_mm->listFolders());
        $this->set('files', $this->_mm->listFiles());
    }

    public function browse()
    {
        $this->setAction('index');
    }

    public function file_open()
    {

    }

    public function file_create()
    {

    }

    public function file_copy()
    {

    }

    public function file_delete()
    {

    }

    public function file_info()
    {

    }

    public function dir_create()
    {

    }

    public function dir_copy()
    {

    }

    public function dir_move()
    {

    }

    public function dir_delete()
    {

    }

    public function dir_info()
    {

    }
}
