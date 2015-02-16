<?php

namespace Backend\Lib\FileManager;

use Cake\Core\Exception\Exception;
use Cake\Filesystem\Folder;

/**
 * Class Directory
 * @package Backend\Lib\FileManager
 */
class Directory
{

    /**
     * @var FileManager
     */
    public $FileManager;

    /**
     * @var CakeFolder
     */
    protected $_Folder;

    /**
     * @var string Basename of current dir
     */
    protected $_name = '';

    /**
     * @var string Relative path to FileManager root
     */
    protected $_path = '/';

    /**
     * @var string Absolute path on file system
     */
    protected $_realPath;

    public function __construct(FileManager $fm, $dir, $create = false)
    {
        $this->FileManager = $fm;
        $this->_setup($dir);
        $this->_Folder = new Folder($this->_realPath, $create);

        if (!is_dir($this->_realPath) || !is_readable($this->_realPath)) {
            throw new Exception('Directory ' . $this->_realPath . ' does not exist or is not readable');
        }
    }

    protected function _setup($dir)
    {
        $this->_realPath = $this->root();
        $dir = ltrim(rtrim($dir, '\/'), '\/');
        if ($dir) {
            $this->_name = basename($dir);
            $this->_path = $dir . '/';
            $this->_realPath = $this->root() . $dir . DS;
        }
    }

    public function path()
    {
        return $this->_path;
    }

    /**
     * @return string Absolute path to FileManager root
     */
    public function root()
    {
        return $this->FileManager->root();
    }

    /**
     * @return bool True if current directory is same as FileManager root
     */
    public function isRoot()
    {
        return ($this->_path === "/") ? true : false;
    }

    /**
     * @return string Absolute path to pwd
     */
    public function pwd()
    {
        return $this->_Folder->pwd();
    }

    public function parent()
    {
        if ($this->isRoot()) {
            return false;
        }
        $dir = rtrim($this->_path, '\/');
        $parts = explode('/', $dir);
        array_pop($parts);
        $parent = implode('/', $parts) . '/';
        return $parent;
    }

    public function files()
    {
        list(,$files) = $this->_Folder->read();
        return $files;
    }

    /**
     * @return array List of Directory objects
     */
    public function directories()
    {
        list($dirs,) = $this->_Folder->read();
        $collection = [];
        foreach ($dirs as $dir) {
            $collection[] = new Directory($this->FileManager, $this->_path . $dir);
        }
        return $collection;
    }

    /**
     * @see Folder::delete()
     * @return bool
     */
    public function delete()
    {
        return $this->_Folder->delete($this->_path);
    }

    /**
     * @return string Directory name
     */
    public function __toString()
    {
        return $this->_name;
    }

}
