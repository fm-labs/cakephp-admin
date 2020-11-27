<?php
declare(strict_types=1);

namespace Admin\Ui\Layout;

use Cake\Utility\Inflector;
use Cupcake\Ui\UiElement;
use Cupcake\Ui\UiElementInterface;

abstract class LayoutElement extends UiElement
{
    public $plugin = 'Admin';

    protected $elementBase = 'ui/';
}
