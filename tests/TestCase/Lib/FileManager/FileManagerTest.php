<?php

namespace Backend\Test\TestCase\Lib\FileManager;

use Cake\TestSuite\TestCase;
use Cake\Filesystem\Folder;
use Backend\Lib\FileManager\FileManager;

class FileManagerTest extends TestCase
{
    /**
     * @var string
     */
    public static $rootPath;

    /**
     * @var Folder
     */
    public static $root;

    /**
     * @var FileManager
     */
    public $FileManager;

    public static function setUpBeforeClass()
    {
        $tmpRoot = TMP . 'tests' . DS . 'file_manager' . DS;
        $Root = new Folder($tmpRoot, true);
        if (!is_dir($tmpRoot)) {
            throw new \Exception('The temporary test directory could not be created');
        }
        static::$root = $Root;
        static::$rootPath = $tmpRoot;
    }

    public static function tearDownAfterClass()
    {
        static::$root->delete(static::$rootPath);
    }

    public function setUp()
    {
        $this->FileManager = new FileManager([
           'root' => static::$rootPath
        ]);
    }

    public function testMakeDir()
    {
        $this->assertInstanceOf('Backend\Lib\FileManager\Directory', $this->FileManager->makeDir('test'));
        $this->assertTrue(is_dir(static::$rootPath . 'test'));

        $this->assertInstanceOf('Backend\Lib\FileManager\Directory', $this->FileManager->makeDir('test/a'));
        $this->assertTrue(is_dir(static::$rootPath . 'test2/a'));
    }

    public function testOpenDir()
    {
        $this->assertInstanceOf('Backend\Lib\FileManager\Directory', $this->FileManager->openDir('test'));
        $this->assertInstanceOf('Backend\Lib\FileManager\Directory', $this->FileManager->openDir('/test'));
        $this->assertInstanceOf('Backend\Lib\FileManager\Directory', $this->FileManager->openDir('/test2/a'));
    }

    public function testMoveDir()
    {

    }

    public function testRemoveDir()
    {
        $this->assertTrue($this->FileManager->removeDir('test'));
        $this->assertTrue($this->FileManager->removeDir('test2/a'));
    }
}
