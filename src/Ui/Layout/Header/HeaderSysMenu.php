<?php

namespace Admin\Ui\Layout\Header;

use Admin\Ui\Layout\LayoutElement;

class HeaderSysMenu extends LayoutElement
{
    protected $elementName = "Admin.layout/admin/header/sysmenu";

    public function data(): array
    {
        $items = \Admin\Admin::getMenu('admin_system')->toArray();
        return compact('items');
    }
}
