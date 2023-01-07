<?php

namespace Admin\Ui\Layout\Header;

use Admin\Ui\Layout\BaseLayoutElement;

class MenuPanel extends BaseLayoutElement
{
    protected $elementName = "Admin.layout/admin/header/sysmenu_panel";

    public function data(): array
    {
        $items = \Admin\Admin::getMenu('admin_system');

        return compact('items');
    }
}
