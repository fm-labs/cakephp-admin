<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/25/15
 * Time: 6:05 PM
 */

namespace Backend\View;

use Cake\View\View;

class BackendView extends View
{
    public function initialize()
    {
        $this->helpers = [
            'Html',
            'Form' => [
                'className' => 'Backend\View\Helper\BackendFormHelper',
                'templates' => 'Backend.form_templates',
                'widgets' => [
                    '_default' => ['Backend\View\Widget\BasicWidget'],
                    'button' => ['Backend\View\Widget\ButtonWidget'],
                    'select' => ['Backend\View\Widget\ChosenSelectBoxWidget'],
                    'textarea' => ['Backend\View\Widget\TextareaWidget'],
                    'htmleditor' => ['Backend\View\Widget\HtmlEditorWidget'],
                    'htmltext' => ['Backend\View\Widget\HtmlTextWidget'],
                    'datepicker' => ['Backend\View\Widget\DatePickerWidget'],
                    'timepicker' => ['Backend\View\Widget\TimePickerWidget'],
                    'imageselect' => ['Backend\View\Widget\ImageSelectWidget'],
                    'imagemodal' => ['Backend\View\Widget\ImageModalWidget'],
                ]
            ],
            'Paginator' => [
                'templates' => 'Backend.paginator_templates'
            ],
            'Backend.Backend',
            //'Backend.TinyMce',
            'Backend.Toolbar',
            'Backend.Ui'
        ];

        $this->loadHelper('Html');

        $this->Html->css('Backend.chosen/chosen.min', ['block' => 'cssBackend']);
        $this->Html->css('Backend.pickadate/themes/classic', ['block' => 'cssBackend']);
        $this->Html->css('Backend.pickadate/themes/classic.date', ['block' => 'cssBackend']);
        $this->Html->css('Backend.pickadate/themes/classic.time', ['block' => 'cssBackend']);
        $this->Html->css('Backend.imagepicker/image-picker', ['block' => 'cssBackend']);
        //$this->Html->css('Backend.backend', ['block' => 'cssBackend']);

        $beScript = <<<SCRIPT
var _backendConf = {
    rootUrl: '{{ROOTURL}}'
};
var _backend = (function (conf) {
    return {
        rootUrl: conf.rootUrl
            }
    })(_backendConf);
SCRIPT;
        $beScript = str_replace(['{{ROOTURL}}'], [$this->Url->build('/')], $beScript);
        $this->Html->scriptBlock($beScript, ['block' => true]);

        $this->Html->script('Backend.jquery/jquery-1.11.2.min', ['block' => true]);

        $this->Html->script('Backend.tinymce/tinymce.min', ['block' => 'scriptBackend']);
        $this->Html->script('Backend.tinymce/jquery.tinymce.min', ['block' => 'scriptBackend']);
        $this->Html->script('Backend.chosen/chosen.jquery.min', ['block' => 'scriptBackend']);
        $this->Html->script('Backend.pickadate/picker', ['block' => 'scriptBackend']);
        $this->Html->script('Backend.pickadate/picker.date', ['block' => 'scriptBackend']);
        $this->Html->script('Backend.pickadate/picker.time', ['block' => 'scriptBackend']);
        $this->Html->script('Backend.imagepicker/image-picker.min', ['block' => 'scriptBackend']);

    }

    public function renderLayout($content, $layout = null)
    {

        $title = $this->Blocks->get('title');
        if ($title === '') {
            $this->Blocks->set('title', $this->request['controller']);
        }

        return parent::renderLayout($content, $layout);
    }
}
