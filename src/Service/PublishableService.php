<?php

namespace Backend\Service;

use Backend\Action\EditAction;
use Backend\BackendService;
use Cake\Event\Event;

class PublishableService extends BackendService
{
    /**
     * {@inheritDoc}
     */
    public function implementedEvents()
    {
        return [
            'Backend.Controller.setupActions' => ['callable' => function (Event $event) {
                $modelClass = $event->getSubject()->modelClass;
                if ($modelClass) {
                    $Model = $event->getSubject()->loadModel($modelClass);
                    if ($Model->behaviors()->has('Publishable')) {
                        $event->data['actions']['publish'] = 'Backend.Publish';
                        $event->data['actions']['unpublish'] = 'Backend.Unpublish';
                    }
                }
            }],

            'Backend.beforeAction' => ['callable' => function (Event $event) {

                if ($event->data['action'] instanceof EditAction) {
                    $elements = (isset($event->getSubject()->viewVars['form_elements'])) ? $event->getSubject()->viewVars['form_elements'] : [];

                    $elements['translate'] = [
                        'title' => __d('backend', 'Translations'),
                        //'cell' => 'Banana.TranslateEntityForm',
                        'element' => 'Backend.Action/Edit/info_translate',

                    ];

                    $elements['publishable'] = [
                        'title' => __d('backend', 'Publishing'),
                        'helpers' => [],
                        //'cell' => 'Banana.PublishEntityForm',
                        'element' => 'Backend.Action/Edit/info_publish',
                    ];

                    $elements['media'] = [
                        'title' => __d('backend', 'Media'),
                        'helpers' => ['Media.Media', 'Media.MediaPicker'],
                        'cell' => 'Banana.MediaEntityForm',
                    ];

                    $event->getSubject()->set('form_elements', $elements);
                }
            }]
        ];
    }
}
