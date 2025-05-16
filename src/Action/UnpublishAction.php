<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;
use Cake\Http\Response;

/**
 * Class UnpublishAction
 *
 * @package Admin\Action
 */
class UnpublishAction extends BaseEntityAction
{
    protected array $scope = ['table'];

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return __d('admin', 'Unpublish');
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return ['data-icon' => 'eye-slash'];
    }

    /**
     * @inheritDoc
     */
    public function isUsable(EntityInterface $entity): bool
    {
        return $entity->get('is_published');
    }

    /**
     * @inheritDoc
     */
    public function _execute(Controller $controller): ?Response
    {
        if ($this->model()->hasBehavior('Publish')) {
            $entity = $this->entity();
            $entity->is_published = false; //@TODO Use Publishabel behavior methods

            if ($this->model()->save($entity)) {
                $controller->Flash->success(__d('admin', 'Unpublished'));
            } else {
                $controller->Flash->error(__d('admin', 'Unpublish failed'));
            }
        } else {
            $controller->Flash->error('Publish behavior not loaded for model ' . $this->model()->getAlias());

            return $controller->redirect($controller->referer(['action' => 'index']));
        }

        return $controller->redirect($controller->referer(['action' => 'index']));
    }
}
