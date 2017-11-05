<?php

namespace Backend\Service;

use Backend\Action\EditAction;
use Backend\BackendService;
use Cake\Event\Event;

class PublishableService extends BackendService
{
    public function implementedEvents()
    {
        return [
            'Backend.Controller.setupActions' => ['callable' => function(Event $event) {
                $modelClass = $event->subject()->modelClass;
                if ($modelClass) {
                    $Model = $event->subject()->loadModel($modelClass);
                    if ($Model->behaviors()->has('Publishable')) {
                        $event->data['actions']['publish'] = 'Backend.Publish';
                        $event->data['actions']['unpublish'] = 'Backend.Unpublish';
                    }
                }
            }],

            'Backend.beforeAction' => ['callable' => function(Event $event) {

                if ($event->data['action'] instanceof EditAction) {
                    $elements = (isset($event->subject()->viewVars['form_elements'])) ? $event->subject()->viewVars['form_elements'] : [];

                    $elements['translate'] = [
                        'title' => __('Translations'),
                        //'cell' => 'Banana.TranslateEntityForm',
                        'element' => 'Backend.Action/Edit/info_translate',

                    ];

                    $elements['publishable'] = [
                        'title' => __('Publishing'),
                        'helpers' => [],
                        //'cell' => 'Banana.PublishEntityForm',
                        'element' => 'Backend.Action/Edit/info_publish',
                    ];

                    $elements['media'] = [
                        'title' => __('Media'),
                        'helpers' => ['Media.Media', 'Media.MediaPicker'],
                        'cell' => 'Banana.MediaEntityForm',
                    ];

                    $event->subject()->set('form_elements', $elements);
                }
            }]
        ];
    }
}