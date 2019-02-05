<?php

namespace Backend\View\Helper;

use Cake\View\Helper;

class SumoSelectHelper extends Helper
{
    protected $_defaultConfig = [
        'scriptUrl' => '/backend/libs/sumoselect/jquery.sumoselect.min.js',
        'cssUrl' => '/backend/libs/sumoselect/sumoselect.css',
        'sumo' => []
    ];

    public $helpers = ['Html', 'Form'];

    public function initialize(array $config)
    {
        $this->Html->css($this->config('cssUrl'), ['block' => true]);
        $this->Html->script($this->config('scriptUrl'), ['block' => true]);

        $this->Form->addWidget('sumoselect', ['Backend\View\Widget\SumoSelectBoxWidget', '_view']);
    }

//    public function selectbox($options = [])
//    {
//        $defaultSumo = [
//            //'placeholder' => __d('backend', 'Select Here'),
//            //'csvDispCount' => 3,
//            //'captionFormat' => __d('backend', '{0} Selected'),
//            //'captionFormatAllSelected' => __d('backend', '{0} all selected!'),
//        ];
//
//        $options = array_merge($defaultSumo, $this->config('sumo'), $options);
//
//    }
}
