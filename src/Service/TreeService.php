<?php
declare(strict_types=1);

namespace Admin\Service;

use Cake\Event\Event;

class TreeService extends AdminService
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
                    if ($Model->behaviors()->has('Tree')) {
                        $event->getData('actions')['index'] = 'Admin.TreeIndex';
                        //$event->getData('actions')['view']     = 'Admin.View';
                        //$event->getData('actions')['edit']     = 'Admin.Edit';
                        //$event->getData('actions')['delete']   = 'Admin.Delete';
                        //$event->getData('actions')['moveup']   = 'Admin.TreeMoveUp';
                        //$event->getData('actions')['movedown'] = 'Admin.TreeMoveDown';
                        $event->getData('actions')['sort'] = 'Admin.TreeSort';
                        //$event->getData('actions')['repair'] = 'Admin.TreeRepair';
                    }
                }
            }],
        ];
    }
}
