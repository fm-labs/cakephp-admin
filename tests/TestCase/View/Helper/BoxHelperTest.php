<?php
declare(strict_types=1);

namespace Admin\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * Class BoxHelperTest
 *
 * @package Admin\Test\TestCase\View\Helper
 */
class BoxHelperTest extends TestCase
{
    /*
     * Test create method
     */
    public function testCreate()
    {
        $view = new View();
        $view->loadHelper('Admin.Box');

        $view->Box->create('Collapsable');
        $view->Box->body('The body of the box');

        $expected = <<<HTML
<div class="box box-default"><div class="box-header with-border"><h3 class="box-title">Collapsable</h3><div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div></div><div class="box-body">The body of the box</div></div>
HTML;
        //$this->assertEquals($expected, $view->Box->render());
        $this->markTestIncomplete();
    }
}
