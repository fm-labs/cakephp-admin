<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Exception;

/**
 * Class LogsController
 *
 * @package Admin\Controller\Admin
 */
class LogsController extends AppController
{
    /**
     * @var array
     */
    public array $permissions = [
        'index' => ['logs.view'],
        'view' => ['logs.view'],
        'clear' => ['logs.edit'],
        'rotate' => ['logs.edit'],
        'delete' => ['logs.delete'],
    ];

    public $actions = []; //@TODO Disable ActionComponent

    public ?string $defaultTable = null;

    /**
     * @var string
     */
    public string $logDir = LOGS;

    /**
     * @param string $logFile
     * @return string|bool
     */
    protected function _getFilePath(string $logFile)
    {
        $logFile .= '.log';
        $path = realpath($this->logDir . $logFile);
        if (!$path) {
            return false;
        }

        if (strpos($path, '..') !== false) {
            return false;
        }

        if (!preg_match('/^' . preg_quote($this->logDir, '/') . '/', $path)) {
            return false;
        }

        return $path;
    }

    /**
     * List log files
     */
    public function index()
    {
        $logDir = $this->logDir;

        //$Folder = new Folder($logDir, false);
        //$logFiles = $Folder->find('.*.log(\.[0-9])?', true);

        $logFiles = [];
        $handle = opendir($logDir);
        if (!$handle) {
            $this->Flash->error(__d('admin', 'Failed to open log directory {0}', $logDir));

            return;
        }

        while (($file = readdir($handle)) !== false) {
            if (preg_match('/.*\.log(\.[0-9])?$/', $file)) {
                $logFiles[] = $file;
            }
        }
        closedir($handle);

        $files = [];
        foreach ($logFiles as $logFile) {
            $logFile = $logDir . $logFile;
            $fileSize = filesize($logFile);
            $lastModified = filemtime($logFile);
            $lastAccess = fileatime($logFile);

            $file = [
                'id' => basename($logFile, '.log'),
                'name' => $logFile,
                //'dir' => $logDir,
                'size' => $fileSize,
                'last_modified' => $lastModified,
                'last_access' => $lastAccess,
            ];
            array_push($files, $file);
        }

        //$logRotation = Configure::read('Admin.LogRotation');
        $logRotation = [];

        $this->set(compact('files', 'logRotation'));
    }

    /**
     * View log file
     *
     * @param string|null $logFile
     */
    public function view(?string $logFile = null): void
    {
        if (!$logFile) {
            $this->Flash->error(__d('admin', 'No logFile selected'));
            $this->redirect(['action' => 'index']);
        }

        $filePath = $this->_getFilePath($logFile);
        if (!$filePath || !file_exists($filePath)) {
            $this->Flash->error(__d('admin', 'Logfile {0} not found in {1}', $logFile, $filePath));
            //return $this->redirect(array('action' => 'index'));
            return;
        }

        $page = $this->request->getQuery('page') ?: 1;
        $length = 1 * 1024 * 1024; // 1 MB
        $offset = ($page - 1) * $length;

        $log = '';
        try {
            $handle = fopen($filePath, 'r');
            if ($handle) {
                fseek($handle, $offset);
                $log = fread($handle, $length);
                fclose($handle);
            }
        } catch (Exception $ex) {
            $this->Flash->error(__d('admin', 'Failed to read log file {0}: {1}', $logFile, $ex->getMessage()));

            return;
        }

        $this->set(compact('logFile', 'log', 'page'));
    }

    /**
     * Clear log file
     *
     * @param string|null $logFile
     * @return \Cake\Http\Response|null
     */
    public function clear(?string $logFile = null)
    {
        if (!$logFile) {
            $this->Flash->error(__d('admin', 'No logFile selected'));
            $this->redirect(['action' => 'index']);
        }

        $filePath = $this->_getFilePath($logFile);
        if (!$filePath) {
            $this->Flash->error(__d('admin', 'Logfile {0} not found', $logFile));

            return $this->redirect($this->referer(['action' => 'index']));
        }

        if (file_put_contents($filePath, '') !== false) {
            $this->Flash->success(__d('admin', 'Logfile {0} cleared', $logFile));
        } else {
            $this->Flash->error(__d('admin', 'Failed to clear logFile {0}', $logFile));
        }
        $this->redirect(['action' => 'index']);
    }

    /**
     * Delete log file
     *
     * @param string|null $logFile
     */
    public function delete(?string $logFile = null)
    {
        if (!$logFile) {
            $this->Flash->error(__d('admin', 'No logFile selected'));
            $this->redirect(['action' => 'index']);
        }

        $filePath = $this->_getFilePath($logFile);
        if (unlink($filePath)) {
            $this->Flash->success(__d('admin', 'Logfile {0} deleted', $logFile));
        } else {
            $this->Flash->error(__d('admin', 'Logfile {0} could not be deleted', $logFile));
        }
        $this->redirect(['action' => 'index']);
    }

    /**
     * @param string|null $alias
     * @deprecated
     */
    public function rotate(?string $alias = null)
    {
        /*
        $L = new LogRotation($alias);
        if ($L->rotate()) {
            $this->Flash->success(__d('admin','Ok'));
        } else {
            $this->Flash->error(__d('admin','LogRotation for {0} failed', $alias));
        }
        */
        $this->Flash->error('LogRotation is deprecated. Use log engine features instead');
        $this->redirect($this->referer());
    }
}
