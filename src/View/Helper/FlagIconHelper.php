<?php
declare(strict_types=1);

namespace Admin\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * FlagIcon helper
 */
class FlagIconHelper extends Helper
{
    use StringTemplateTrait;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public $helpers = ['Html'];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        $this->Html->css('/admin/libs/flag-icon-css/css/flag-icon.min.css', ['block' => true]);

        $this->templater()->add([
            'flag_icon' => '<span class="flag-icon flag-icon-{{flag}}"{{attrs}}></span>',
            //'flag_icon_wrapper' => '<div class="flag-wrapper"><div class="img-thumbnail flag flag-icon-background flag-icon-{{flag}}"></div></div>'
        ]);
    }

    /**
     * @param string $flag Flag icon
     * @param array $options Icon options
     * @return string
     */
    public function create($flag, $options = [])
    {
        return $this->templater()->format('flag_icon', [
            'flag' => strtolower($flag),
            'attrs' => $this->templater()->formatAttributes($options),
        ]);
    }
}
