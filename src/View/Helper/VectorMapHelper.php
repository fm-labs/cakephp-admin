<?php
declare(strict_types=1);

namespace Admin\View\Helper;

use Cake\View\Helper;

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
        'scriptUrl' => 'Admin./libs/jqvmap/jquery.vmap.min.js',
        'cssUrl' => 'Admin./libs/jqvmap/jqvmap.min.css',
        'mapsBaseUrl' => 'Admin./libs/jqvmap/maps/',
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
