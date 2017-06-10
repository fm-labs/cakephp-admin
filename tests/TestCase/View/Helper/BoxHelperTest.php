<?php

namespace Backend\Test\TestCase\View\Helper;

use Backend\View\Helper\BoxHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * Class BoxHelperTest
 * @package Backend\Test\TestCase\View\Helper
 */
class BoxHelperTest extends TestCase
{
    /*
     * Test create method
     */
    public function testCreate()
    {
        $helper = new BoxHelper(new View());
        $helper->create('Collapsable');

        $expected = <<<HTML
<div class="box box-default">
    <div class="box-header with-border">
    <h3 class="box-title">Collapsable</h3>
    <div class="box-tools pull-right">
    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
    </div>
    <div class="box-body">
    The body of the box
    </div>
    </div>
HTML;

        $this->assertEquals($expected, $helper->render());
    }
}
