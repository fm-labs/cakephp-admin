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

        $this->loadHelper('Html', [
            //'className' => 'Backend\View\Helper\BackendHelper'
        ]);
        $this->loadHelper('Form', [
            'className' => 'Backend\View\Helper\BackendFormHelper',
            'widgets' => [
                '_default' => ['Backend\View\Widget\BasicWidget'],
                'button' => ['Backend\View\Widget\ButtonWidget'],
                //'select' => ['Backend\View\Widget\ChosenSelectBoxWidget'],
                'textarea' => ['Backend\View\Widget\TextareaWidget'],
                'htmleditor' => ['Backend\View\Widget\HtmlEditorWidget'],
                'htmltext' => ['Backend\View\Widget\HtmlTextWidget'],
                'datepicker' => ['Backend\View\Widget\DatePickerWidget'],
                'timepicker' => ['Backend\View\Widget\TimePickerWidget'],
                'imageselect' => ['Backend\View\Widget\ImageSelectWidget'],
                'imagemodal' => ['Backend\View\Widget\ImageModalWidget'],
            ]
        ]);
        //$this->loadHelper('Paginator', []);
        $this->loadHelper('Backend.Chosen', []);
        $this->loadHelper('Backend.Ajax', []);
        $this->loadHelper('Backend.Toolbar', []);
        $this->loadHelper('Bootstrap.Ui', []);

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

        //$this->Html->script('_jquery', ['block' => true]);

        $this->Html->css('Backend.chosen/chosen.min', ['block' => true]);
        $this->Html->script('Backend.chosen/chosen.jquery.min', ['block' => 'scriptBottom']);
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
