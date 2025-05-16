<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;
use Cake\Http\Response;

/**
 * Class PublishAction
 *
 * @package Admin\Action
 */
class PublishAction extends BaseEntityAction
{
    protected array $scope = ['table'];

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return __d('admin', 'Publish');
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return ['data-icon' => 'eye'];
    }

    /**
     * @inheritDoc
     */
    public function isUsable(EntityInterface $entity): bool
    {
        return !$entity->get('is_published');
    }

    /**
     * @inheritDoc
     */
    protected function _execute(Controller $controller): ?Response
    {
        if (!$this->model()->hasBehavior('Publish')) {
            $controller->Flash->error('Publish behavior not loaded for model ' . $this->model()->getAlias());

            return $controller->redirect($controller->referer(['action' => 'index']));
        }

        $entity = $this->entity();
        if ($controller->getRequest()->is(['put', 'post'])) {
            $entity = $this->model()->patchEntity($entity, $controller->getRequest()->getData(), [
                //'fieldList' => ['is_published', 'publish_start', 'publish_end'], //@TODO Enable fieldList
            ]);
            if ($this->model()->save($entity)) {
                $controller->Flash->success(__d('admin', 'Updated'));
                $controller->redirect($controller->referer(['action' => 'view', $entity->id]));
            } else {
                $controller->Flash->error(__d('admin', 'Update failed'));
            }
        }

        $controller->set('entity', $entity);

        return null;
    }
}
