<?php
declare(strict_types=1);

namespace Admin\Action;

class IndexFilterAction extends IndexAction
{
    public $template = 'Admin.index';

    public function getLabel()
    {
        return __d('admin', "Filter");
    }

    public function _fetchResult()
    {
        return parent::_fetchResult();
    }
}
