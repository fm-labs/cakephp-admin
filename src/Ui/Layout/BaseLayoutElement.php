<?php
declare(strict_types=1);

namespace Admin\Ui\Layout;

use Cupcake\Ui\UiElement;

abstract class BaseLayoutElement extends UiElement
{
    public ?string $plugin = 'Admin';

    protected string $elementBase = 'ui/';
}
