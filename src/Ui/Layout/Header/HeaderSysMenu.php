<?php

namespace Admin\Ui\Layout\Header;

use Admin\Ui\Layout\BaseLayoutElement;

class HeaderSysMenu extends BaseLayoutElement
{
    protected $elementName = "Admin.layout/admin/header/sysmenu";

    public function data(): array
    {
        $items = \Admin\Admin::getMenu('admin_system')->toArray();
        return compact('items');
    }
}
