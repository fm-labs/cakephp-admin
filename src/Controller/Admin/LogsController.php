<?php
namespace Backend\Controller\Admin;

use Cake\Filesystem\File;
use Cake\Filesystem\Folder;

class LogsController extends AppController
{
    public $permissions = [
        'index' => ['logs.view'],
        'view' => ['logs.view'],
        'clear' => ['logs.edit'],
        'rotate' => ['logs.edit'],
        'delete' => ['logs.delete']
    ];

    public $logDir = LOGS;

    protected function _getFilePath($logFile)
    {
        $logFile .= '.log';
        $path = realpath($this->logDir . $logFile);
        if (!$path) {
            return false;
        }

        if (strpos($path, '..') !== false) {
            return false;
        }

        if (!preg_match('/^'.preg_quote($this->logDir, '/') . '/', $path)) {
            return false;
        }

        return $path;
    }

    public function index()
    {
        $logDir = $this->logDir;

        $Folder = new Folder($logDir, false);
        $logFiles = $Folder->find('.*.log(\.[0-9])?', true);

        $files = array();
        foreach ($logFiles as $logFile) {
            $F = new File($logDir.$logFile);

            $file = array(
                'id' => basename($logFile, '.log'),
                'name' => $logFile,
                //'dir' => $logDir,
                'size' => $F->size(),
                'last_modified' => $F->lastChange(),
                'last_access' => $F->lastAccess(),
            );
            array_push($files, $file);
        }

        //$logRotation = Configure::read('Backend.LogRotation');
        $logRotation = [];

        $this->set(compact('files', 'logRotation'));
    }

    public function view($logFile = null)
    {
        if (!$logFile) {
            $this->Flash->error(__d('backend', 'No logFile selected'));
            $this->redirect(array('action' => 'index'));
        }

        $filePath = $this->_getFilePath($logFile);
        if (!$filePath || !file_exists($filePath)) {
            $this->Flash->error(__d('backend', 'Logfile {0} not found in {1}', $logFile, $filePath));
            //return $this->redirect(array('action' => 'index'));
            return;
        }

        $page = ($this->request->query('page')) ?: 1;
        $length = 409600; // 400 kB
        $offset = ($page - 1) * $length;

        $File = new File($filePath, false);
        $File->offset($offset);
        $log = $File->read($length); // read max 400 kB
        $File->close();
        $this->set(compact('logFile', 'log', 'page'));
    }

    public function clear($logFile = null)
    {
        if (!$logFile) {
            $this->Flash->error(__d('backend', 'No logFile selected'));
            $this->redirect(array('action' => 'index'));
        }

        $filePath = $this->_getFilePath($logFile);
        if (!$filePath) {
            $this->Flash->error(__d('backend', 'Logfile {0} not found', $logFile));
            return $this->redirect($this->referer(array('action' => 'index')));
        }

        $File = new File($filePath, false);
        if ($File->write("")) {
            $this->Flash->success(__d('backend', 'Logfile {0} cleared', $logFile));
        } else {
            $this->Flash->error(__d('backend', 'Failed to clear logFile {0}', $logFile));
        }
        $this->redirect(array('action' => 'index'));
    }

    public function delete($logFile = null)
    {
        if (!$logFile) {
            $this->Flash->error(__d('backend', 'No logFile selected'));
            $this->redirect(array('action' => 'index'));
        }

        $filePath = $this->_getFilePath($logFile);
        if (unlink($filePath)) {
            $this->Flash->success(__d('backend', 'Logfile {0} deleted', $logFile));
        } else {
            $this->Flash->error(__d('backend', 'Logfile {0} could not be deleted', $logFile));
        }
        $this->redirect(array('action' => 'index'));
    }

    /*
    public function rotate($alias = null)
    {
        $L = new LogRotation($alias);
        if ($L->rotate()) {
            $this->Flash->success(__('Ok'));
        } else {
            $this->Flash->error(__('LogRotation for {0} failed', $alias));
        }

        $this->redirect($this->referer());
    }
    */
}