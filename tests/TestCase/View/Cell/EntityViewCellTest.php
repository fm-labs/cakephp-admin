<?php
declare(strict_types=1);

namespace Admin\Test\TestCase\View\Cell;

use Admin\View\Cell\EntityViewCell;
use Cake\TestSuite\TestCase;

/**
 * Admin\View\Cell\EntityViewCell Test Case
 */
class EntityViewCellTest extends TestCase
{
    /**
     * Request mock
     *
     * @var \Cake\Http\ServerRequest|\PHPUnit\Framework\MockObject\MockObject
     */
    public $request;

    /**
     * Response mock
     *
     * @var \Cake\Http\Response|\PHPUnit\Framework\MockObject\MockObject
     */
    public $response;

    /**
     * Test subject
     *
     * @var \Admin\View\Cell\EntityViewCell
     */
    public $EntityView;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->request = $this->getMockBuilder('Cake\Http\ServerRequest')->getMock();
        $this->response = $this->getMockBuilder('Cake\Http\Response')->getMock();
        $this->EntityView = new EntityViewCell($this->request, $this->response);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->EntityView);

        parent::tearDown();
    }

    /**
     * Test display method
     *
     * @return void
     */
    public function testDisplay()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
