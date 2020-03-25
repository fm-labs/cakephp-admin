<?php
declare(strict_types=1);

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;

/**
 * Class UnpublishAction
 *
 * @package Backend\Action
 */
class UnpublishAction extends BaseEntityAction
{
    public $scope = ['table'];

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __d('backend', 'Unpublish');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'eye-slash'];
    }

    /**
     * {@inheritDoc}
     */
    public function isUsable(EntityInterface $entity)
    {
        return $entity->get('is_published');
    }

    /**
     * {@inheritDoc}
     */
    public function _execute(Controller $controller)
    {
        if ($this->model()->hasBehavior('Publishable')) {
            $entity = $this->entity();
            $entity->is_published = false; //@TODO Use Publishabel behavior methods

            if ($this->model()->save($entity)) {
                $controller->Flash->success(__d('backend', 'Unpublished'));
            } else {
                $controller->Flash->error(__d('backend', 'Unpublish failed'));
            }
        } else {
            $controller->Flash->error('Publishable behavior not loaded for model ' . $this->model()->getAlias());

            return $controller->redirect($controller->referer(['action' => 'index']));
        }

        return $controller->redirect($controller->referer(['action' => 'index']));
    }
}
