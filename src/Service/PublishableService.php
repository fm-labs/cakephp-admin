<?php
declare(strict_types=1);

namespace Admin\Service;

use Admin\Action\EditAction;
use Admin\AdminService;
use Cake\Event\Event;

class PublishService extends AdminService
{
    /**
     * {@inheritDoc}
     */
    public function implementedEvents(): array
    {
        return [
            'Admin.Controller.setupActions' => ['callable' => function (Event $event) {
                $modelClass = $event->getSubject()->modelClass;
                if ($modelClass) {
                    $Model = $event->getSubject()->loadModel($modelClass);
                    if ($Model->behaviors()->has('Publish')) {
                        $event->getData('actions')['publish'] = 'Admin.Publish';
                        $event->getData('actions')['unpublish'] = 'Admin.Unpublish';
                    }
                }
            }],

            'Admin.beforeAction' => ['callable' => function (Event $event) {

                if ($event->getData('action') instanceof EditAction) {
                    $elements = $event->getSubject()->viewVars['form_elements'] ?? [];

                    $elements['translate'] = [
                        'title' => __d('admin', 'Translations'),
                        //'cell' => 'Banana.TranslateEntityForm',
                        'element' => 'Admin.Action/Edit/info_translate',

                    ];

                    $elements['publishable'] = [
                        'title' => __d('admin', 'Publishing'),
                        'helpers' => [],
                        //'cell' => 'Banana.PublishEntityForm',
                        'element' => 'Admin.Action/Edit/info_publish',
                    ];

                    /*
                    $elements['media'] = [
                        'title' => __d('admin', 'Media'),
                        'helpers' => ['Media.Media', 'Media.MediaPicker'],
                        'cell' => 'Banana.MediaEntityForm',
                    ];
                    */

                    $event->getSubject()->set('form_elements', $elements);
                }
            }],
        ];
    }
}
