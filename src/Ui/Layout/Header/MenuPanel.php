<?php
declare(strict_types=1);

namespace Admin\Ui\Layout\Header;

use Admin\Admin;
use Admin\Ui\Layout\BaseLayoutElement;

class MenuPanel extends BaseLayoutElement
{
    protected ?string $elementName = 'Admin.layout/admin/header/sysmenu_panel';

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        $items = Admin::getMenu('admin_system');

        return compact('items');
    }
}
