<?php
namespace Backend\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * VectorMap helper.
 *
 * Render vector maps using jqvmap.js
 * @link https://www.10bestdesign.com/jqvmap/documentation/
 *
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class VectorMapHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'scriptUrl' => 'Backend./libs/jqvmap/jquery.vmap.min.js',
        'cssUrl' => 'Backend./libs/jqvmap/jqvmap.min.css',
        'mapsBaseUrl' => 'Backend./libs/jqvmap/maps/',
    ];

    public $helpers = ['Html'];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        $this->Html->css($this->getConfig('cssUrl'), ['block' => true]);
        $this->Html->script($this->getConfig('scriptUrl'), ['block' => true]);
    }

    public function create()
    {
    }
}
