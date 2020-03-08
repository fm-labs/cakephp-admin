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
                        $event->getData('actions')['index'] = 'Backend.TreeIndex';
                        //$event->getData('actions')['view']     = 'Backend.View';
                        //$event->getData('actions')['edit']     = 'Backend.Edit';
                        //$event->getData('actions')['delete']   = 'Backend.Delete';
                        //$event->getData('actions')['moveup']   = 'Backend.TreeMoveUp';
                        //$event->getData('actions')['movedown'] = 'Backend.TreeMoveDown';
                        $event->getData('actions')['sort'] = 'Backend.TreeSort';
                        //$event->getData('actions')['repair'] = 'Backend.TreeRepair';
                    }
                }
            }],
        ];
    }
}
