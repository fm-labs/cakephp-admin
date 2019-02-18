<?php
namespace Backend\View\Widget;

use Cake\Core\Configure;
use Cake\View\Form\ContextInterface;
use Cake\View\View;
use Cake\View\Widget\SelectBoxWidget;

class Select2Widget extends SelectBoxWidget
{
    /**
     * Constructor.
     *
     * @param \Cake\View\StringTemplate $templates Templates list.
     */
    public function __construct($templates, View $view)
    {
        parent::__construct($templates);

        $view->loadHelper('Backend.Select2');
    }

    /**
     * {@inheritDoc}
     */
    public function render(array $data, ContextInterface $context)
    {
        $data += [
            'id' => uniqid('select2'),
            'class' => 'form-control',
            'disabled' => null,
            'multiple' => null,
            'width' => null,
        ];

        // https://select2.org/configuration/options-api
        $select2 = [
            'debug' => Configure::read('debug'),
            'placeholder' => __d('backend', 'Select an option'),
            'theme' => 'bootstrap',
            'minimumResultsForSearch' => 10,
            'allowClear' => false,
            'closeOnSelect' => true,
            'disabled' => ($data['disabled']) ? true : false,
            'multiple' => ($data['multiple']) ? true : false,
            'width' => $data['width'],
            'language' => 'en', //@TODO Localization
            //'amdBase' => '/backend/libs/select2/dist/js/', // <-- not working (?)
            //'amdLanguageBase' => '/backend/libs/select2/dist/js/i18n/'// <-- not working (?)
            //// Browser JS console output: Select2: The language file for "./i18n/de" could not be automatically loaded. A fallback will be used instead.
        ];
        if (isset($data['select2'])) {
            $select2 = array_merge($select2, (array)$data['select2']);
            unset($data['select2']);
        }

        if (isset($data['empty']) && $data['empty']) {
            $select2['allowClear'] = true;

            if (is_string($data['empty'])) {
                $select2['placeholder'] = $data['empty'];
                $data['empty'] = true;
            }
        }

        $html = parent::render($data, $context);
        $jsTemplate = "<script>$(document).ready(function() { $('#%s').select2(%s); });</script>";
        $js = sprintf($jsTemplate, $data['id'], json_encode($select2));

        return $html . $js;
    }
}
