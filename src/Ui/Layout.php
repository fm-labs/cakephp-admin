<?php

namespace Admin\Ui;

use Admin\Ui as AdminUi;
use Cupcake\Ui\Ui;

class Layout extends Ui
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        // the header section will be automatically filled from the element fallback.
        // the header itself fetches from 2 more sub-section
        // a) attaching the sub-element to the parent class
        //$this->bind(AdminUi\Layout\Header\HeaderPanel::class, AdminUi\Layout\Header\Panel\MenuPanel::class);

        // b) attaching the sub-element to the sub-section.
        $this->add('header_panels_right', new AdminUi\Layout\Header\MenuPanel());
        $this->add('header_panels_right', new AdminUi\Layout\Header\UserPanel());
        $this->add('sidebar_panels', new AdminUi\Layout\Sidebar\MenuPanel());
    }
}
