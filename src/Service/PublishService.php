<?php
declare(strict_types=1);

namespace Admin\Service;

use Admin\Action\EditAction;
use Cake\Event\Event;

class PublishService extends AdminService
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Admin.Controller.setupActions' => ['callable' => function (Event $event): void {
                $modelClass = $event->getSubject()->modelClass;
                if ($modelClass) {
                    $Model = $event->getSubject()->loadModel($modelClass);
                    if ($Model->behaviors()->has('Publish')) {
                        $event->getData('actions')['publish'] = 'Admin.Publish';
                        $event->getData('actions')['unpublish'] = 'Admin.Unpublish';
                    }
                }
            }],

            'Admin.beforeAction' => ['callable' => function (Event $event): void {

                if ($event->getData('action') instanceof EditAction) {
                    $elements = $event->getSubject()->viewVars['form_elements'] ?? [];

                    $elements['translate'] = [
                        'title' => __d('admin', 'Translations'),
                        //'cell' => 'Cupcake.TranslateEntityForm',
                        'element' => 'Admin.Action/Edit/info_translate',

                    ];

                    $elements['publishable'] = [
                        'title' => __d('admin', 'Publishing'),
                        'helpers' => [],
                        //'cell' => 'Cupcake.PublishEntityForm',
                        'element' => 'Admin.Action/Edit/info_publish',
                    ];

                    /*
                    $elements['media'] = [
                        'title' => __d('admin', 'Media'),
                        'helpers' => ['Media.Media', 'Media.MediaPicker'],
                        'cell' => 'Cupcake.MediaEntityForm',
                    ];
                    */

                    $event->getSubject()->set('form_elements', $elements);
                }
            }],
        ];
    }
}
