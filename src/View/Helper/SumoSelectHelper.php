<?php
declare(strict_types=1);

namespace Admin\View\Helper;

use Cake\View\Helper;

class SumoSelectHelper extends Helper
{
    protected $_defaultConfig = [
        'scriptUrl' => '/admin/libs/sumoselect/jquery.sumoselect.min.js',
        'cssUrl' => '/admin/libs/sumoselect/sumoselect.css',
        'sumo' => [],
    ];

    public $helpers = ['Html', 'Form'];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        $this->Html->css($this->getConfig('cssUrl'), ['block' => true]);
        $this->Html->script($this->getConfig('scriptUrl'), ['block' => true]);

        $this->Form->addWidget('sumoselect', ['Admin\View\Widget\SumoSelectBoxWidget', '_view']);
    }

//    public function selectbox($options = [])
//    {
//        $defaultSumo = [
//            //'placeholder' => __d('admin', 'Select Here'),
//            //'csvDispCount' => 3,
//            //'captionFormat' => __d('admin', '{0} Selected'),
//            //'captionFormatAllSelected' => __d('admin', '{0} all selected!'),
//        ];
//
//        $options = array_merge($defaultSumo, $this->getConfig('sumo'), $options);
//
//    }
}
