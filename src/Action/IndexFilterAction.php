<?php
declare(strict_types=1);

namespace Admin\Action;

/**
 * IndexFilterAction class
 *
 * @deprecated
 */
class IndexFilterAction extends IndexAction
{
    public ?string $template = 'Admin.index';

    public function getLabel(): string
    {
        return __d('admin', 'Filter');
    }

    public function _fetchResult()
    {
        return parent::_fetchResult();
    }
}
