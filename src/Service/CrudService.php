<?php
declare(strict_types=1);

namespace Admin\Service;

use Admin\AdminService;
use Cake\Event\Event;

class CrudService extends AdminService
{
    /**
     * {@inheritDoc}
     */
    public function implementedEvents(): array
    {
        return [
            'Admin.Controller.setupActions' => ['callable' => function (Event $event) {

                if (isset($event->getSubject()->scaffold)) {
                    if (!isset($event->getData('actions')['index'])) {
                        $event->getData('actions')['index'] = 'Admin.Index';
                    }
                    if (!isset($event->getData('actions')['add'])) {
                        $event->getData('actions')['add'] = 'Admin.Add';
                    }
                    if (!isset($event->getData('actions')['view'])) {
                        $event->getData('actions')['view'] = 'Admin.View';
                    }
                    if (!isset($event->getData('actions')['edit'])) {
                        $event->getData('actions')['edit'] = 'Admin.Edit';
                    }
                    if (!isset($event->getData('actions')['delete'])) {
                        $event->getData('actions')['delete'] = 'Admin.Delete';
                    }
                }
            }],
        ];
    }
}
