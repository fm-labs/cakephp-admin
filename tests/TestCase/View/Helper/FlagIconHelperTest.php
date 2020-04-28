<?php
declare(strict_types=1);

namespace Admin\Test\TestCase\View\Helper;

use Admin\View\Helper\FlagIconHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * Admin\View\Helper\FlagIconHelper Test Case
 */
class FlagIconHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Admin\View\Helper\FlagIconHelper
     */
    public $FlagIcon;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->FlagIcon = new FlagIconHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FlagIcon);

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
