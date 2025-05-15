<?php
declare(strict_types=1);

namespace Admin\Ui\Layout;

class Header extends BaseLayoutElement
{
    protected ?string $elementName = 'Admin.layout/admin/header';

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function initialize(): void
    {
        //$this->_Ui->add('header_panels_right', Header\HeaderSysMenu::class);
    }
}
