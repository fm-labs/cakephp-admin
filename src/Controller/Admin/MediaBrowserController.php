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
        if (!Configure::check($configKey)) {
            throw new Exception(__("Media configuration {0} not found", $config));
        }
        $this->_mediaConfig = $config;
        $this->_mm = MediaManager::create($config);
    }

    public function beforeRender(Event $event)
    {
        $this->set('cfg', $this->_mediaConfig);
        $this->set('currentPath', $this->_mm->getPath());
        $this->set('parentPath', $this->_mm->getParentPath());
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
