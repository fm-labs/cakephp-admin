<?php
declare(strict_types=1);

namespace Admin\Ui\Layout;

use Admin\Ui\Layout\Sidebar\SidebarMenu;

class Sidebar extends BaseLayoutElement
{
    protected ?string $elementName = 'Admin.layout/admin/sidebar_sticky';
    //protected $elementName = "Admin.layout/admin/sidebar_collapsible";

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        $bodyClass = $this->_View->get('body_class', '');
        $bodyClass = $bodyClass ? $bodyClass . ' sidebar-enabled' : 'sidebar-enabled';

        $this->_Ui->add('sidebar_content', SidebarMenu::class);
    }
}
