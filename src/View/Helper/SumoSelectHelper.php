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

    public $helpers = ['Html'];

    public function initialize(array $config)
    {
        $this->Html->css($this->config('cssUrl'), ['block' => true]);
        $this->Html->script($this->config('scriptUrl'), ['block' => true]);
    }

//    public function selectbox($options = [])
//    {
//        $defaultSumo = [
//            //'placeholder' => __('Select Here'),
//            //'csvDispCount' => 3,
//            //'captionFormat' => __('{0} Selected'),
//            //'captionFormatAllSelected' => __('{0} all selected!'),
//        ];
//
//        $options = array_merge($defaultSumo, $this->config('sumo'), $options);
//
//    }
}
