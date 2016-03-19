<?php
namespace Backend\Test\TestCase\View\Cell;

use Backend\View\Cell\AttachmentsEditorCell;
use Cake\TestSuite\TestCase;

/**
 * Backend\View\Cell\AttachmentsEditorCell Test Case
 */
class AttachmentsEditorCellTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->request = $this->getMock('Cake\Network\Request');
        $this->response = $this->getMock('Cake\Network\Response');
        $this->AttachmentsEditor = new AttachmentsEditorCell($this->request, $this->response);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AttachmentsEditor);

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
