<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 2/15/15
 * Time: 9:47 PM
 */

namespace Backend\Lib\FileManager;

use Backend\Lib\FileManager\File;
use Backend\Lib\FileManager\Folder;
use \InvalidArgumentException;

class FileManager
{
    protected $_config = [
        'root' => null,
    ];

    public function __construct($config = [])
    {
        if (!is_array($config)) {
            throw new InvalidArgumentException('Malformed config. Expects array.');
        }

        $this->_config = array_merge($this->_config, $config);
        if (empty($this->_config['root'])) {
            throw new InvalidArgumentException('Invalid config. No root path defined.');
        } elseif (!is_dir($this->_config['root'])) {
            throw new InvalidArgumentException('Invalid config. Root path ' . $this->_config['root'] . ' does not exist.');
        }
    }

    public function root()
    {
        return $this->_config['root'];
    }

    public function makeDir($dir)
    {
        return new Directory($this, $dir, true);
    }

    public function openDir($dir)
    {
        return new Directory($this, $dir);
    }

    public function moveDir($dir, $target)
    {

    }

    public function removeDir($dir)
    {
        $d = new Directory($this, $dir);
        return $d->delete();
    }

    public function makeFile($file)
    {

    }

    public function openFile($file)
    {

    }

    public function moveFile($file, $target)
    {

    }

    public function removeFile($file)
    {

    }
}
