<?php

namespace Backend\Lib\FileManager;

use Cake\Filesystem\File as CakeFile;

class File
{
    /**
     * @var FileManager
     */
    public $FileManager;

    /**
     * @var CakeFile
     */
    protected $_File;

    protected $_file;

    protected $_path;

    public function __construct(FileManager $fm, $file, $create = false)
    {
        $this->FileManager = $fm;
        $this->_buildPath($file);
        $this->_File = new CakeFile($this->_path, $create);
    }

    public function root()
    {
        return $this->FileManager->root();
    }

    protected function _buildPath($file)
    {
        $this->_file = $file;
        $this->_path = $this->root() . ltrim('\/', $file) . DS;
    }
}
