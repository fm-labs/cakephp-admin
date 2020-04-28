<?php
declare(strict_types=1);

namespace Admin\Test\TestCase\View\Helper;

use Admin\View\Helper\VectorMapHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * Admin\View\Helper\VectorMapHelper Test Case
 */
class VectorMapHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Admin\View\Helper\VectorMapHelper
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
