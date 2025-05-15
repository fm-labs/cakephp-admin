<?php
declare(strict_types=1);

namespace Admin\Ui\Layout\Header;

use Admin\Admin;
use Admin\Ui\Layout\BaseLayoutElement;

class HeaderSysMenu extends BaseLayoutElement
{
    protected ?string $elementName = 'Admin.layout/admin/header/sysmenu';

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        $items = Admin::getMenu('admin_system')->toArray();

        return compact('items');
    }
}
