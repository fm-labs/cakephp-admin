<?php
namespace Backend\Test\TestCase\View\Helper;

use Backend\View\Helper\VectorMapHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * Backend\View\Helper\VectorMapHelper Test Case
 */
class VectorMapHelperTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Backend\View\Helper\VectorMapHelper
     */
    public $VectorMap;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->VectorMap = new VectorMapHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->VectorMap);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
