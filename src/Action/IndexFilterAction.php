<?php

namespace Backend\Action;

class IndexFilterAction extends IndexAction
{
    public $template = 'Backend.index';

    public function getLabel()
    {
        return __d('backend', "Filter");
    }

    public function _fetchResult()
    {
        return parent::_fetchResult();
    }
}
