<?php

namespace Backend\Service;

use Backend\BackendService;
use Cake\Event\Event;

class TreeService extends BackendService
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
                    if ($Model->behaviors()->has('Tree')) {
                        $event->data['actions']['index'] = 'Backend.TreeIndex';
                        //$event->data['actions']['view']     = 'Backend.View';
                        //$event->data['actions']['edit']     = 'Backend.Edit';
                        //$event->data['actions']['delete']   = 'Backend.Delete';
                        //$event->data['actions']['moveup']   = 'Backend.TreeMoveUp';
                        //$event->data['actions']['movedown'] = 'Backend.TreeMoveDown';
                        $event->data['actions']['sort'] = 'Backend.TreeSort';
                        //$event->data['actions']['repair'] = 'Backend.TreeRepair';
                    }
                }
            }]
        ];
    }
}
